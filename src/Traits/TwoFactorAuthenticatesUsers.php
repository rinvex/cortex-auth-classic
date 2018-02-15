<?php

declare(strict_types=1);

namespace Cortex\Fort\Traits;

use PragmaRX\Google2FA\Google2FA;
use Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract;

trait TwoFactorAuthenticatesUsers
{
    /**
     * Verify TwoFactor authentication.
     *
     * @param \Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract $user
     * @param int                                                     $token
     *
     * @return bool
     */
    protected function attemptTwoFactor(AuthenticatableTwoFactorContract $user, int $token): bool
    {
        return $this->isValidTwoFactorTotp($user, $token) || $this->isValidTwoFactorBackup($user, $token) || $this->isValidTwoFactorPhone($user, $token);
    }

    /**
     * Invalidate given backup code for the given user.
     *
     * @param \Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract $user
     * @param int                                                     $token
     *
     * @return void
     */
    protected function invalidateTwoFactorBackup(AuthenticatableTwoFactorContract $user, int $token): void
    {
        $settings = $user->getTwoFactor();
        $backup = array_get($settings, 'totp.backup');

        unset($backup[array_search($token, $backup)]);

        array_set($settings, 'totp.backup', $backup);

        // Update TwoFactor OTP backup codes
        $user->fill(['two_factor' => $settings])->forceSave();
    }

    /**
     * Determine if the given token is a valid TwoFactor Phone token.
     *
     * @param \Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract $user
     * @param int                                                     $token
     *
     * @return bool
     */
    protected function isValidTwoFactorPhone(AuthenticatableTwoFactorContract $user, int $token): bool
    {
        $settings = $user->getTwoFactor();
        $authyId = array_get($settings, 'phone.authy_id');

        return in_array(mb_strlen($token), [6, 7, 8]) && app('rinvex.authy.token')->verify($token, $authyId)->succeed();
    }

    /**
     * Determine if the given token is a valid TwoFactor Backup code.
     *
     * @param \Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract $user
     * @param int                                                     $token
     *
     * @return bool
     */
    protected function isValidTwoFactorBackup(AuthenticatableTwoFactorContract $user, int $token): bool
    {
        $backup = array_get($user->getTwoFactor(), 'totp.backup', []);
        $result = mb_strlen($token) === 10 && in_array($token, $backup);
        ! $result || $this->invalidateTwoFactorBackup($user, $token);

        return $result;
    }

    /**
     * Determine if the given token is a valid TwoFactor TOTP token.
     *
     * @param \Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract $user
     * @param int                                                     $token
     *
     * @return bool
     */
    protected function isValidTwoFactorTotp(AuthenticatableTwoFactorContract $user, int $token): bool
    {
        $totpProvider = app(Google2FA::class);
        $secret = array_get($user->getTwoFactor(), 'totp.secret');

        return mb_strlen($token) === 6 && $totpProvider->verifyKey($secret, $token);
    }
}
