<?php

declare(strict_types=1);

namespace Cortex\Auth\DataTables\Adminarea;

use Cortex\Auth\Models\Ability;
use Illuminate\Http\JsonResponse;
use Cortex\Auth\Transformers\AbilityTransformer;
use Cortex\Foundation\DataTables\AbstractDataTable;

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
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = parent::query();
        $user = $this->request()->user();

        if ($user->isNotA('superadmin')) {
            $query = $query->whereIn('id', $user->getAbilities()->pluck('id')->toArray());
        }

        return $query;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax(): JsonResponse
    {
        return datatables($this->query())
            ->setTransformer(app($this->transformer))
            ->orderColumn('title', 'title->"$.'.app()->getLocale().'" $1')
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
            ? '"<a href=\""+routes.route(\'adminarea.cortex.auth.abilities.edit\', {ability: full.id, locale: \''.$this->request()->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.cortex.auth.abilities.edit\', {ability: full.id})+"\">"+data+"</a>"';

        return [
            'id' => ['checkboxes' => '{"selectRow": true}', 'exportable' => false, 'printable' => false, 'width' => '1%'],
            'title' => ['title' => trans('cortex/auth::common.title'), 'render' => $link, 'responsivePriority' => 0],
            'name' => ['title' => trans('cortex/auth::common.name')],
            'created_at' => ['title' => trans('cortex/auth::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/auth::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
