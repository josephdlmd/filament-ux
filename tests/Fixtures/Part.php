<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];
}
