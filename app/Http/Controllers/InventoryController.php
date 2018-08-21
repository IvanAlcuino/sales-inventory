<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Inventory;  
use App\Product;  
use App\Http\Resources\Inventory as InventoryResource; 


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //Log::info("here1");
        $this->validate($request, ['product_id' => 'required', 'product_name' => 'required', 'added_qty' => 'required']);
 
        $prev_inventory = Inventory::where("product_id",$request->product_id)->orderBy("id","desc")->first();
        $user_id = Auth::id();
        $inventory = new Inventory;  

        $inventory->product_id = $request->input('product_id');
        $inventory->product_name = $request->input('product_name'); 
        $inventory->added_qty = $request->input('added_qty');
        $inventory->current_stock = (int)$prev_inventory->current_stock + (int)$request->input('added_qty');
        $inventory->entry_type = "in";
        $inventory->entry_name = "New stock";
        $inventory->description = "New stock";   
        $inventory->user_id = $user_id; 
        //Log::info("here3");

        if($inventory->save()){
            return response()->json(['data'=>'success','current_stock'=>$inventory->current_stock]);
        }else{
            return response()->json(['data'=>'failed']);
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
