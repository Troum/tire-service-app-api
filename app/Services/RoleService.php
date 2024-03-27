<?php

namespace App\Services;

use App\Http\Resources\Collections\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Interfaces\APIInterface;
use App\Models\Role;
use App\Traits\ResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleService implements APIInterface
{
    use ResponseHandler;

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return $this->success(new RoleCollection(Role::all()));
    }

    /**
     * @param mixed $model
     * @return JsonResponse
     */
    public function getOne(mixed $model): JsonResponse
    {
        return $this->success(new RoleResource($model));
    }

    public function store(Request $request)
    {
        //
    }
}
