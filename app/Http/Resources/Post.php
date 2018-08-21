<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Http\Resources\User as UserResource;

class Post extends JsonResource
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
            'title' => $this->title, 
            'body' => $this->body, 
            'photoUrl' => $this->photoUrl,
            'created_at' => $this->created_at->toDateTimeString(),
            'user_id' => $this->user_id,
            'user' => new UserResource(User::find($this->user_id)), 
           
        ];
    }

    public function with($request){
        return [
            'version' => '1.0.0',
            'author_url' => url('...')
        ];
    }
}
