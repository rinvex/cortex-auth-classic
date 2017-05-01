<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AbstractController;

class RegistrationController extends AbstractController
{
    /**
     * Create a new registration controller instance.
     */
    public function __construct()
    {
        $this->middleware($this->getGuestMiddleware(), ['except' => $this->middlewareWhitelist]);
    }

    /**
     * Show the registration form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function form(Request $request)
    {
        if (! config('rinvex.fort.registration.enabled')) {
            return $this->redirect();
        }

        return view('cortex/fort::frontend.authentication.register');
    }

    /**
     * Process the registration form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(Request $request, User $user)
    {
        if (! config('rinvex.fort.registration.enabled')) {
            return $this->redirect();
        }

        // Prepare registration data
        $input = $request->only(['username', 'email', 'password', 'password_confirmation']);
        $active = ['active' => ! config('rinvex.fort.registration.moderated')];

        // Fire the register start event
        event('rinvex.fort.register.start', [$input + $active]);

        $user->fill($input + $active)->save();

        // Attach default role to the registered user
        if ($defaultRole = config('rinvex.fort.registration.default_role')) {
            if ($defaultRole = Role::where('slug', $defaultRole)->first()) {
                $user->roles()->attach($defaultRole);
            }
        }

        // Fire the register success event
        event('rinvex.fort.register.success', [$user]);

        // Send verification if required
        if (config('rinvex.fort.emailverification.required')) {
            app('rinvex.fort.emailverification')->broker()->sendVerificationLink(['email' => $input['email']]);

            // Registration completed, verification required
            return intend([
                'intended' => url('/'),
                'with' => ['success' => trans('cortex/fort::messages.register.success_verify')],
            ]);
        }

        // Registration completed successfully
        return intend([
            'url' => route('frontend.auth.login'),
            'with' => ['success' => trans('cortex/fort::messages.register.success')],
        ]);
    }

    /**
     * Return redirect response.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function redirect()
    {
        return intend([
            'back' => true,
            'withErrors' => ['rinvex.fort.registration.disabled' => trans('cortex/fort::messages.register.disabled')],
        ]);
    }
}
