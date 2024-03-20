<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index(){
        try{
        return Recipe::filter(request(['category']))
        ->orderBy('id','desc')
        ->paginate(6);
        
        }catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
     }

     public function store(Request $request){
      
        // dd($test);
        try{

            // $validator = Validator::make(request()->all(),[
            //     'title' => 'required',
            //     'description' => 'required',
            //     'photo' => 'required',
            //     'category_id' => ['required', Rule::exists('categories','id')],
            // ]);
           
            // if($validator->fails()){
            //    $flatteredErrors = collect($validator->errors())->flatMap(function ($e,$field){
            //     return [$field => $e[0]];
            //    });

            //    return response()->json([
            //     'errors'=>$flatteredErrors,
            //     'status'=>400
            //    ],400);
            // }
            // $newImageName =  time() . '-'. $request->title .'.' .$request->photo->extension();
            // $request->photo->move(public_path('images'),$newImageName);
            // // dd($newImageName);

            // $recipe = Recipe::create([
            //     'title' =>$request->title,
            //     'description' =>$request->description,
            //     'photo' =>$newImageName,
            //     'category_id' =>$request->category_id,

            // ]);



            $recipe = Recipe::create([
                'title' =>request('title'),
                'description' =>request('description'),
                'photo' => request('photo'),
                'price' => request('price'),
                'category_id' =>request('category_id'),

            ]);

            return response()->json($recipe,201);
        
        }catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
     }

     public function show($id){
        try{

        $recipe = Recipe::find($id);
        if(!$recipe){
            return response()->json([[
                'message'=> 'recipe not found',
                'status'=> 404
            ]],404);
        }
        return $recipe;
        
        }catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
     }

     public function delete($id){
        try{

        $recipe = Recipe::find($id);
        if(!$recipe){
            return response()->json([[
                'message'=> 'recipe not found',
                'status'=> 404
            ]],404);
        }
        $recipe->delete();
        return $recipe;
        
        }catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
     }


     public function update(Request $request,$id){
        try{
            $recipe = Recipe::find($id);

            if(!$recipe){
                return response()->json([[
                    'message'=> 'recipe not found',
                    'status'=> 404
                ]],404);
            }
           
            $validator = Validator::make($request->all(),[
                'title' => 'required',
                'description' => 'required',
                'category_id' => ['required', Rule::exists('categories','id')],
                'photo' => 'required',
            ]);
            if($validator->fails()){
               $flatteredErrors = collect($validator->errors())->flatMap(function ($e,$field){
                return [$field => $e[0]];
               });

               return response()->json([
                'errors'=>$flatteredErrors,
                'status'=>400
               ],400);
            }
        
            
            
            $recipe->update([
                'title' => $request->title,
                'description' => $request->description,
                'photo' => $request->photo,
                'category_id' => $request->category_id,
            ]);

               
           
            return response()->json($recipe,200);
        
        }catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
     }

     public function upload(){
        try{
            $validator = Validator::make(request()->all(),[
               
                'photo' => ['required','image'],

            ]);

            if($validator->fails()){
               $flatteredErrors = collect($validator->errors())->flatMap(function ($e,$field){
                return [$field => $e[0]];
               });

               return response()->json([
                'errors'=>$flatteredErrors,
                'status'=>400
               ],400);
            }

            // dd(request('photo'));
            $newImageName =  time() . '-'.request('photo')->extension();
            request('photo')->move(public_path('images'),$newImageName);
            // dd($newImageName);
        

            // $path =  '/storage/' .request('photo')->store('/recipes');
           return [
            'path' => '/images/' . $newImageName,
            'staus'=> 201
           ];


        
        }
        catch(Exception $e){
            return response()->json([[
                'message'=> $e->getMessage(),
                'status'=> 500
            ]],500);
        }
         }


    public function bestSeller(){
        return Recipe::bestSeller();
    }
}
