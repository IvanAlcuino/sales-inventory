<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    { 
        //Log::info("posts index page..");
        $pageView = 'index';
        return view('posts.index',['pageView'=>$pageView]);
        // $result = Status::with('user')->latest()->paginate(5);
        // return StatusResource::collection($result);
        //$products = Product::orderBy('created_at','desc')->paginate(5);
    }

    public function records()
    {
        //Log::info("posts records..");
        $result = Post::with('user')->latest()->paginate(5);
        //Log::info("posts:".print_r($result,true));
        return PostResource::collection($result);
        //$products = Product::orderBy('created_at','desc')->paginate(5);
    }

    public function record($id)
    {

        //Log::info("post id:".$id);
        $result = Post::with('user')->where('id', $id)->get();
        //$result = Post::all();
        //Log::info("post record:".print_r($result,true));
        return PostResource::collection($result);
        //$products = Product::orderBy('created_at','desc')->paginate(5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageView = 'create';

        return view('posts.create',['pageView'=>$pageView]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //middleware security


        //validation
        // $this->validate($request, ['body' => 'required']);

        // //create data
        // $status = User::find(1)
        //     ->statuses()
        //     ->create($request->only(['body']));


        // //return result
        // return $status->load('user');
        $user_id = Auth::id();
        
        $this->validate($request, ['body' => 'required', 'title' => 'required']);

        $post = $request->isMethod('put') ? Post::findOrFail($request->id) : new Post;

        

        $post->id = $request->input('id');
        $post->title = $request->input('title');
        $post->body = $request->input('body'); 
        $post->user_id = $user_id;
        $post->photoUrl = 'post_default.jpg'; 

        Log::info("post:".print_r($post,true));

        if($post->save()){
            //$post->load('user');
            return new PostResource($post);
        }

    }

    public function show($id)
    { 
        $pageView = 'show';

        return view('posts.show',['pageView'=>$pageView]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $pageView = 'edit';

        return view('posts.show',['pageView'=>$pageView]);
    }

  
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if($post->delete()){
            return new PostResource($post);
        }
    }
}
