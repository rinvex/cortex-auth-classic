<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Models\Ability;
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
     * {@inheritdoc}
     */
    protected $resourceActionWhitelist = ['grant'];

    /**
     * Display a listing of the resource.
     *
     * \Cortex\Fort\DataTables\Adminarea\AbilitiesDataTable $abilitiesDataTable
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
     * Get a listing of the resource logs.
     *
     * @param \Rinvex\Fort\Models\Ability $ability
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
            ? Models::role()->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        return view('cortex/fort::adminarea.pages.ability', compact('ability', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AbilityFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.ability'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Rinvex\Fort\Models\Ability                             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AbilityFormRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Rinvex\Fort\Models\Ability             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Ability $ability)
    {
        // Prepare required input fields
        $data = $request->validated();
        $class = mb_strstr($data['policy'], '@', true);
        $method = str_replace('@', '', mb_strstr($data['policy'], '@'));

        // Verify valid policy
        if (! $data['policy'] || ! class_exists($class) || ! method_exists($class, $method)) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => ['policy' => trans('cortex/fort::messages.ability.invalid_policy')],
            ]);
        }

        // Save ability
        $ability->fill($data)->save();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'ability', 'id' => $ability->name])],
        ]);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Models\Ability $ability
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
