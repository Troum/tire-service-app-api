<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('update', function () {
    return true;
});
Broadcast::channel('info.{info_id}.update', function () {
    return true;
});
