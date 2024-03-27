<?php

namespace App\Http\Resources;

use App\Http\Resources\Collections\TypeCollection;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Size
 */
class SizeResource extends JsonResource
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
            'size' => $this->size,
            'types' => $this->whenLoaded('types', function () {
                return new TypeCollection($this->types);
            })
        ];
    }
}
