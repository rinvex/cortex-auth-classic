<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Exception;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\DefaultImporter;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Foundation\DataTables\ImportLogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Managerarea\MembersDataTable;
use Cortex\Foundation\DataTables\ImportRecordsDataTable;
use Cortex\Auth\Http\Requests\Managerarea\MemberFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Managerarea\MemberAttributesFormRequest;

class MembersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = Member::class;

    /**
     * List all members.
     *
     * @param \Cortex\Auth\DataTables\Managerarea\MembersDataTable $membersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(MembersDataTable $membersDataTable)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $tags = app('rinvex.tags.tag')->all()->groupBy('group')->map->pluck('name', 'id')->sortKeys();
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];

        return $membersDataTable->with([
            'id' => 'managerarea-members-index',
            'countries' => $countries,
            'languages' => $languages,
            'genders' => $genders,
            'tags' => $tags,
        ])->render('cortex/auth::managerarea.pages.members');
    }

    /**
     * List member logs.
     *
     * @param \Cortex\Auth\Models\Member                  $member
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Member $member, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $member,
            'tabs' => 'managerarea.members.tabs',
            'id' => "managerarea-members-{$member->getRouteKey()}-logs",
        ])->render('cortex/foundation::managerarea.pages.datatable-tab');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Auth\Models\Member                        $member
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(Member $member, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $member,
            'tabs' => 'managerarea.members.tabs',
            'id' => "managerarea-members-{$member->getRouteKey()}-activities",
        ])->render('cortex/foundation::managerarea.pages.datatable-tab');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, Member $member)
    {
        return view('cortex/auth::managerarea.pages.member-attributes', compact('member'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\MemberAttributesFormRequest $request
     * @param \Cortex\Auth\Models\Member                                         $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(MemberAttributesFormRequest $request, Member $member)
    {
        $data = $request->validated();

        // Update profile
        $member->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Import members.
     *
     * @param \Cortex\Auth\Models\Member                           $member
     * @param \Cortex\Foundation\DataTables\ImportRecordsDataTable $importRecordsDataTable
     *
     * @return \Illuminate\View\View
     */
    public function import(Member $member, ImportRecordsDataTable $importRecordsDataTable)
    {
        return $importRecordsDataTable->with([
            'resource' => $member,
            'tabs' => 'managerarea.attributes.tabs',
            'url' => route('managerarea.members.stash'),
            'id' => "managerarea-attributes-{$member->getRouteKey()}-import",
        ])->render('cortex/foundation::managerarea.pages.datatable-dropzone');
    }

    /**
     * Stash members.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\DefaultImporter       $importer
     *
     * @return void
     */
    public function stash(ImportFormRequest $request, DefaultImporter $importer)
    {
        // Handle the import
        $importer->config['resource'] = $this->resource;
        $importer->config['name'] = 'username';
        $importer->handleImport();
    }

    /**
     * Hoard members.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function hoard(ImportFormRequest $request)
    {
        foreach ((array) $request->get('selected_ids') as $recordId) {
            $record = app('cortex.foundation.import_record')->find($recordId);

            try {
                $fillable = collect($record['data'])->intersectByKeys(array_flip(app('cortex.auth.member')->getFillable()))->toArray();

                tap(app('cortex.auth.member')->firstOrNew($fillable), function ($instance) use ($record) {
                    $instance->save() && $record->delete();
                });
            } catch (Exception $exception) {
                $record->notes = $exception->getMessage().(method_exists($exception, 'getMessageBag') ? "\n".json_encode($exception->getMessageBag())."\n\n" : '');
                $record->status = 'fail';
                $record->save();
            }
        }

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/foundation::messages.import_complete')],
        ]);
    }

    /**
     * List member import logs.
     *
     * @param \Cortex\Foundation\DataTables\ImportLogsDataTable $importLogsDatatable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function importLogs(ImportLogsDataTable $importLogsDatatable)
    {
        return $importLogsDatatable->with([
            'resource' => trans('cortex/auth::common.member'),
            'tabs' => 'managerarea.members.tabs',
            'id' => 'managerarea-members-import-logs',
        ])->render('cortex/foundation::managerarea.pages.datatable-tab');
    }

    /**
     * Show member create/edit form.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Cortex\Auth\Models\Member $member
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Member $member)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];
        $abilities = $request->user($this->getGuard())->getManagedAbilities();
        $roles = $request->user($this->getGuard())->getManagedRoles();

        return view('cortex/auth::managerarea.pages.member', compact('member', 'abilities', 'roles', 'countries', 'languages', 'genders', 'tags'));
    }

    /**
     * Store new member.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\MemberFormRequest $request
     * @param \Cortex\Auth\Models\Member                               $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(MemberFormRequest $request, Member $member)
    {
        return $this->process($request, $member);
    }

    /**
     * Update given member.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\MemberFormRequest $request
     * @param \Cortex\Auth\Models\Member                               $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(MemberFormRequest $request, Member $member)
    {
        return $this->process($request, $member);
    }

    /**
     * Process stored/updated member.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Member              $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Member $member)
    {
        // Prepare required input fields
        $data = $request->validated();

        ! $request->hasFile('profile_picture')
        || $member->addMediaFromRequest('profile_picture')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('profile_picture', config('cortex.foundation.media.disk'));

        ! $request->hasFile('cover_photo')
        || $member->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.foundation.media.disk'));

        // Save member
        $member->fill($data)->save();

        return intend([
            'url' => route('managerarea.members.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.member'), 'identifier' => $member->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given member.
     *
     * @param \Cortex\Auth\Models\Member $member
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return intend([
            'url' => route('managerarea.members.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.member'), 'identifier' => $member->getRouteKey()])],
        ]);
    }
}
