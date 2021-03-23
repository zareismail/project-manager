<?php

namespace Zareismail\ProjectManager\Nova\Actions;
 
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\{ActionFields, Select};
use Zareismail\NovaContracts\Nova\User;
use Zareismail\ProjectManager\Nova\{Inventory, InventoryMaterial};

class DetermineSubstitutes extends Action
{  
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {  
        $models->first()->auth->substitutes()->sync([
            $fields->substitutes => [
                'end_date' => $fields->end_date,
            ]
        ]);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [ 
            Select::make(__('Substitutes User'), 'substitutes')
                ->options(User::newModel()->get()->keyBy->getKey()->map->fullname())
                ->searchable()
                ->required()
                ->rules('required'),

            User::dateField(__('End Date'), 'end_date')
                ->required()
                ->rules('required'),
        ];
    }
}
