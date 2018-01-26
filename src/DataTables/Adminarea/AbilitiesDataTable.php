<?php

declare(strict_types=1);

namespace Cortex\Fort\DataTables\Adminarea;

use Rinvex\Fort\Models\Ability;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Fort\Transformers\Adminarea\AbilityTransformer;

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
        $transformer = app($this->transformer);

        return datatables($this->query())
            ->setTransformer($transformer)
            ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.abilities.edit\', {ability: full.id, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.abilities.edit\', {ability: full.id})+"\">"+data+"</a>"';

        return [
            'name' => ['title' => trans('cortex/fort::common.name'), 'render' => $link, 'responsivePriority' => 0],
            'action' => ['title' => trans('cortex/fort::common.action')],
            'resource' => ['title' => trans('cortex/fort::common.resource')],
            'policy' => ['title' => trans('cortex/fort::common.policy')],
            'created_at' => ['title' => trans('cortex/fort::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/fort::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
