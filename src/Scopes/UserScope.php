<?php

declare(strict_types=1);

namespace Cortex\Auth\Scopes;

use Yajra\DataTables\Utilities\Request;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Contracts\DataTableScope;

class UserScope implements DataTableScope
{
    /**
     * The datatable request.
     *
     * @var \Yajra\DataTables\Utilities\Request
     */
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @param \Yajra\DataTables\Utilities\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($query)
    {
        // Filter fields
        collect([
            'country_code' => ['field' => 'country_code', 'operator' => '='],
            'language_code' => ['field' => 'language_code', 'operator' => '='],
            'gender' => ['field' => 'gender', 'operator' => '='],
            'created_at_from' => ['field' => 'created_at', 'operator' => '>='],
            'created_at_to' => ['field' => 'created_at', 'operator' => '<='],
        ])->each(function ($filter, $key) use ($query) {
            if (! empty($this->request->get($key))) {
                $query->where($filter['field'], $filter['operator'], $this->request->get($key));
            }
        });

        // Filter relationships
        if (! empty($this->request->get('tags'))) {
            $query->whereHas('tags', function (Builder $builder) {
                $builder->whereIn('id', $this->request->get('tags'));
            });
        }

        if (! empty($this->request->get('role_id'))) {
            $query->whereHas('roles', function (Builder $builder) {
                $builder->where('id', $this->request->get('role_id'));
            });
        }

        return $query;
    }
}
