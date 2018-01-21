<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Rinvex\Fort\Models\User;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Frontarea\RegistrationRequest;
use Cortex\Fort\Http\Requests\Frontarea\RegistrationProcessRequest;

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
     * @param \Cortex\Fort\Http\Requests\Frontarea\RegistrationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function form(RegistrationRequest $request)
    {
        return view('cortex/fort::frontarea.pages.registration');
    }

    /**
     * Process the registration form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontarea\RegistrationProcessRequest $request
     * @param \Rinvex\Fort\Models\User                                        $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(RegistrationProcessRequest $request, User $user)
    {
        // Prepare registration data
        $data = $request->validated();

        $user->fill($data)->save();

        // Fire the register success event
        event('rinvex.fort.register.success', [$user]);

        // Send verification if required
        if (config('rinvex.fort.emailverification.required')) {
            app('rinvex.fort.emailverification')->broker()->sendVerificationLink(['email' => $data['email']]);

            // Registration completed, verification required
            return intend([
                'url' => route('frontarea.verification.email.request'),
                'with' => ['success' => trans('cortex/fort::messages.register.success_verify')],
            ]);
        }

        // Registration completed successfully
        return intend([
            'url' => route('frontarea.login'),
            'with' => ['success' => trans('cortex/fort::messages.register.success')],
        ]);
    }
}
