<?php

namespace App\Http\Controllers;
//use http\Env\Response;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Resource;

class AuthController extends Controller
{
    public function register(Request $request){


        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try{
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->is_admin = 1;
            $user->save();
            return response()->json(['user' => $user, 'message' => 'User Created'], 201);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }


    }
    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (! $token = Auth::attempt($credentials)){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        //        else{
        //            return response()->json(['message' => 'Login Successfully!!'], 201);
        //        }
        //        if (Auth::check($cren)){
        //            return response()->json(['message' => 'User Login Successfully!']);
        //        }else{
        //            return response()->json(['message' => 'login Failed!!']);
        //        }
        $chk_type = User::where('email',$request->email)->first();
        $role = $chk_type->is_admin;
        $resource = Resource::where('email',$request->email)->exists();
        $res_data = Resource::where('email',$request->email)->first();
        $admin_name = $chk_type->name;
        $admin_email = $chk_type->email;


        // $email = $res_data->email;
        // $name = $res_data->name;
        // return response()->json(compact('role','token','email', 'name'),201);



        $email = null;
        $name = null;
        if ($role === 0 || $resource) {        
            $email = $res_data->id;
            $orig_email = $res_data->email;
            $name = $res_data->firstname;
            // dd($res_email);
            return response()->json(compact('role','token','email', 'name', 'orig_email'),201);
        
        }elseif($role === 1){

            return response()->json(compact('role','token','admin_name', 'admin_email'),201);
        }


        // if ($res === 0) {
        //     $chk_type = User::where('email',$user_type->email)->first();
        //     $res = $chk_type->is_admin;
        //     return response()->json(compact('res','token'),201);
        // }else{
        //     return response()->json(compact('token'),201);
        // }
        // return $this->respondWithToken($token);
        // dd($chk_type->is_admin);
        // $token = JWTAuth::fromUser($user);
        // return response()->json(compact('user','token'),201);
        // return response()->json(compact('user','token'),201);

    }

    public function reset(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        try{
            $plainPassword = $request->input('password');
            //update password (reset password)
            $res = Resource::find($id);
            $user = User::where('email', $res->email)->get();
            $user2 = User::find($user[0]->id);
            $user2->password = app('hash')->make($plainPassword);
            $user2->save();

            return response()->json($user2, 201);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Updation failed '.$e], 409);
        }

    }

}
