<?php

declare(strict_types=1);

namespace Cortex\Auth\DataTables\Managerarea;

use Cortex\Auth\Models\Manager;
use Illuminate\Database\Eloquent\Builder;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Auth\Transformers\Managerarea\ManagerTransformer;

class ManagersDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Manager::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = ManagerTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $query = $this->query();

        if (! empty($this->request->get('country_code'))) {
            $query->where('country_code', $this->request->get('country_code'));
        }

        if (! empty($this->request->get('language_code'))) {
            $query->where('language_code', $this->request->get('language_code'));
        }

        if (! empty($this->request->get('gender'))) {
            $query->where('gender', $this->request->get('gender'));
        }

        if (! empty($this->request->get('tags'))) {
            $query->whereHas('tags', function (Builder $builder) {
                $builder->whereIn('id', $this->request->get('tags'));
            });
        }

        return datatables($query)
            ->setTransformer(app($this->transformer))
            ->filterColumn('country_code', function (Builder $builder, $keyword) {
                $countryCode = collect(countries())->search(function ($country) use ($keyword) {
                    return mb_strpos($country['name'], $keyword) !== false || mb_strpos($country['emoji'], $keyword) !== false;
                });

                ! $countryCode || $builder->where('country_code', $countryCode);
            })
            ->filterColumn('language_code', function (Builder $builder, $keyword) {
                $languageCode = collect(languages())->search(function ($language) use ($keyword) {
                    return mb_strpos($language['name'], $keyword) !== false;
                });

                ! $languageCode || $builder->where('language_code', $languageCode);
            })
            ->make(true);
    }

    /**
     * Get Ajax form.
     *
     * @return string
     */
    protected function getAjaxForm(): string
    {
        return '#managerarea-managers-filters-form';
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'managerarea.managers.edit\', {manager: full.id, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'managerarea.managers.edit\', {manager: full.id})+"\">"+data+"</a>"';

        return [
            'id' => ['checkboxes' => '{"selectRow": true}', 'exportable' => false, 'printable' => false],
            'given_name' => ['title' => trans('cortex/auth::common.given_name'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'family_name' => ['title' => trans('cortex/auth::common.family_name')],
            'username' => ['title' => trans('cortex/auth::common.username')],
            'email' => ['title' => trans('cortex/auth::common.email'), 'render' => 'data+(data ? (full.email_verified_at ? " <i class=\"text-success fa fa-check\" title=\""+full.email_verified_at+"\"></i>" : " <i class=\"text-danger fa fa-close\"></i>") : "")'],
            'phone' => ['title' => trans('cortex/auth::common.phone'), 'render' => 'data+(data ? (full.phone_verified_at ? " <i class=\"text-success fa fa-check\" title=\""+full.phone_verified_at+"\"></i>" : " <i class=\"text-danger fa fa-close\"></i>") : "")'],
            'country_code' => ['title' => trans('cortex/auth::common.country'), 'render' => 'full.country_emoji+" "+data'],
            'language_code' => ['title' => trans('cortex/auth::common.language'), 'visible' => false],
            'title' => ['title' => trans('cortex/auth::common.title'), 'visible' => false],
            'organization' => ['title' => trans('cortex/auth::common.organization'), 'visible' => false],
            'birthday' => ['title' => trans('cortex/auth::common.birthday'), 'visible' => false],
            'gender' => ['title' => trans('cortex/auth::common.gender'), 'visible' => false],
            'last_activity' => ['title' => trans('cortex/auth::common.last_activity'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')", 'visible' => false],
            'created_at' => ['title' => trans('cortex/auth::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/auth::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
