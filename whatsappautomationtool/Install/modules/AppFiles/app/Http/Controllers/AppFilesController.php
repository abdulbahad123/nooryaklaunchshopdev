<?php

namespace Modules\AppFiles\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\AppFiles\Models\Files;
use Modules\AppFiles\Services\UploadFileService;

class AppFilesController extends Controller
{
    protected $uploadFileService;

    public function __construct(UploadFileService $uploadFileService)
    {
        $this->table = "files";
        $this->uploadFileService = $uploadFileService;

        // Allowed file types with robust defaults
        $rawTypes = get_option("file_allowed_file_types", "jpeg,gif,png,jpg,webp,mp4,csv,pdf,mp3,wmv,json");
        if (empty($rawTypes) || trim($rawTypes) === '') {
            $rawTypes = "jpeg,gif,png,jpg,webp,mp4,csv,pdf,mp3,wmv,json";
        }
        $this->allowedFileTypes = array_filter(array_map('trim', explode(',', $rawTypes)));
        if (empty($this->allowedFileTypes)) {
            $this->allowedFileTypes = ['jpeg', 'gif', 'png', 'jpg', 'webp', 'mp4', 'csv', 'pdf', 'mp3', 'wmv', 'json'];
        }

        $maxMb = (int)\Access::permission('appfiles.max_size');
        $this->maxFileSize = ($maxMb > 0 ? $maxMb : 50) * 1024;
    }

    public function index(Request $request)
    {   
        $total = Files::where("team_id", $request->team_id)->count();
        return view('appfiles::index',[
            "total" => $total
        ]);
    }

    public function list(Request $request)
    {
        $data = Files::getList($request);

        if ($data['files']->isEmpty() && $data['current_page'] > 1) {
            return response()->json([
                "status" => 0
            ]);
        }

        switch ($request->segment(3)) {
            case 'popup_list':
                $view = 'appfiles::popup_list';
                break;
            case 'mini_list':
                $view = 'appfiles::mini_list';
                break;
            default:
                $view = 'appfiles::list';
                break;
        }

        return response()->json([
            "status" => 1,
            "data" => view($view, $data)->render()
        ]);
    }

    public function upload_files(Request $request)
    {
        $folder = Files::where([
            "is_folder" => 1,
            "id_secure" => $request->folder_id,
            "team_id" => $request->team_id
        ])->first();

        if (!$request->hasFile('files')) {
            return response()->json([
                "status" => 0,
                "message" => __("Please select at least one file to upload."),
            ]);
        }

        $allowedFileTypes = is_array($this->allowedFileTypes) && !empty($this->allowedFileTypes)
            ? $this->allowedFileTypes
            : ['jpeg', 'gif', 'png', 'jpg', 'webp', 'mp4', 'csv', 'pdf', 'mp3', 'wmv', 'json'];
        $maxFileSizeKB = $this->maxFileSize > 0 ? $this->maxFileSize : 51200;

        $errors = [];
        $validFiles = [];

        foreach ($request->file('files') as $file) {
            if (!$file->isValid()) {
                $errors[$file->getClientOriginalName()] = __('Upload failed: file exceeds server upload limits or was interrupted.');
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowedFileTypes)) {
                $errors[$file->getClientOriginalName()] = __('Invalid file type. Allowed: ') . implode(', ', $allowedFileTypes);
                continue;
            }

            $sizeKB = round($file->getSize() / 1024);
            if ($sizeKB > $maxFileSizeKB) {
                $errors[$file->getClientOriginalName()] = __('Max file size is :size MB.', ['size' => round($maxFileSizeKB / 1024)]);
                continue;
            }

            $validFiles[] = $file;
        }

        if (count($validFiles) === 0) {
            return response()->json([
                "status" => 0,
                "message" => __("All files failed to upload."),
                "errors" => $errors
            ]);
        }

        $request->files->set('files', $validFiles);

