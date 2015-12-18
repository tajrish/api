<?php

namespace Tajrish\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
        'deleted_at' => 'string',
    ];

    public function getColumns()
    {
        return array_diff($this->columns, $this->columnsExcludes);
    }

}