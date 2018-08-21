<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];
    
    public function member(){
        return $this->belongsTo('App\User', 'member_id')->select(['id', 'name']);
        
    }

    public function transactionDetails(){
        return $this->hasMany('App\TransactionDetail', 'transaction_id');
    }
    

}