        try {
            \UploadFile::handleFileUpload($request, $folder);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 0,
                "message" => $e->getMessage()
            ]);
        }

        if (count($errors)) {
            return response()->json([
                "status" => 2,
                "message" => __("Some files failed to upload."),
                "errors" => $errors
            ]);
        }

        return response()->json([
            "status" => 1,
            "message" => __("Upload successful.")
        ]);
    }

    public function upload_from_url(Request $request){
        $folder_id = $request->id;

        return response()->json([
            "status" => 1,
            "data" => view('appfiles::upload_from_url',[
                "folder_id" => $folder_id
            ])->render()
        ]);
    }

    public function save_file(Request $request)
    {
        // Validate the input
        $request->validate([
            'file_url' => 'required|url',
        ]);

        $file_url = $request->file_url;
        $folder_id = $request->folder_id;
        $folder = Files::where(["is_folder" => 1, "id_secure" => $folder_id, "team_id" => $request->team_id])->first();

        try {
            $filePath = \UploadFile::saveFileFromUrl([
                'file_url' => $file_url, 
                'folder_id' => $folder ? $folder->id : 0,
                'from' => 'url'
            ], $folder);

            return response()->json([
                'status' => 1,
                'message' => 'File saved successfully',
                'file_path' => $filePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function save_files(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|url',
            'folder_id' => 'nullable|string',
        ]);

        $folder = Files::where([
            'is_folder' => 1,
            'id_secure' => $request->folder_id,
            'team_id' => $request->team_id
        ])->first();

        $folder_id = $folder?->id ?? 0;
        $saved = 0;

        try {
            foreach ($request->input('files', []) as $fileUrl) {
                \UploadFile::saveFileFromUrl([
                    'file_url'  => $fileUrl,
                    'folder_id' => $folder_id,
                    'from'      => 'search'
                ], $folder);

                $saved++;
            }

            return response()->json([
                'status' => 1,
                'message' => "Saved {$saved} files successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function save_file_from_cloud(Request $request){

        // Validate the input
        $request->validate([
            'files' => 'required',
        ]);

        $files = $request->input('files');
        $folder_id = $request->folder_id;
        $folder = Files::where(["is_folder" => 1, "id_secure" => $folder_id, "team_id" => $request->team_id])->first();
        $folder_id =$folder ? $folder->id : 0;

        try {
            $result = \UploadFile::saveMultipleFilesFromUrls($files, $folder_id);

            return response()->json([
                'status' => 1,
                'message' => 'File saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function popup_files(Request $request){
        $id = $request->id;
        $filter = $request->filter;

        if($filter){
            try {
                $filter = unserialize($filter);
            } catch (\Exception $e) {
                $filter = null;
            }
        }

        return response()->json([
            "status" => 1,
            "data" => view('appfiles::popup_files',[
                "id" => $id,
                "filter" => $filter
            ])->render()
        ]);
    }

    public function update_folder(Request $request){
        $id = $request->id;
        $Files = new Files();
        $result = Files::where("id_secure", $id)->first();
        $folders = $Files->getFoldersAndSubfolders(0);

        return response()->json([
            "status" => 1,
            "data" => view('appfiles::update_folder',[
                "result" => $result,
                "folders" => $folders
            ])->render()
        ]);
    }

    public function save_folder(Request $request)
    {
        $parent_folder = Files::where([
            'is_folder' => 1,
            'id_secure' => $request->parent
        ])->first();

        $parent_id = $parent_folder ? $parent_folder->id : 0;

        $item = Files::where('id_secure', $request->id_secure)->first();

        $rules = [
            'name' => ['required', Rule::unique('files')->ignore($item?->id)]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $values = [
            'team_id' => $request->team_id,
            'name' => $request->name,
            'pid' => $parent_id,
            'is_folder' => 1,
        ];

        if ($item) {
            $item->update($values);
        } else {
            $values['id_secure'] = rand_string();
            $values['created'] = now()->timestamp;
            Files::create($values);
        }

        return response()->json(['status' => 1, 'message' => 'Folder saved successfully.']);
    }

    public function destroy(Request $request)
    {
        $result = \UploadFile::destroy($request);
        return $result;
    }

    public function settings(Request $request)
    {   
        return view('appfiles::settings');
    }
}
