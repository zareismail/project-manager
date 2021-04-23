<?php

namespace Zareismail\ProjectManager\Models;

use Zareismail\ProjectManager\Helper;

class Project extends AuthorizableModel
{  
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    	'start_date' => 'date',
    	'finish_date' => 'date',
    	'renewal_date' => 'date',
    ];

	/**
	 * Query the related Material.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function materials()
	{
		return $this->belongsToMany(Material::class, Helper::prefixTable('material_project'));
	}

	/**
	 * Query the related User.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function manager()
	{
		return $this->authenticatable('manager_id');
	}

	/**
	 * Query the related Employer.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function employer()
	{
		return $this->belongsTo(Employer::class);
	}

	/**
	 * Query the related Inventory.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOneOrMany
	 */
	public function inventory()
	{
		return $this->hasMany(Inventory::class, 'project_id');
	}
}
