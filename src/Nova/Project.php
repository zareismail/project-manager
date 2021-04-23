<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Fields\{ID, Text, Number, Currency, BelongsTo, HasMany};
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
                // ->searchable()
                ->required()
                ->rules('required'),

            BelongsTo::make(__('Employer'), 'employer', Employer::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                // ->searchable()
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

            Currency::make(__('Project Amount'), 'amount')
                ->nullable()
                ->currency('IRR'),

            Currency::make(__('Warning Point'), 'warning')
                ->nullable()
                ->currency('IRR'),

            static::dateField(__('Started Date'), 'start_date')
                ->hideFromIndex(),

            static::dateField(__('Renewal Date'), 'renewal_date')
                ->hideFromIndex(),

            static::dateField(__('Finish Date'), 'finish_date')
                ->hideFromIndex(),

            HasMany::make(__('Inventory'), 'inventory', Inventory::class),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            Filters\Manager::make(),
            Filters\Employer::make(),
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
                ->onlyOnTableRow(),
        ];
    }
}
