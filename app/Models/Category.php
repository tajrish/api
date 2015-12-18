<?php

namespace Tajrish\Models;

class Category extends BaseModel
{
    protected $table = 'categories';

    public $timestamps = false;

    protected $guarded = ['id'];
}