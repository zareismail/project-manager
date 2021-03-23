<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Fields\{ID, Text, Number, Select, Currency, BelongsTo, HasMany};
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\NovaContracts\Nova\User;
use Zareismail\Keil\Nova\MeasuringUnit;
use Zareismail\Task\Nova\{Task, Actions\CreateTask};

class Inventory extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\ProjectManager\Models\Inventory::class; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['project'];

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

            BelongsTo::make(__('User'), 'auth', User::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->searchable()
                ->required()
                ->rules('required')
                ->exceptOnForms(),

            BelongsTo::make(__('Project'), 'project', Project::class) 
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->searchable()
                ->required()
                ->rules('required'),

            HasMany::make(__('Materials'), 'items', InventoryMaterial::class),

            HasMany::make(__('Tasks'), 'tasks', Task::class),
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
            CreateTask::make()->onlyOnTableRow()
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->project->name;
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }
}
