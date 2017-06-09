<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Cortex\Fort\Models\Role;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\DataTables\Backend\AbilitiesDataTable;
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
        return app(AbilitiesDataTable::class)->render('cortex/foundation::backend.partials.datatable', ['resource' => 'cortex/fort::common.abilities']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->process($request, new Ability());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Ability $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('backend.abilities.index'),
            'with' => ['warning' => trans('cortex/fort::messages.ability.deleted', ['abilityId' => $ability->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Ability $ability)
    {
        $roleList = Role::all()->pluck('name', 'id');

        return view('cortex/fort::backend.forms.ability', compact('ability', 'roleList'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Fort\Models\Ability $ability
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, Ability $ability)
    {
        // Prepare required input fields
        $input = $request->all();

        // Verify valid policy
        if (! empty($input['policy']) && (($class = mb_strstr($input['policy'], '@', true)) === false || ! method_exists($class, str_replace('@', '', mb_strstr($input['policy'], '@'))))) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => ['policy' => trans('cortex/fort::messages.ability.invalid_policy')],
            ]);
        }

        // Save ability
        $ability->fill($input)->save();

        return intend([
            'url' => route('backend.abilities.index'),
            'with' => ['success' => trans('cortex/fort::messages.ability.saved', ['abilityId' => $ability->id])],
        ]);
    }
}
