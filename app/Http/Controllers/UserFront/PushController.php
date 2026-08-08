<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class PushController extends Controller
{

    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $tenant = getUser();
        $guest = Guest::firstOrCreate([
            'endpoint' => $endpoint,
            'user_id' => $tenant->id
        ]);
        $guest->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }
}
