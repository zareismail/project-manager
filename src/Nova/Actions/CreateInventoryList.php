<?php

namespace Zareismail\ProjectManager\Nova\Actions;
 
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\{ActionFields, Select};
use Zareismail\ProjectManager\Nova\{Inventory, InventoryMaterial};

class CreateInventoryList extends Action
{  
    /**
     * Determine where the action redirection should be without confirmation.
     *
     * @var bool
     */
    public $withoutConfirmation = true;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {  
        $inventory = $models->first()->inventory()->firstOrCreate();

        return [
            'push' => [
                'name' => 'create',
                'params' => [
                    'resourceName' => InventoryMaterial::uriKey(),
                ],
                'query' => [
                    'viaResource' => Inventory::uriKey(),
                    'viaResourceId' => $inventory->id,
                    'viaRelationship' => 'items',
                ],
            ],
        ];
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [ 
        ];
    }
}
