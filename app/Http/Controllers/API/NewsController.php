<?php

namespace App\Http\Controllers\API;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function index(){

        $news = News::all();
        return response()->json([
            'status'=>200,
            'news'=>$news,
        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'title'=>'required|max:20',
            'desc'=>'required|max:191',
            'image'=>'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),

            ]);

        } else {

            $news = new News;
            $news->title = $request->input('title');
            $news->desc = $request->input('desc');

            if($request->hasFile('image')){

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('uploads/news/', $filename);
                $news->image = 'uploads/news/'.$filename;
            }

           // $news->popular = $request->input('popular') == true ? '1' : '0';
           // $news->status = $request->input('status') == true ? '1' : '0';
            $news->save();

            return response()->json([

                'status'=>200,
                'message'=>'news added succesfully',
            ]); 
        }
    }

    public function edit($id){

        $news = News::find($id);

        if($news){

            return response()->json([

                'status'=>200,
                'news'=>$news,

            ]);

        } else {

            return response()->json([

                'status'=>404,
                'message'=>'No news found',

            ]);
        }
    }

    public function update(Request $request, $id){


        $validator = Validator::make($request->all(),[
            'title'=>'required|max:191',
            'desc'=>'required|max:20',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),

            ]);

        } else {

            $news = news::find($id);
            if($news){

            $news->title = $request->input('title');
            $news->desc = $request->input('desc');

            if($request->hasFile('image')){

                $path = $news->image; 
                if(File::exists($path)){

                    File::delete($path);
                }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('uploads/news/', $filename);
                $news->image = 'uploads/news/'.$filename;
            }

           // $news->popular = $request->input('popular');
           // $news->status = $request->input('status');
            $news->update();

            return response()->json([

                'status'=>200,
                'message'=>'news updated succesfully',
            ]); 
            
        }  else{
                 
            return response()->json([

                'status'=>404,
                'message'=>'news not found!',
            ]); 
           }
        }
    }

    public function destroy($id){

        $news = News::find($id);
        if($news){

            $news->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Article deleted successfully',
           
            ]);
        }
       

       else {

        return response()->json([
            'status'=>404,
            'message'=>'No article ID found!',
        ]);
    } 
}
}
