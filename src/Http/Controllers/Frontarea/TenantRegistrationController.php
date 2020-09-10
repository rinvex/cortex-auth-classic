<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Cortex\Auth\Models\Manager;
use Cortex\Tenants\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Cortex\Auth\Http\Requests\Frontarea\TenantRegistrationRequest;
use Cortex\Auth\Http\Requests\Frontarea\TenantRegistrationProcessRequest;

class TenantRegistrationController extends RegistrationController
{
    /**
     * Show the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\TenantRegistrationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function form(TenantRegistrationRequest $request)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();
        $languages = collect(languages())->pluck('name', 'iso_639_1');

        return view('cortex/auth::frontarea.pages.tenant-registration', compact('countries', 'languages'));
    }

    /**
     * Process the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\TenantRegistrationProcessRequest $request
     * @param \Cortex\Auth\Models\Manager                                           $manager
     * @param \Cortex\Tenants\Models\Tenant                                         $tenant
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(TenantRegistrationProcessRequest $request, Manager $manager, Tenant $tenant)
    {
        // Prepare registration data
        $managerData = $request->validated()['manager'];
        $tenantData = $request->validated()['tenant'];

        $manager->fill($managerData)->save();

        // Save tenant
        $tenant->fill($tenantData)->save();
        $manager->attachTenants($tenant);
        $manager->assign('supermanager');

        // Fire the register success event
        event(new Registered($manager));

        // Send verification if required
        ! config('cortex.auth.emails.verification')
        || app('rinvex.auth.emailverification')->broker(app('request.emailVerificationBroker'))->sendVerificationLink(['email' => $manager->email]);

        // Auto-login registered manager
        auth()->guard('managers')->login($manager);

        // Registration completed successfully
        return intend([
            'intended' => route('managerarea.home', ['subdomain' => $tenant->name]),
            'with' => ['success' => trans('cortex/auth::messages.register.success')],
        ]);
    }
}
