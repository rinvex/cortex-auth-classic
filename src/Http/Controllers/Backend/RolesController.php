<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Cortex\Fort\Models\Role;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\DataTables\Backend\RolesDataTable;
use Cortex\Fort\Http\Requests\Backend\RoleFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RolesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'roles';

    /**
     * {@inheritdoc}
     */
    protected $resourceActionWhitelist = ['assign'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(RolesDataTable::class)->render('cortex/foundation::backend.partials.datatable', ['resource' => 'cortex/fort::common.roles']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\RoleFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleFormRequest $request)
    {
        return $this->process($request, new Role());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\RoleFormRequest $request
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleFormRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Role $role)
    {
        $role->delete();

        return intend([
            'url' => route('backend.roles.index'),
            'with' => ['warning' => trans('cortex/fort::messages.role.deleted', ['roleId' => $role->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Role $role)
    {
        $abilityList = Ability::all()->groupBy('resource')->map(function ($ability) {
            return $ability->pluck('name', 'id');
        })->toArray();

        return view('cortex/fort::backend.forms.role', compact('role', 'abilityList'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, Role $role)
    {
        // Prepare required input fields
        $data = $request->all();

        // Save role
        $role->fill($data)->save();

        // Sync abilities
        if ($request->user($this->getGuard())->can('grant-abilities')) {
            $role->abilities()->sync((array) array_pull($data, 'abilityList'));
        }

        return intend([
            'url' => route('backend.roles.index'),
            'with' => ['success' => trans('cortex/fort::messages.role.saved', ['roleId' => $role->id])],
        ]);
    }
}
