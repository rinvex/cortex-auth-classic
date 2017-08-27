<?php

declare(strict_types=1);

namespace Cortex\Fort\DataTables\Backend;

use Rinvex\Fort\Contracts\RoleContract;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Fort\Transformers\Backend\RoleTransformer;

class RolesDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = RoleContract::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = RoleTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $transformer = app($this->transformer);

        return $this->datatables
            ->eloquent($this->query())
            ->setTransformer($transformer)
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
            'name' => ['title' => trans('cortex/fort::common.name'), 'render' => '"<a href=\""+routes.route(\'backend.roles.edit\', {role: full.slug})+"\">"+data+"</a>"', 'responsivePriority' => 0],
            'created_at' => ['title' => trans('cortex/fort::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/fort::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
