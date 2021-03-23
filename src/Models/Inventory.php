<?php

namespace Zareismail\ProjectManager\Models;

use Zareismail\ProjectManager\Helper;
use Zareismail\Task\Contracts\Taskable;
use Zareismail\Task\Concerns\InteractsWithTasks;

class Inventory extends AuthorizableModel implements Taskable
{   
	use InteractsWithTasks;
	
	/**
	 * Query the related Project.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function project()
	{
		return $this->belongsTo(Project::class);
	} 

	/**
	 * Query the related Project.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function materials()
	{
		return $this->belongsToMany(Material::class, Helper::prefixTable('inventory_material'))
					->using(InventoryMaterial::class)
					->withPivot('value', 'unit_id', 'price');
	}

	/**
	 * Query the related Project.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function items()
	{
		return $this->hasMany(InventoryMaterial::class, 'inventory_id');
	} 
}
