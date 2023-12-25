<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $this->validateData($request);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'token' => $request->user()->createToken($request->device)->plainTextToken,
            'message' => 'Success'
        ]);
    }

    public function validateData(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required'
        ]);
    }

    public function getUsers()
    {
        // get user
        $users = DB::table('users')->get();
        return response()->json([
            'data' => $users
        ]);
    }

    public function register(Request $request)
    {
        $this->validateData($request);
        $email = $request->email;
        $password = $request->password;
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
