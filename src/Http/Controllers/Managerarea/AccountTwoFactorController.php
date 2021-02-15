<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorPhoneRequest;
use Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorTotpBackupRequest;
use Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorTotpProcessRequest;

class AccountTwoFactorController extends AuthenticatedController
{
    /**
     * Show the account security form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $twoFactor = $request->user(app('request.guard'))->getTwoFactor();

        return view('cortex/auth::managerarea.pages.account-twofactor', compact('twoFactor'));
    }

    /**
     * Enable TwoFactor TOTP authentication.
     *
     * @param \PragmaRX\Google2FA\Google2FA $totpProvider
     *
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     *
     * @return \Illuminate\View\View
     */
    public function enableTotp(Google2FA $totpProvider)
    {
        $twoFactor = app('request.user')->getTwoFactor();

        if (! $secret = Arr::get($twoFactor, 'totp.secret')) {
            $twoFactor['totp'] = [
                'enabled' => false,
                'secret' => $secret = $totpProvider->generateSecretKey(),
            ];

            app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();
        }

        $qrCode = $totpProvider->getQRCodeInline(config('app.name'), app('request.user')->email, $secret);

        return view('cortex/auth::managerarea.pages.account-twofactor-totp', compact('secret', 'qrCode', 'twoFactor'));
    }

    /**
     * Disable TwoFactor TOTP authentication.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disableTotp()
    {
        $twoFactor = app('request.user')->getTwoFactor();
        $twoFactor['totp'] = [];

        app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.totp.disabled')],
        ]);
    }

    /**
     * Process the TwoFactor TOTP enable form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorTotpProcessRequest $request
     * @param \PragmaRX\Google2FA\Google2FA                                             $totpProvider
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateTotp(AccountTwoFactorTotpProcessRequest $request, Google2FA $totpProvider)
    {
        $twoFactor = app('request.user')->getTwoFactor();
        $secret = Arr::get($twoFactor, 'totp.secret');
        $backup = Arr::get($twoFactor, 'totp.backup');
        $backupAt = Arr::get($twoFactor, 'totp.backup_at');

        if ($totpProvider->verifyKey($secret, $request->input('token'))) {
            $twoFactor['totp'] = [
                'enabled' => true,
                'secret' => $secret,
                'backup' => $backup ?? $this->generateTotpBackups(),
                'backup_at' => $backupAt ?? Carbon::now()->toDateTimeString(),
            ];

            // Update TwoFactor settings
            app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();

            return intend([
                'back' => true,
                'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.totp.enabled')],
            ]);
        }

        return intend([
            'back' => true,
            'withErrors' => ['token' => trans('cortex/auth::messages.verification.twofactor.totp.invalid_token')],
        ]);
    }

    /**
     * Process the TwoFactor OTP backup.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorTotpBackupRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function backupTotp(AccountTwoFactorTotpBackupRequest $request)
    {
        $twoFactor = app('request.user')->getTwoFactor();
        $twoFactor['totp']['backup'] = $this->generateTotpBackups();
        $twoFactor['totp']['backup_at'] = Carbon::now()->toDateTimeString();

        app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.totp.rebackup')],
        ]);
    }

    /**
     * Enable TwoFactor Phone authentication.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\AccountTwoFactorPhoneRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function enablePhone(AccountTwoFactorPhoneRequest $request)
    {
        app('request.user')->routeNotificationForAuthy();
        $twoFactor = app('request.user')->getTwoFactor();
        $twoFactor['phone']['enabled'] = true;

        app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.phone.enabled')],
        ]);
    }

    /**
     * Disable TwoFactor Phone authentication.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disablePhone()
    {
        $twoFactor = app('request.user')->getTwoFactor();
        $twoFactor['phone']['enabled'] = false;

        app('request.user')->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.phone.disabled')],
        ]);
    }

    /**
     * Generate TwoFactor OTP backup codes.
     *
     * @return array
     */
    protected function generateTotpBackups(): array
    {
        $backup = [];

        for ($x = 0; $x <= 9; $x++) {
            $backup[] = str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_BOTH);
        }

        return $backup;
    }
}
