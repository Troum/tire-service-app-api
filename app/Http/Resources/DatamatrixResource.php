<?php

namespace App\Http\Resources;

use App\Models\Datamatrix;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Datamatrix
 */
class DatamatrixResource extends JsonResource
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
            'tireName' => $this->tireName,
            'codes' => $this->codes,
            'url' => url($this->url),
        ];
    }
}
