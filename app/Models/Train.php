<?php

namespace Tajrish\Models;

use Bigsinoos\JEloquent\PersianDateTrait;

class Train extends BaseModel
{
    use PersianDateTrait;

    protected $table = 'trains';

    protected $guarded = ['id'];

    protected $visible = [
        'jalali_starts_at',
        'jalali_ends_at',
        'title',
        'provider',
        'start_city',
        'end_city',
        'price'
    ];

    protected $dates = ['created_at', 'updated_at', 'starts_at', 'ends_at'];
}