<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginLog;

class LogsLoginController extends Controller
{
    //
    public function logsLogin(){
        
        $logs = LoginLog::all();
        $user = auth()->user();
            LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => request()->path(),
            ]);


        return response()->json([
            'status' => 'success',
            'logs' => $logs,
        ], 200
        );
       
    }
    public function logsLoginUser(){
        $request = request()->all();
        $email = $request['email'];
        $logs = LoginLog::where('email', $email)->get();
        $user = auth()->user();
            LoginLog::create([
                'name' => $user->name,
                'email' => $user->email,
                'url' => request()->path(),
            ]);

        return response()->json([
            'status' => 'success',
            'logs' => $logs,
        ], 200
        );
       
    }
}
