<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\AbilitiesDataTable;
use Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class AbilitiesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'ability';

    /**
     * List all abilities.
     *
     * @param \Cortex\Fort\DataTables\Adminarea\AbilitiesDataTable $abilitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AbilitiesDataTable $abilitiesDataTable)
    {
        return $abilitiesDataTable->with([
            'id' => 'adminarea-abilities-index-table',
            'phrase' => trans('cortex/fort::common.abilities'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * List ability logs.
     *
     * @param \Cortex\Fort\Models\Ability                 $ability
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Ability $ability, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $ability,
            'tabs' => 'adminarea.abilities.tabs',
            'phrase' => trans('cortex/fort::common.abilities'),
            'id' => "adminarea-abilities-{$ability->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Create new ability.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $ability
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Edit given ability.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $ability
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Show ability create/edit form.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Ability $ability)
    {
        $roles = $request->user($this->getGuard())->can('superadmin')
            ? app('cortex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        return view('cortex/fort::adminarea.pages.ability', compact('ability', 'roles'));
    }

    /**
     * Store new ability.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Cortex\Fort\Models\Ability                             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AbilityFormRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Update given ability.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Cortex\Fort\Models\Ability                             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AbilityFormRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Process stored/updated ability.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Fort\Models\Ability             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Ability $ability)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save ability
        $ability->fill($data)->save();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'ability', 'id' => $ability->name])],
        ]);
    }

    /**
     * Destroy given ability.
     *
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'ability', 'id' => $ability->name])],
        ]);
    }
}
