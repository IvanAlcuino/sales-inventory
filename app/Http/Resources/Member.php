<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource; 

class Member extends JsonResource
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
            'name' => $this->name,  
            'email' => $this->email,  
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'up_user_id' => $this->up_user_id,
            'image_url' => $this->image_url,
            'mobile_no' => $this->mobile_no,  
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'total_sales' => mt_rand(1000, 9000),
            'monthly_sales' => mt_rand(500, 4000),
        ];
    }

    public function with($request){
        return [
            'version' => '1.0.0',
            'author_url' => url('...')
        ];
    }
}
