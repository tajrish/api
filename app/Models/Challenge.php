<?php

namespace Tajrish\Models;

class Challenge extends BaseModel
{
    protected $table = 'challenges';

    protected $guarded = ['id'];

    protected $visible = [
        'id', 'title', 'description', 'pin_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'pin_id' => 'integer'
    ];
}