<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource; 
use App\User;
use App\Http\Resources\Member as MemberResource;
  

class TransactionDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,  
            'product_id' => $this->product_id,  
            'product_name' => $this->product_name,
            'price' => $this->price, 
            'qty' => $this->qty, 
            'amount' => $this->amount, 
            
        ];
    }

     
}
