<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Guardian;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Auth\DataTables\Adminarea\GuardiansDataTable;
use Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class GuardiansController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'guardian';

    /**
     * List all guardians.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\GuardiansDataTable $guardiansDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(GuardiansDataTable $guardiansDataTable)
    {
        return $guardiansDataTable->with([
            'id' => 'adminarea-guardians-index-table',
            'phrase' => trans('cortex/auth::common.guardians'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * List guardian logs.
     *
     * @param \Cortex\Auth\Models\Guardian                $guardian
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Guardian $guardian, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $guardian,
            'tabs' => 'adminarea.guardians.tabs',
            'phrase' => trans('cortex/auth::common.guardians'),
            'id' => "adminarea-guardians-{$guardian->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Create new guardian.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Guardian $guardian)
    {
        return $this->form($request, $guardian);
    }

    /**
     * Edit given guardian.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Guardian $guardian)
    {
        return $this->form($request, $guardian);
    }

    /**
     * Show guardian create/edit form.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Guardian $guardian)
    {
        return view('cortex/auth::adminarea.pages.guardian', compact('guardian'));
    }

    /**
     * Store new guardian.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest $request
     * @param \Cortex\Auth\Models\Guardian                             $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(GuardianFormRequest $request, Guardian $guardian)
    {
        return $this->process($request, $guardian);
    }

    /**
     * Update given guardian.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest $request
     * @param \Cortex\Auth\Models\Guardian                             $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(GuardianFormRequest $request, Guardian $guardian)
    {
        return $this->process($request, $guardian);
    }

    /**
     * Process stored/updated guardian.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Guardian            $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Guardian $guardian)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save guardian
        $guardian->fill($data)->save();

        return intend([
            'url' => route('adminarea.guardians.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'guardian', 'id' => $guardian->username])],
        ]);
    }

    /**
     * Destroy given guardian.
     *
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Guardian $guardian)
    {
        $guardian->delete();

        return intend([
            'url' => route('adminarea.guardians.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'guardian', 'id' => $guardian->username])],
        ]);
    }
}
