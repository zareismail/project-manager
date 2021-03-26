<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Fields\{ID, Number, Currency, BelongsTo};
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Keil\Nova\MeasuringUnit;

class InventoryMaterial extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\ProjectManager\Models\InventoryMaterial::class; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('Inventory'), 'inventory', Inventory::class) 
                ->hideWhenUpdating()
                ->showCreateRelationButton()
                ->withoutTrashed()
                // ->searchable()
                ->required()
                ->rules('required'),

            BelongsTo::make(__('Material'), 'material', Material::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                // ->searchable()
                ->required()
                ->rules('required')
                ->creationRules([function($attribute, $value, $fail) { 
                    $conditions = [
                        'inventory_id' => request('inventory'),
                        'material_id' => $value,
                    ];

                    if(static::newModel()->where($conditions)->exists()) {
                        $fail(trans('nova::validation.relatable'));
                    }
                }]),

            BelongsTo::make(__('Unit'), 'unit', MeasuringUnit::class) 
                ->showCreateRelationButton()
                ->withoutTrashed() 
                ->required()
                ->rules('required'), 

            Number::make(__('Required Value'), 'value')
                ->required()
                ->rules('required'),

            Currency::make(__('Unit Price'), 'price') 
                ->nullable(),
        ];
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    /**
     * Determine if this resource is available for navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return false;
    }
}
