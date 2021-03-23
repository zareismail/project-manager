<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Fields\{ID, Text, Trix, Badge, Boolean, BelongsTo, MorphMany}; 
use Laravel\Nova\Http\Requests\{NovaRequest, ActionRequest};
use Zareismail\Task\Nova\{Task, Actions\CreateTask};
use Zareismail\NovaContracts\Nova\User;

class Leave extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\ProjectManager\Models\Leave::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Requests';

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['auth', 'substitute'];

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
                ->exceptOnForms(), 

            Badge::make(__('Request Status'), 'marked_as')
                ->map([
                    'draft' => 'info',
                    'rejected' => 'danger',
                    'pending' => 'warning',
                    'accepted' => 'success',
                ]),

            static::dateField(__('Started Date'), 'start_date')
                ->required()
                ->rules('required'),

            static::dateField(__('End Date'), 'end_date')
                ->required()
                ->rules('required'),

            Trix::make(__('Note'), 'note')->nullable(),

            Boolean::make(__('Request Later'), 'marked_as')
                ->default(false)
                ->onlyOnForms()
                ->hideWhenUpdating()
                ->fillUsing(function($request, $model) {
                    if(! intval($request->get('marked_as'))) {
                        $model->asPending();
                    }
                }),

            MorphMany::make(__('Related Tasks'), 'tasks', Task::class),
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->auth->fullname();
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Leave');
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
            CreateTask::make() 
                ->onlyOnTableRow()
                ->canSee(function($request) { 
                    if ($request instanceof ActionRequest) {
                        return true;  
                    }

                    if (! $request->user()->is(optional($this->resource)->auth)) {
                        return false;
                    }

                    return optional($this->resource)->isPending();
                }),

            Actions\ActiveRequest::make() 
                ->onlyOnTableRow()
                ->canSee(function($request) { 
                    if ($request instanceof ActionRequest) {
                        return true;  
                    }

                    if (! $request->user()->is(optional($this->resource)->auth)) {
                        return false;
                    }

                    return optional($this->resource)->isDrafted();
                }),

            Actions\DetermineSubstitutes::make() 
                ->onlyOnTableRow()
                ->canSee(function($request) {
                    return $this->canDetermineSubstitutes($request);
                })
                ->canRun(function($request) {
                    return $this->canDetermineSubstitutes($request);
                }),
        ];
    }

    public function canDetermineSubstitutes($request) 
    {
        if ($request instanceof ActionRequest) {
            return true;  
        }

        if (is_null($this->resource)) {
            return false;
        }

        if (! $request->user()->is($this->resource->auth)) {
            return false;
        }

        if (! $this->resource->isAccepted()) {
            return false;
        }

        return ! $this->resource->expired(); 
    }

    /**
     * Determine if the current user has a given ability.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ability
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeTo(Request $request, $ability)
    {
        if ($ability == 'update' && $this->resource->isAccepted()) {
            throw_unless(true, \Illuminate\Auth\Access\AuthorizationException::class);
        }

        return parent::authorizeTo($request, $ability); 
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ability
     * @return bool
     */
    public function authorizedTo(Request $request, $ability)
    {
        if ($ability == 'update' && $this->resource->isAccepted()) {
            return false;
        }

        return parent::authorizedTo($request, $ability);
    }
}
