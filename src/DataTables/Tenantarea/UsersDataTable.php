<?php

declare(strict_types=1);

namespace Cortex\Fort\DataTables\Tenantarea;

use Rinvex\Fort\Contracts\UserContract;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Fort\Transformers\Tenantarea\UserTransformer;

class UsersDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = UserContract::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = UserTransformer::class;

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'tenantarea.users.edit\', {user: full.username, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'tenantarea.users.edit\', {user: full.username})+"\">"+data+"</a>"';

        return [
            'username' => ['title' => trans('cortex/fort::common.username'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'first_name' => ['title' => trans('cortex/fort::common.first_name')],
            'last_name' => ['title' => trans('cortex/fort::common.last_name')],
            'email' => ['title' => trans('cortex/fort::common.email'), 'render' => 'data+(data ? "&nbsp;&nbsp;"+(full.email_verified ? "<i class=\"text-success fa fa-check\" title=\""+full.email_verified_at+"\"></i>" : "<i class=\"text-danger fa fa-close\"></i>") : "")'],
            'phone' => ['title' => trans('cortex/fort::common.phone'), 'render' => 'data+(data ? "&nbsp;&nbsp;"+(full.phone_verified ? "<i class=\"text-success fa fa-check\" title=\""+full.phone_verified_at+"\"></i>" : "<i class=\"text-danger fa fa-close\"></i>") : "")'],
            'country_code' => ['title' => trans('cortex/fort::common.country')],
            'created_at' => ['title' => trans('cortex/fort::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/fort::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
