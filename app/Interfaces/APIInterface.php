<?php

namespace App\Interfaces;

use App\Models\Info;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface APIInterface
{
    public function getAll();

    public function getOne(mixed $model);

    public function store(Request $request);
}
