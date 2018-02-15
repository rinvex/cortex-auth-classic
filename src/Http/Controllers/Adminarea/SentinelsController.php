<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Fort\Models\Sentinel;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\SentinelsDataTable;
use Cortex\Fort\Http\Requests\Adminarea\SentinelFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class SentinelsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'sentinel';

    /**
     * List all sentinels.
     *
     * @param \Cortex\Fort\DataTables\Adminarea\SentinelsDataTable $sentinelsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(SentinelsDataTable $sentinelsDataTable)
    {
        return $sentinelsDataTable->with([
            'id' => 'adminarea-sentinels-index-table',
            'phrase' => trans('cortex/fort::common.sentinels'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * List sentinel logs.
     *
     * @param \Cortex\Fort\Models\Sentinel                $sentinel
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Sentinel $sentinel, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $sentinel,
            'tabs' => 'adminarea.sentinels.tabs',
            'phrase' => trans('cortex/fort::common.sentinels'),
            'id' => "adminarea-sentinels-{$sentinel->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Create new sentinel.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Fort\Models\Sentinel $sentinel
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Sentinel $sentinel)
    {
        return $this->form($request, $sentinel);
    }

    /**
     * Edit given sentinel.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Fort\Models\Sentinel $sentinel
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Sentinel $sentinel)
    {
        return $this->form($request, $sentinel);
    }

    /**
     * Show sentinel create/edit form.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Fort\Models\Sentinel $sentinel
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Sentinel $sentinel)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();
        $currentUser = $request->user($this->getGuard());
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/fort::common.male'), 'female' => trans('cortex/fort::common.female')];

        $roles = $currentUser->can('superadmin')
            ? app('cortex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $currentUser->roles->pluck('name', 'id')->toArray();

        $abilities = $currentUser->can('superadmin')
            ? app('cortex.fort.ability')->all()->pluck('title', 'id')->toArray()
            : $currentUser->abilities->pluck('title', 'id')->toArray();

        return view('cortex/fort::adminarea.pages.sentinel', compact('sentinel', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Store new sentinel.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\SentinelFormRequest $request
     * @param \Cortex\Fort\Models\Sentinel                             $sentinel
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(SentinelFormRequest $request, Sentinel $sentinel)
    {
        return $this->process($request, $sentinel);
    }

    /**
     * Update given sentinel.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\SentinelFormRequest $request
     * @param \Cortex\Fort\Models\Sentinel                             $sentinel
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(SentinelFormRequest $request, Sentinel $sentinel)
    {
        return $this->process($request, $sentinel);
    }

    /**
     * Process stored/updated sentinel.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Fort\Models\Sentinel            $sentinel
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Sentinel $sentinel)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save sentinel
        $sentinel->fill($data)->save();

        return intend([
            'url' => route('adminarea.sentinels.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'sentinel', 'id' => $sentinel->username])],
        ]);
    }

    /**
     * Destroy given sentinel.
     *
     * @param \Cortex\Fort\Models\Sentinel $sentinel
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Sentinel $sentinel)
    {
        $sentinel->delete();

        return intend([
            'url' => route('adminarea.sentinels.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'sentinel', 'id' => $sentinel->username])],
        ]);
    }
}
