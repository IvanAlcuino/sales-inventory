<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;  

class Inventory extends JsonResource
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
            'product_id' => $this->product_id,  
            'product_name' => $this->product_name,  
            'entry_type' => $this->entry_type,  
            'entry_name' => $this->entry_name,
            'description' => $this->description, 
            'current_stock' => $this->current_stock, 
            'qty' => $this->qty, 
            'user_id' => $this->user_id, 
             
        ];
        
    }

     
}
