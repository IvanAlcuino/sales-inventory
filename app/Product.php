<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Product extends Model
{
    protected $guarded = []; 

    public static function getNextID(){
        $statement = DB::select("SHOW TABLE STATUS LIKE 'products'");
        return $statement[0]->Auto_increment;
    }

    public function getCurrentStockAttribute(){
        return Inventory::where("product_id",$this->id)->orderBy("id","desc")->first(['current_stock'])->current_stock;
    }
     
}
