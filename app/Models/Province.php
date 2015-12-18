<?php

namespace Tajrish\Models;

class Province extends BaseModel
{
    protected $table = 'provinces';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $hidden = ['order'];
}