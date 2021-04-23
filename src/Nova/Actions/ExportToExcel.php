<?php

namespace Zareismail\ProjectManager\Nova\Actions;
  
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\LaravelNovaExcel\Actions\ExportToExcel as BaseExportToExcel;
use Zareismail\ProjectManager\Models\InventoryMaterial;

class ExportToExcel extends BaseExportToExcel
{  
    /**
     * @param \Laravel\Nova\Http\Requests\ActionRequest $request
     * @param \Laravel\Nova\Actions\Action        $exportable
     *
     * @return array
     */
    public function handle(ActionRequest $request, Action $exportable): array
    { 
        $this->onSuccess(function() {  
            return static::download(
                \Storage::disk($this->getDisk())->url($this->getFilename()), $this->getFilename()
            );
        }); 

        $this->withFilename($request->findParentResourceOrFail()->title());

        $query = InventoryMaterial::where('inventory_id', $request->viaResourceId);

        $this->handleHeadings($query, $this->request); 

        return parent::handle($request, $this->withQuery($query));
    }
}
