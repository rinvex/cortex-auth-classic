<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Userarea;

use Carbon\Carbon;
use PragmaRX\Google2FA\Google2FA;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpSettingsRequest;
use Cortex\Fort\Http\Requests\Userarea\TwoFactorPhoneSettingsRequest;
use Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpBackupSettingsRequest;
use Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpProcessSettingsRequest;

class TwoFactorSettingsController extends AuthenticatedController
{
    /**
     * Enable TwoFactor TOTP authentication.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpSettingsRequest $request
     * @param \PragmaRX\Google2FA\Google2FA                                    $totpProvider
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function enableTotp(TwoFactorTotpSettingsRequest $request, Google2FA $totpProvider)
    {
        $currentUser = $request->user($this->getGuard());
        $twoFactor = $currentUser->getTwoFactor();

        if (! $secret = array_get($twoFactor, 'totp.secret')) {
            $twoFactor['totp'] = [
                'enabled' => false,
                'secret' => $secret = $totpProvider->generateSecretKey(),
            ];

            $currentUser->fill(['two_factor' => $twoFactor])->forceSave();
        }

        $qrCode = $totpProvider->getQRCodeInline(config('app.name'), $currentUser->email, $secret);

        return view('cortex/fort::userarea.forms.twofactor', compact('secret', 'qrCode', 'twoFactor'));
    }

    /**
     * Disable TwoFactor TOTP authentication.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disableTotp(TwoFactorTotpSettingsRequest $request)
    {
        $currentUser = $request->user($this->getGuard());
        $twoFactor = $currentUser->getTwoFactor();
        $twoFactor['totp'] = [];

        $currentUser->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'url' => route('userarea.account.settings'),
            'with' => ['success' => trans('cortex/fort::messages.verification.twofactor.totp.disabled')],
        ]);
    }

    /**
     * Process the TwoFactor TOTP enable form.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpProcessSettingsRequest $request
     * @param \PragmaRX\Google2FA\Google2FA                                           $totpProvider
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateTotp(TwoFactorTotpProcessSettingsRequest $request, Google2FA $totpProvider)
    {
        $currentUser = $request->user($this->getGuard());
        $twoFactor = $currentUser->getTwoFactor();
        $secret = array_get($twoFactor, 'totp.secret');
        $backup = array_get($twoFactor, 'totp.backup');
        $backupAt = array_get($twoFactor, 'totp.backup_at');

        if ($totpProvider->verifyKey($secret, $request->get('token'))) {
            $twoFactor['totp'] = [
                'enabled' => true,
                'secret' => $secret,
                'backup' => $backup ?? $this->generateTotpBackups(),
                'backup_at' => $backupAt ?? (new Carbon())->toDateTimeString(),
            ];

            // Update TwoFactor settings
            $currentUser->fill(['two_factor' => $twoFactor])->forceSave();

            return intend([
                'back' => true,
                'with' => ['success' => trans('cortex/fort::messages.verification.twofactor.totp.enabled')],
            ]);
        }

        return intend([
            'back' => true,
            'withErrors' => ['token' => trans('cortex/fort::messages.verification.twofactor.totp.invalid_token')],
        ]);
    }

    /**
     * Process the TwoFactor OTP backup.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorTotpBackupSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function backupTotp(TwoFactorTotpBackupSettingsRequest $request)
    {
        $currentUser = $request->user($this->getGuard());
        $twoFactor = $currentUser->getTwoFactor();
        $twoFactor['totp']['backup'] = $this->generateTotpBackups();
        $twoFactor['totp']['backup_at'] = (new Carbon())->toDateTimeString();

        $currentUser->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.verification.twofactor.totp.rebackup')],
        ]);
    }

    /**
     * Enable TwoFactor Phone authentication.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorPhoneSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function enablePhone(TwoFactorPhoneSettingsRequest $request)
    {
        $currentUser = $request->user($this->getGuard());
        $currentUser->routeNotificationForAuthy();
        $twoFactor = $currentUser->getTwoFactor();
        $twoFactor['phone']['enabled'] = true;

        $currentUser->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'url' => route('userarea.account.settings'),
            'with' => ['success' => trans('cortex/fort::messages.verification.twofactor.phone.enabled')],
        ]);
    }

    /**
     * Disable TwoFactor Phone authentication.
     *
     * @param \Cortex\Fort\Http\Requests\Userarea\TwoFactorPhoneSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disablePhone(TwoFactorPhoneSettingsRequest $request)
    {
        $currentUser = $request->user($this->getGuard());
        $twoFactor = $currentUser->getTwoFactor();
        $twoFactor['phone']['enabled'] = false;

        $currentUser->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'url' => route('userarea.account.settings'),
            'with' => ['success' => trans('cortex/fort::messages.verification.twofactor.phone.disabled')],
        ]);
    }

    /**
     * Generate TwoFactor OTP backup codes.
     *
     * @return array
     */
    protected function generateTotpBackups()
    {
        $backup = [];

        for ($x = 0; $x <= 9; $x++) {
            $backup[] = str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_BOTH);
        }

        return $backup;
    }
}
