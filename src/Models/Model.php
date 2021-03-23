<?php

namespace Zareismail\ProjectManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model as LaravelModel, SoftDeletes};
use Zareismail\ProjectManager\Helper;

class Model extends LaravelModel
{
    use HasFactory, SoftDeletes;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
    	return Helper::prefixTable(parent::getTable());
    }
}
