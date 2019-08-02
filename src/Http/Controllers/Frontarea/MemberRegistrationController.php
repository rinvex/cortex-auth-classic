<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Cortex\Auth\Models\Member;
use Illuminate\Auth\Events\Registered;
use Cortex\Auth\Http\Requests\Frontarea\MemberRegistrationRequest;
use Cortex\Auth\Http\Requests\Frontarea\MemberRegistrationProcessRequest;

class MemberRegistrationController extends RegistrationController
{
    /**
     * Show the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\MemberRegistrationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function form(MemberRegistrationRequest $request)
    {
        return view('cortex/auth::frontarea.pages.member-registration');
    }

    /**
     * Process the registration form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\MemberRegistrationProcessRequest $request
     * @param \Cortex\Auth\Models\Member                                            $member
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function register(MemberRegistrationProcessRequest $request, Member $member)
    {
        // Prepare registration data
        $data = $request->validated();

        $member->fill($data)->save();

        // Fire the register success event
        event(new Registered($member));

        // Send verification if required
        ! config('cortex.auth.emails.verification')
        || app('rinvex.auth.emailverification')->broker($this->getEmailVerificationBroker())->sendVerificationLink(['email' => $member->email]);

        // Auto-login registered member
        auth()->guard('members')->login($member);

        // Registration completed successfully
        return intend([
            'intended' => route('frontarea.home'),
            'with' => ['success' => trans('cortex/auth::messages.register.success')],
        ]);
    }
}
