<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Transaction;
use App\TransactionDetail;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Resources\Transaction as TransactionResource;
use App\Http\Resources\TransactionDetail as TransactionDetailResource;


class TransactionsController extends Controller
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
        return view('transactions.index');
    }

    public function records()
    {
        //Log::info("Transactions records..");
        $result = Transaction::latest()->paginate(20);
        //Log::info("Transaction:".print_r($result,true));
        return TransactionResource::collection($result);
        //$products = Product::orderBy('created_at','desc')->paginate(5);
    }

    public function record($id)
    { 
        //Log::info("Trans id:".$id);
        $result = Transaction::where('id', $id)->get(); 
        //Log::info("Trans record:".print_r($result,true));
        return TransactionResource::collection($result); 
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
        //Log::info("Transaction store()..");
        $user_id = Auth::id();
        
        $this->validate($request, ['member_id' => 'required', 'member_name' => 'required', 'total_amount' => 'required', 'products' => 'required']);
      
        $trans = new Transaction;
        $transDetails = new TransactionDetail;

          
        $trans->member_id = $request->input('member_id');
        $trans->member_name = $request->input('member_name');  
        $trans->amount = $request->input('total_amount');  
        $trans->cashier_id = $user_id;

        try{
            DB::beginTransaction();
            //Log::info("Here1");
            if($trans->save()){
                $products = $request->input('products'); 
                //Log::info("Here2");
                foreach($products as $key => $product){ 
                    $transDetails = new TransactionDetail;
                    $transDetails->transaction_id = $trans->id;
                    $transDetails->product_id = $product['id'];
                    $transDetails->product_name = $product['name'];
                    $transDetails->price = $product['price'];
                    $transDetails->qty = $product['qty'];
                    $transDetails->amount = $product['amount'];
    
                    //Log::info("Here3");
                    $transDetails->save(); 

                    //Log::info("Here3-2");
                    //Update inventory
                    $prev_inventory = Inventory::where("product_id",$product['id'])->orderBy("id","desc")->first();
                    $inventory = new Inventory;   
                    $inventory->product_id = $product['id'];
                    $inventory->product_name = $product['name']; 
                    $inventory->added_qty = (int)("-".$product['qty']);
                    $inventory->current_stock = (int)$prev_inventory->current_stock + (int)$inventory->added_qty;
                    $inventory->entry_type = "out";
                    $inventory->entry_name = "product sold";
                    $inventory->description = "product sold";   
                    $inventory->user_id = $user_id;  

                    //Log::info("Here4");
                    $inventory->save();
                } 
                
            }    
 

            DB::commit();
            //Log::info("Here5");
        }catch(\Exception $e){
            //Log::info("Here error");
            DB::rollback();
            return response()->json(['data'=>'error while saving']);
        }  
        
        //Log::info("Here success");
        return response()->json(['data'=>'success']);
         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\odel  $odel
     * @return \Illuminate\Http\Response
     */
    public function show(odel $odel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\odel  $odel
     * @return \Illuminate\Http\Response
     */
    public function edit(odel $odel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\odel  $odel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, odel $odel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\odel  $odel
     * @return \Illuminate\Http\Response
     */
    public function destroy(odel $odel)
    {
        //
    }
}
