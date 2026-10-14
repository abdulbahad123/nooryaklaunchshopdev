<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Blade;
use Hexadog\ThemesManager\Facades\ThemesManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Modules\AdminUsers\Models\Teams;
use App\Translation\CustomFileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Core;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
        $cachePath = storage_path('framework/cache');
        if (!File::exists($cachePath)) {
            File::makeDirectory($cachePath, 0777, true);
        }

        $cachePath = storage_path('framework/views');
        if (!File::exists($cachePath)) {
            File::makeDirectory($cachePath, 0777, true);
        }

        $this->app->singleton('translation.loader', function ($app) {
            $path = $app['path.lang'];
            return new CustomFileLoader(new Filesystem, $path);
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];
            $translator = new Translator($loader, $locale);

            $translator->setFallback($app['config']['app.fallback_locale']);

            return $translator;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appUrl = config('app.url');
        if (str_starts_with($appUrl, 'https://') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \URL::forceScheme('https');
        }

        // Dynamic Database Resolution for Agencies (e.g. whatsappbulk.youverse.in -> nooryak_ps_youverse_whatsapp)
        $host = strtolower(request()->getHost() ?? '');
        $host = preg_replace('/:\d+$/', '', $host);
        if (str_starts_with($host, 'whatsappbulk.') || str_starts_with($host, 'whatsapp.')) {
            $parts = explode('.', $host);
            if (count($parts) >= 3) {
                $rootDomain = preg_replace('/^(whatsappbulk|whatsapp)\./i', '', $host);
                $agencySlug = str_replace('-', '_', strtolower($parts[1]));

                // Only switch DB for agency subdomains, not the main platform domain (saasreselling.com)
                if (!in_array($rootDomain, ['saasreselling.com', 'cockroachjantaparty.top', 'localhost', '127.0.0.1'])) {
                    $cpanelUser = env('CPANEL_USER', 'nooryak');
                    $tenantDb = "{$cpanelUser}_ps_{$agencySlug}_whatsapp";
                    try {
                        config(['database.connections.mysql.database' => $tenantDb]);
                        DB::purge('mysql');
                    } catch (\Throwable $e) {}
                }
            }
        }

        Schema::defaultStringLength(191);
        $this->registerAuth();
        $this->registerModule();
        $this->registerHelper();
        $this->registerDB();
        $this->registerBlade();
        $this->registerFacades();
        Core::updateModuleStatusesFile();
    }

    public function registerModule(): void
    {
        $modules = Module::all();

        foreach ($modules as $key => $module)
        {
            if($menu_item = $module->get("menu"))
            {
                if(isset($menu_item['uri']))
                {
                    $contains = Str::contains(url()->current(), [ $menu_item['uri'] ]);
                    if($contains)
                    {
                        if(request()->module == "" || strlen(request()->module['uri']) < strlen($menu_item['uri']) ){
                            $menu_item['permission'] = $module->get('permission');
                            $menu_item['module_name'] = $module->getName();
                            $menu_item['key'] = $module->getLowerName();
                            $menu_item['module_path'] = $module->getPath();
                            $menu_item['module_desc'] = $module->getDescription();
                            request()->merge(['module' => $menu_item]);
                        }
                    }
                }

            }
        }
    }

    public function registerAuth(){
        view()->composer('*', function($view){
            if(Auth::check()){
                $user_id = Auth::id();
                $user = Auth::user();
                $team = Teams::where("owner", $user_id)->first();

                if (!$team) {
                    Auth::guard('web')->logout();
                    Session::invalidate();
                    Session::regenerateToken();
                    header("Location: ". url(""));
                }

                request()->merge(['team' => $team ]);
                request()->merge(['team_id' => $team->id]);

                $view->with("user", $user);
                $view->with("team", $team);
            }
        });
    }

    public function registerFacades(): void{
        $folderPath =  base_path('/app/Facades');

        $files = glob($folderPath . '/*.php');

        foreach ($files as $file) {
            // Extract the class name from the filename
            $className = basename($file, '.php');

            // Check if the class exists and create alias
            if (!class_exists($className)) {
                class_alias("\\App\\Facades\\{$className}", $className);
            }
        }
    }

    public function registerDB(): void
    {
        if (Schema::hasTable('options')) {
            $options = DB::table('options')->get();
            config(['options' => $options]);
        }
    }

    public function registerHelper(): void
    {
        $modules = Module::all();
        foreach ($modules as $key => $module) {
            $helper_dir = $module->getExtraPath('helpers');
            if( is_dir( $helper_dir ) )
            {
                $helper_files = glob($helper_dir."/*.php");
                if( !empty($helper_files) ){
                    foreach ($helper_files as $helper_file) {
                        include_once $helper_file;
                    }
                }
            }
        }
    }

    public function registerBlade(): void
    {
        Blade::extend(function($value) {
            return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
        });

        Blade::if('cans', function (...$abilities) {
            $user = auth()->user();
            foreach ($abilities as $ability) {
                if (!$user || !$user->can($ability)) return false;
            }
            return true;
        });

        Blade::if('canany', function (...$abilities) {
            $user = auth()->user();
            foreach ($abilities as $ability) {
                if ($user && $user->can($ability)) {
                    return true;
                }
            }
            return false;
        });

        Blade::directive('prefix', function ($expression) {
            return "<?php echo \\App\\Facades\\Script::applyPrefix($expression); ?>";
        });

        Blade::directive('tw', function ($expression) {
            return "<?php echo \\App\\Facades\\Script::applyPrefix($expression); ?>";
        });
    }
}


