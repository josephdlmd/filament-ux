<?php

namespace Josephdlmd\FilamentUx\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gadget extends Model
{
    protected $guarded = [];

    /**
     * @return HasMany<Part, $this>
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }
}
