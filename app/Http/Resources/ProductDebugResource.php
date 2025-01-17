<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDebugResource extends JsonResource
{
    // public $additional = [
    //     'author' => 'pzn'
    // ];

    public static $wrap = 'data';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
            ],
            'author' => 'pzn',
            'server_time' => now()->toDateString()
        ];
    }
}
