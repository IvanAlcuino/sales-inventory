<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\User; 
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
     
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    { 
        // Log::info("product index page..");
        return view('products.index');
    }

    public function records()
    {
        //Log::info("posts records..");
        $result = Product::latest()->paginate(10);
        //Log::info("Product:".print_r($result,true));
        //Log::info("Product current stock:".$result[0]->current_stock);
        return ProductResource::collection($result); 
    }

    public function record($id)
    { 
        //Log::info("post id:".$id);
        $result = Product::where('id', $id)->get(); 
        //Log::info("post record:".print_r($result,true));
        return ProductResource::collection($result); 
    }

 
    public function create()
    {  
        return view('product.create');
    }

   
    public function store(Request $request)
    { 
        $user_id = Auth::id();
        
        $this->validate($request, ['name' => 'required', 'price' => 'required']);

        $is_new = $request->isMethod('put') ? false : true; //put = update, post = add new.
        $product =  $is_new ? new Product : Product::findOrFail($request->id);

        if($is_new){
            $product->id = Product::getNextID();
            Log::info("product_id:". $product->id);
        }
 
        $product->name = $request->input('name');
        $product->price = $request->input('price');  
        $product->image_url = (($product->image_url && $request->input('image') == null ) ? $product->image_url : 'NULL'); 

        //$product->image_url = ($request->input('image_url') ? $request->input('image_url') : 'NULL'); 

        if($request->get('image'))
        {
            $save_path = "storage/images/products/".$product->id."/";

            if (!file_exists($save_path)) { 
                
                mkdir($save_path, 666, true);
            }elseif(file_exists($save_path)){  
                //if folder storage exist remove first all containing images before storing new image to avoid redundant images that will not used anymore..
                $files = glob($save_path . '*', GLOB_MARK);
                //Log::info(print_r($files,true));

                foreach ($files as $file) {
                    if (is_dir($file)) {
                        self::deleteDir($file);
                    } else {
                        unlink($file);
                    }
                }
                 
            }

           $image = $request->get('image');
           $ext = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           $_name = time();
           $name = $_name.'.' . $ext;
           //$name = $product->id.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
           $img = \Image::make($request->get('image'))->save(public_path($save_path).$name);

           $product->image_url = "/".$save_path.$name;
 
           $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            });
           $img->save(public_path($save_path).$_name."_s.".$ext);

         }
  

        //Log::info("post:".print_r($product,true));

        if($product->save()){
            //$post->load('user');
            return new ProductResource($product);
        }

    }

    public function show($id)
    {  
        return view('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        return view('products.index');
    }

  
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if($post->delete()){
            return new ProductResource($post);
        }
    }
}