<?php

namespace Zareismail\ProjectManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Zareismail\NovaContracts\Models\AuthorizableModel as Model; 
use Zareismail\ProjectManager\Helper;

class AuthorizableModel extends Model
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
