<?php

declare(strict_types=1);

namespace Cortex\Auth\DataTables\Managerarea;

use Cortex\Auth\Models\Role;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Auth\Transformers\Rolearea\Managerarea\RoleTransformer;

class RolesDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Role::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = RoleTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $currentUser = $this->request->user($this->request->route('guard'));

        $query = ! $currentUser->isA('supermanager') ? app($this->model)->query()->whereIn('id', $currentUser->roles->pluck('id')->toArray())
            : app($this->model)->query()->whereIn('id', $currentUser->roles->merge(config('rinvex.tenants.active') ? app('cortex.auth.role')->where('scope', config('rinvex.tenants.active')->getKey())->get() : collect())->pluck('id')->toArray());

        return $this->applyScopes($query);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
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
            ? '"<a href=\""+routes.route(\'managerarea.roles.edit\', {role: full.id, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'managerarea.roles.edit\', {role: full.id})+"\">"+data+"</a>"';

        return [
            'title' => ['title' => trans('cortex/auth::common.title'), 'render' => $link, 'responsivePriority' => 0],
            'name' => ['title' => trans('cortex/auth::common.name')],
            'created_at' => ['title' => trans('cortex/auth::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/auth::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
