<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Cortex\Foundation\Exceptions\GenericException;

class PhoneVerificationSendRequest extends PhoneVerificationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        parent::authorize();

        $user = $this->user();
        $attemptUser = auth()->attemptUser();

        if ($user && ! $user->country_code) {
            // Country field required for phone verification
            throw new GenericException(trans('cortex/fort::messages.account.country_required'), route('userarea.account.settings'));
        }

        if ($user && ! $user->phone) {
            // Phone field required before verification
            throw new GenericException(trans('cortex/fort::messages.account.phone_required'), route('userarea.account.settings'));
        }

        if ($attemptUser && ! $attemptUser->country_code) {
            // Country field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.country_required'), route('frontend.home'));
        }

        if ($attemptUser && ! $attemptUser->phone) {
            // Phone field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.phone_required'), route('frontend.home'));
        }

        if (! in_array('phone', config('rinvex.fort.twofactor.providers'))) {
            // Country required for phone verification
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.disabled'), route('userarea.account.settings'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
