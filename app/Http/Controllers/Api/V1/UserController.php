<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Http\Services\AuthService;
use App\Http\Requests\StorePostRequest;

class UserController extends Controller
{

    protected $authServices;
    public function __construct(AuthService $authService)
    {
        $this->middleware('guest')->except('logout');
        $this->authService = $authService;
    }

    public function login(StorePostRequest $request)
    {
        $credentials = $request->only('email', 'password', 'device');
        if($this->authService->login($credentials)){
            $token = $request->user()->createToken($request->device)->plainTextToken;
            $data = [
                'status' => 200,
                'message' => 'logued',
                'token' => $token
            ];
        } else {
            $data = [
                'status' => 400,
                'error' => 'bad credenctials'
            ];
        }
        return response()->json($data);
    }

    public function register(Request $request)
    {
        $this->storePostRequest->rules($request);
        $email = $request->email;
        $password = $request->password;
        $device = $request->device;
        if(isset($email) && isset($password)){
            $user = DB::table('users')->where('email', $email)->exists();
            if($user){
                $data = array(
                    'message' => 'Error',
                    'status' => 409
                );
            } else {
                $newUser = new User();
                $newUser->name = $email;
                $newUser->email = $email;
                $newUser->device = $device;
                $newUser->password = $password;
                if($newUser->save()){
                    $data = array(
                        'msg' => 'success',
                        'status' => 200
                    );
                }
            }
        } else {
            $data = array(
                'msg' => 'Error',
                'status' => 400
            );
        }

        return response()->json($data);
    }

    public function getUsers()
    {
        // get user
        $users = DB::table('users')->get();
        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
