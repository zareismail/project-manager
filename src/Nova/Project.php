<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Fields\{ID, Text, Number, Currency, BelongsTo, HasOne};
use Laravel\Nova\Http\Requests\{NovaRequest, ActionRequest};
use Zareismail\NovaContracts\Nova\User;

class Project extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\ProjectManager\Models\Project::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'number',
    ]; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [
        'inventory'
    ];

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

            BelongsTo::make(__('Manager'), 'manager', User::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->searchable()
                ->required()
                ->rules('required'),

            BelongsTo::make(__('Employer'), 'employer', Employer::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->searchable()
                ->required()
                ->rules('required'), 

            Text::make(__('Project Name'), 'name')
                ->required()
                ->rules('required'),

            Text::make(__('Project Number'), 'number')
                ->required()
                ->rules('required', 'unique:pm_projects,number,{{resourceId}}'),

            Number::make(__('Coefficient'), 'coefficient')
                ->required()
                ->rules('required'),

            Currency::make(__('Warning Point'), 'warning')
                ->nullable()
                ->currency('IRR'),

            static::dateField(__('Started Date'), 'start_date')
                ->hideFromIndex(),

            static::dateField(__('Finish Date'), 'finish_date')
                ->hideFromIndex(),

            HasOne::make(__('Inventory'), 'inventory', Inventory::class),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\CreateInventoryList::make()
                ->onlyOnTableRow()
                ->canSee(function($request) {
                    if ($request instanceof ActionRequest) {
                        return true;  
                    }

                    return is_null(optional($this->resource)->inventory);
                }),
        ];
    }
}
