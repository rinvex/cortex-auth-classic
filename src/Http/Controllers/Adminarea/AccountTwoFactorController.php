<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PragmaRX\Google2FA\Google2FA;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorPhoneRequest;
use Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorTotpBackupRequest;
use Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorTotpProcessRequest;

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
        $twoFactor = $request->user()->getTwoFactor();

        return view('cortex/auth::adminarea.pages.account-twofactor', compact('twoFactor'));
    }

    /**
     * Enable TwoFactor TOTP authentication.
     *
     * @param \Illuminate\Http\Request      $request
     * @param \PragmaRX\Google2FA\Google2FA $totpProvider
     *
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     *
     * @return \Illuminate\View\View
     */
    public function enableTotp(Request $request, Google2FA $totpProvider)
    {
        $twoFactor = $request->user()->getTwoFactor();

        if (! $secret = Arr::get($twoFactor, 'totp.secret')) {
            $twoFactor['totp'] = [
                'enabled' => false,
                'secret' => $secret = $totpProvider->generateSecretKey(),
            ];

            $request->user()->fill(['two_factor' => $twoFactor])->forceSave();
        }

        $qrCode = $totpProvider->getQRCodeInline(config('app.name'), $request->user()->email, $secret);

        return view('cortex/auth::adminarea.pages.account-twofactor-totp', compact('secret', 'qrCode', 'twoFactor'));
    }

    /**
     * Disable TwoFactor TOTP authentication.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disableTotp(Request $request)
    {
        $twoFactor = $request->user()->getTwoFactor();
        $twoFactor['totp'] = [];

        $request->user()->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.totp.disabled')],
        ]);
    }

    /**
     * Process the TwoFactor TOTP enable form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorTotpProcessRequest $request
     * @param \PragmaRX\Google2FA\Google2FA                                           $totpProvider
     *
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateTotp(AccountTwoFactorTotpProcessRequest $request, Google2FA $totpProvider)
    {
        $twoFactor = $request->user()->getTwoFactor();
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
            $request->user()->fill(['two_factor' => $twoFactor])->forceSave();

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
     * @param \Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorTotpBackupRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function backupTotp(AccountTwoFactorTotpBackupRequest $request)
    {
        $twoFactor = $request->user()->getTwoFactor();
        $twoFactor['totp']['backup'] = $this->generateTotpBackups();
        $twoFactor['totp']['backup_at'] = Carbon::now()->toDateTimeString();

        $request->user()->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.totp.rebackup')],
        ]);
    }

    /**
     * Enable TwoFactor Phone authentication.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AccountTwoFactorPhoneRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function enablePhone(AccountTwoFactorPhoneRequest $request)
    {
        $request->user()->routeNotificationForAuthy();
        $twoFactor = $request->user()->getTwoFactor();
        $twoFactor['phone']['enabled'] = true;

        $request->user()->fill(['two_factor' => $twoFactor])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.verification.twofactor.phone.enabled')],
        ]);
    }

    /**
     * Disable TwoFactor Phone authentication.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function disablePhone(Request $request)
    {
        $twoFactor = $request->user()->getTwoFactor();
        $twoFactor['phone']['enabled'] = false;

        $request->user()->fill(['two_factor' => $twoFactor])->forceSave();

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
            $backup[] = mb_str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_BOTH);
        }

        return $backup;
    }
}
