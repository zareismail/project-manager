<?php

namespace Zareismail\ProjectManager\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Nova\Actions\Actionable;
use Zareismail\ProjectManager\Helper;

class InventoryMaterial extends Pivot
{    
	use Actionable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['unit'];

	/**
	 * Query the related KeilUnit.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function unit()
	{
		return $this->belongsTo(\Zareismail\Keil\Models\KeilUnit::class);
	}

	/**
	 * Query the related Material.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	/**
	 * Query the related Inventory.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventory()
	{
		return $this->belongsTo(Inventory::class);
	}

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
