<?php

namespace App\Http\Resources;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Info
 */
class InfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image_url,
            'qr_code_images' => $this->when($request->is('infos/*'), function () {
                return $this->codes;
            }, $this->original),
            'price' => $this->price,
            'amount' => $this->amount,
            'type' => $this->whenLoaded('type', function () {
                return new TypeResource($this->type);
            })
        ];
    }
}
