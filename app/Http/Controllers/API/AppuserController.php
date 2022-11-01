<?php

namespace App\Http\Controllers\API;

//use App\Models\Appuser;
use App\Models\Appusers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppuserController extends Controller
{
    public function index(){

        $appuser = Appusers::all();
        return response()->json([
            'status'=>200,
            'appuser'=>$appuser,
        ]);
    }

    public function store(Request $req){
        $appuser = new Appusers;
        $appuser->name = $req->input('name');
        $appuser->surname = $req->input('surname');
        $appuser->email = $req->input('email');
        $appuser->password = $req->input('password');
        $appuser->confirm_password = $req->input('confirm_password');
        $appuser->save();

        return response() ->json([
            'status'=>200,
            'message'=>"Account successfully created"
        ]);
    }

    public function edit($id){

        $appuser = Appusers::find($id);

        if($appuser){

            return response()->json([

                'status'=>200,
                'appuser'=>$appuser,

            ]);

        } else {

            return response()->json([

                'status'=>404,
                'message'=>'No user found',

            ]);
        }
    }

    public function update(Request $request, $id){


        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'surname'=>'required|max:20',
            'email'=>'required|max:191',
            'password'=>'required|max:191',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),

            ]);

        } else {

            $appuser = Appusers::find($id);
            if($appuser){

            $appuser->name = $request->input('name');
            $appuser->surname = $request->input('surname');
            $appuser->email = $request->input('email');
            $appuser->password = $request->input('password');

           // $news->popular = $request->input('popular');
           // $news->status = $request->input('status');
            $appuser->update();

            return response()->json([

                'status'=>200,
                'message'=>'user updated succesfully',
            ]); 
            
        }  else{
                 
            return response()->json([

                'status'=>404,
                'message'=>'user not found!',
            ]); 
           }
        }
    }

    public function destroy($id){

        $appuser = Appusers::find($id);
        if($appuser){

            $appuser->delete();
            return response()->json([
                'status'=>200,
                'message'=>'video deleted successfully',
           
            ]);
        }
       

       else {

        return response()->json([
            'status'=>404,
            'message'=>'No user ID found!',
        ]);
    } 
}
}
