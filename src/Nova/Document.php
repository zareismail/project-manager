<?php

namespace Zareismail\ProjectManager\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\{NovaRequest, ActionRequest};
use Laravel\Nova\Fields\{ID, Text, Trix, MorphMany};
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Zareismail\Task\Nova\{Task, Actions\CreateTask};

class Document extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\ProjectManager\Models\Document::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Requests';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'uniqueness_id'
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

            Text::make(__('Document Number'), 'uniqueness_id')
                ->exceptOnForms(),

            Text::make(__('Document Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required'),

            Trix::make(__('Note'), 'note')
                ->nullable(),

            Medialibrary::make(__('Attachments'), 'attachments')
                ->autouploading()
                ->hideFromIndex()
                ->required()
                ->rules('required'),

            MorphMany::make(__('Related Tasks'), 'tasks', Task::class),
        ];
    } 

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return request()->route('resource') == Task::uriKey() 
                    ? __('Document Circulation') 
                    : parent::singularLabel();
    }

    /**
     * Authenticate the query for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function authenticateQuery(NovaRequest $request, $query)
    {
        return $query->where(function($query) use ($request) {
            $query->when(static::shouldAuthenticate($request), function($query) use ($request) {
                $query->authenticate()->orWhereHas('tasks', function($query) use ($request) {
                    Task::buildIndexQuery($request, $query);
                });
            });
        });
    }

    /**
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToView(Request $request)
    {
        return true;
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return true;
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

                    return $request->user()->is(optional($this->resource)->auth);
                }),
        ];
    }
}
