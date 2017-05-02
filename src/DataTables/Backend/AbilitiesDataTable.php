<?php

declare(strict_types=1);

namespace Cortex\Fort\DataTables\Backend;

use Cortex\Fort\Models\Ability;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Fort\Transformers\Backend\AbilityTransformer;

class AbilitiesDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Ability::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = AbilityTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->setTransformer(new $this->transformer())
            ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name' => ['title' => trans('cortex/fort::common.name'), 'render' => '"<a href=\""+routes.route(\'backend.abilities.edit\', {ability: full.id})+"\">"+data+"</a>"', 'responsivePriority' => 0],
            'action' => ['title' => trans('cortex/fort::common.action')],
            'resource' => ['title' => trans('cortex/fort::common.resource')],
            'policy' => ['title' => trans('cortex/fort::common.policy')],
            'created_at' => ['title' => trans('cortex/fort::common.created_at'), 'width' => '15%', 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/fort::common.updated_at'), 'width' => '15%', 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
