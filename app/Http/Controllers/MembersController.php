<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;   
use App\Http\Resources\Member as MemberResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
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
        //Log::info("Members index page..");
        return view('members.index');
    }

    public function membershipTree()
    {
        //Log::info("Members index page..");
        return view('members.membershipTree');
    }

    public function records()
    {
        //Log::info("posts records..");
        $result = User::latest()->paginate(20);
        //Log::info("User:".print_r($result,true));
        return MemberResource::collection($result);
        //$products = Product::orderBy('created_at','desc')->paginate(5);
    }

    public function record($id)
    { 
        //Log::info("record id:".$id);
        $result = User::where('id', $id)->get(); 
        //Log::info("record:".print_r($result,true));
        return MemberResource::collection($result); 
    }

    public function getDownlineMembers($id){
        //Log::info("getDownlineMembers(".$id.")");
        $sql = 'SELECT m.*
                FROM (SELECT id FROM users WHERE id=?) as u JOIN users AS m ON m.up_user_id=u.id';
        $users = collect(DB::select($sql, [$id]));
        // Log::info("users:".print_r($users,true)); 
        // Log::info("users count:".$users->count()); 

        return MemberResource::collection($users); 
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("inside Member store():");
        //$user_id = Auth::id();
        
        $this->validate($request, ['first_name' => 'required', 'last_name' => 'required', 'email' => 'required']);
        //Log::info("here 2:");
        $member = $request->isMethod('put') ? User::findOrFail($request->id) : new User; 

        //Log::info("here 3:");
        $member->id = $request->input('id');
        $member->name = $request->input('first_name').' '.$request->input('last_name');
        $member->first_name = $request->input('first_name');  
        $member->last_name = $request->input('last_name');  
        $member->email = $request->input('email');  
        $member->mobile_no = $request->input('mobile_no');  
        $member->address = $request->input('address');  
        $member->city = $request->input('city');  
        $member->province = $request->input('province');  
        $member->up_user_id = $request->input('up_user_id');
        $member->image_url = $request->input('image_url');  

        //$member->password = Hash::make($request->input('secret')); //default pw.
        if($request->isMethod('post')){ //means add new user with new pw
            $member->password = Hash::make($request->input('password'));
        }else if($request->isMethod('put') && $request->input('new_password')){
            $member->password = Hash::make($request->input('new_password'));
        }else{
            //is put or update data that the pw is still the same.
            //$member->password = $member->password;  
        }

        Log::info("member:".print_r($member,true));

        if($member->save()){ 
            return new MemberResource($member);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
