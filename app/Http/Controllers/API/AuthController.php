<?php

namespace App\Http\Controllers\API;

//use App\Models\User;
use App\Models\Appusers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use App\Models\Appuser;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:191',
            'surname'=>'max:191',
           // 'email'=>'required|email|max:191|unique:users,email',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:appusers'],
            'password'=>'required|min:8',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'validation_errors'=>$validator->messages(),
           
            ]);
        } else {
           
            $user = Appusers::create([
                'name'=>$request->name,
                'surname'=>$request->surname,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            
            ]);
    
            $token = $user->createToken($user->email.'_Token')->plainTextToken;
    
            return response()->json([
                'status'=>200,
                'email'=>$user->email,
                'token'=>$token,
                'message'=>'Registration successful!',
            ]);
        }
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email'=>'required|max:191',
            'password'=>'required|min:8',
        ]);  

        if($validator->fails()){
            
            return response()->json([
                'validation_errors'=>$validator->messages(),
           
            ]);

        } else {

            $user = Appusers::where('email', $request->email)->first();

            if(! $user || ! Hash::check($request->password, $user->password)){

                return response()->json([
                    'status'=>401,
                    'message'=>'Invalid credentials',
                ]); 
                
            } else {

                 if($user->role_as==1){
                    $role = 'admin';
                    $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken;

                 } else {
                    $role = 'user';
                    $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
                 }

                return response()->json([
                    'status'=>200,
                    'username'=>$user->email,
                    'token'=>$token,
                    'message'=>'Logged in successfully!',
                    'role'=>$role,
                ]);
            }
        }
    }

    public function logout(){

        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logged out successfully'
        ]);
    }
}
