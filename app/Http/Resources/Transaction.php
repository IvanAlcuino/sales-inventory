<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource; 
use App\User;
use App\Http\Resources\User as UserResource;
use App\TransactionDetail;
use App\Http\Resources\TransactionDetail as TransactionDetailResource;

class Transaction extends JsonResource
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
            'member_id' => $this->member_id,  
            'member_name' => $this->member_name,  
            'amount' => $this->amount,
            'cashier_id' => $this->cashier_id, 
            'member' => new UserResource(User::find($this->member_id)) 
        ];
    }

     
}
