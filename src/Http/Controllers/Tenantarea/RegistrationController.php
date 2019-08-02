<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Tenantarea;

use Cortex\Auth\Models\Member;
use Illuminate\Auth\Events\Registered;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Auth\Http\Requests\Tenantarea\RegistrationRequest;
use Cortex\Auth\Http\Requests\Tenantarea\RegistrationProcessRequest;

class RegistrationController extends AbstractController
{
    /**
     * Create a new registration controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware($this->getGuestMiddleware())->except($this->middlewareWhitelist);
    }

    /**
     * Show the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Tenantarea\RegistrationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function form(RegistrationRequest $request)
    {
        return view('cortex/auth::tenantarea.pages.member-registration');
    }

    /**
     * Process the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Tenantarea\RegistrationProcessRequest $request
     * @param \Cortex\Auth\Models\Member                                              $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationProcessRequest $request, Member $member)
    {
        // Prepare registration data
        $data = $request->validated();

        $member->fill($data)->save();

        // Fire the register success event
        event(new Registered($member));

        // Send verification if required
        ! config('cortex.auth.emails.verification')
        || app('rinvex.auth.emailverification')->broker($this->getEmailVerificationBroker())->sendVerificationLink(['email' => $data['email']]);

        // Auto-login registered member
        auth()->guard($this->getGuard())->login($member);

        // Registration completed successfully
        return intend([
            'intended' => route('tenantarea.home'),
            'with' => ['success' => trans('cortex/auth::messages.register.success')],
        ]);
    }
}
