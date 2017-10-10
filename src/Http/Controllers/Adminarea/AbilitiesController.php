<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\AbilityContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\AbilitiesDataTable;
use Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class AbilitiesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'abilities';

    /**
     * {@inheritdoc}
     */
    protected $resourceActionWhitelist = ['grant'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(AbilitiesDataTable::class)->with([
            'id' => 'cortex-fort-abilities',
            'phrase' => trans('cortex/fort::common.abilities'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs(AbilityContract $ability)
    {
        return app(LogsDataTable::class)->with([
            'tab' => 'logs',
            'type' => 'abilities',
            'resource' => $ability,
            'id' => 'cortex-fort-abilities-logs',
            'phrase' => trans('cortex/fort::common.abilities'),
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AbilityFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.ability'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Rinvex\Fort\Contracts\AbilityContract                  $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AbilityFormRequest $request, AbilityContract $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Contracts\AbilityContract $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(AbilityContract $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['warning' => trans('cortex/fort::messages.ability.deleted', ['abilityId' => $ability->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Illuminate\Http\Request               $request
     * @param \Rinvex\Fort\Contracts\AbilityContract $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request, AbilityContract $ability)
    {
        $roles = $request->user($this->getGuard())->isSuperadmin()
            ? app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        return view('cortex/fort::adminarea.forms.ability', compact('ability', 'roles'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request               $request
     * @param \Rinvex\Fort\Contracts\AbilityContract $ability
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, AbilityContract $ability)
    {
        // Prepare required input fields
        $data = $request->all();

        // Verify valid policy
        if (! empty($data['policy']) && (($class = mb_strstr($data['policy'], '@', true)) === false || ! method_exists($class, str_replace('@', '', mb_strstr($data['policy'], '@'))))) {
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
            'with' => ['success' => trans('cortex/fort::messages.ability.saved', ['abilityId' => $ability->id])],
        ]);
    }
}
