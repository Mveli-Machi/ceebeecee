<?php

namespace App\Http\Controllers\API;

use App\Models\Sermons;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SermonsController extends Controller
{
    public function index(){

        $sermons = Sermons::all();
        return response()->json([
            'status'=>200,
            'sermons'=>$sermons,
        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'title'=>'required|max:20',
            'desc'=>'required|max:191',
            'video'=>'required|max:191',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),

            ]);

        } else {

            $sermons = new Sermons;
            $sermons->title = $request->input('title');
            $sermons->desc = $request->input('desc');
            $sermons->video = $request->input('video');

           // $news->popular = $request->input('popular') == true ? '1' : '0';
           // $news->status = $request->input('status') == true ? '1' : '0';
            $sermons->save();

            return response()->json([

                'status'=>200,
                'message'=>'video added succesfully',
            ]); 
        }
    }

    public function edit($id){

        $sermon = Sermons::find($id);

        if($sermon){

            return response()->json([

                'status'=>200,
                'sermon'=>$sermon,

            ]);

        } else {

            return response()->json([

                'status'=>404,
                'message'=>'No video found',

            ]);
        }
    }

    public function update(Request $request, $id){


        $validator = Validator::make($request->all(),[
            'title'=>'required|max:191',
            'desc'=>'required|max:20',
            'video'=>'required|max:191',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),

            ]);

        } else {

            $sermon = Sermons::find($id);
            if($sermon){

            $sermon->title = $request->input('title');
            $sermon->desc = $request->input('desc');
            $sermon->video = $request->input('video');

           // $news->popular = $request->input('popular');
           // $news->status = $request->input('status');
            $sermon->update();

            return response()->json([

                'status'=>200,
                'message'=>'video updated succesfully',
            ]); 
            
        }  else{
                 
            return response()->json([

                'status'=>404,
                'message'=>'video not found!',
            ]); 
           }
        }
    }

    public function destroy($id){

        $sermon = Sermons::find($id);
        if($sermon){

            $sermon->delete();
            return response()->json([
                'status'=>200,
                'message'=>'video deleted successfully',
           
            ]);
        }
       

       else {

        return response()->json([
            'status'=>404,
            'message'=>'No video ID found!',
        ]);
    } 
}
}
