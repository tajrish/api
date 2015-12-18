<?php

namespace Tajrish\Models;

class Pin extends BaseModel
{
    protected $table = 'pins';

    protected $guarded = ['id'];

    public function comments()
    {
        return $this->hasMany(VisitPin::class, 'pin_id')->whereNotNull('comment');
    }
}