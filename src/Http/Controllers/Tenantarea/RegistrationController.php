<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Rinvex\Fort\Models\User;
use Illuminate\Auth\Events\Registered;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Tenantarea\RegistrationRequest;
use Cortex\Fort\Http\Requests\Tenantarea\RegistrationProcessRequest;

class RegistrationController extends AbstractController
{
    /**
     * Create a new registration controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware($this->getGuestMiddleware(), ['except' => $this->middlewareWhitelist]);
    }

    /**
     * Show the registration form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\RegistrationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function form(RegistrationRequest $request)
    {
        return view('cortex/fort::tenantarea.pages.registration');
    }

    /**
     * Process the registration form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\RegistrationProcessRequest $request
     * @param \Cortex\Fort\Models\User                                         $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationProcessRequest $request, User $user)
    {
        // Prepare registration data
        $data = $request->validated();

        $user->fill($data)->save();

        // Fire the register success event
        event(new Registered($user));

        // Send verification if required
        ! config('rinvex.fort.emailverification.required')
        || app('rinvex.fort.emailverification')->broker()->sendVerificationLink(['email' => $data['email']]);

        // Auto-login registered user
        auth()->guard($this->getGuard())->login($user);

        // Registration completed successfully
        return intend([
            'intended' => route('tenantarea.home'),
            'with' => ['success' => trans('cortex/fort::messages.register.success')],
        ]);
    }
}
