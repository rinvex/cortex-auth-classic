<?php

declare(strict_types=1);

return [

    'error' => '<strong>Whoops!</strong> There were some problems with your input.',
    'unauthorized' => 'You are not authorized for this action!',
    'unauthenticated' => 'You are not authenticated for this action!',
    'authenticated' => 'You are already authenticated!',
    'password_required' => 'Password confirmation required.',
    'lockout' => 'Too many attempts. Please try again in :seconds seconds.',

    'socialite' => [
        'disabled' => 'Sorry, social authentication is currently disabled!',
        'not_supported' => 'Sorry, :provider authentication is currently not supported!',
    ],

    'sessions' => [
        'flush_single_heading' => 'Flush Selected Session',
        'flush_single_body' => 'Selected session will be flushed, and thus re-login again will be required on effected device.',
        'flush_all_heading' => 'Log out all other sessions',
        'flush_all_body' => 'This will end all of your other active sessions. It won’t affect your current session.',
        'delete_other_othres' => 'This will end all of your other active sessions. It won’t affect your current session.',
    ],

    'register' => [
        'success' => 'Registration completed successfully!',
        'disabled' => 'Sorry, registration is currently disabled!',
    ],

    'account' => [
        'phone_field_required' => 'You must enter your phone first!',
        'phone_verification_required' => 'You must verify your phone first!',
        'email_verification_required' => 'You verify your email address first!',
        'wrong_password' => 'Sorry, your old password must match your current password!',
        'different_password' => 'Sorry, your new password must not match your current password!',
        'country_required' => 'You must select your country first!',
        'phone_required' => 'You must update your phone first!',
        'updated_password' => 'You have successfully updated your password!',
        'updated_account' => 'You have successfully updated your account!',
        'updated_timezone' => 'Your account timezone has been automatically updated successfully to :timezone!',
    ],

    'auth' => [
        'moderated' => 'Your account is currently moderated!',
        'unverified' => 'Your account in currently unverified!',
        'failed' => 'These credentials do not match our records.',
        'password' => 'The provided password is incorrect.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'login' => 'You have successfully logged in!',
        'logout' => 'You have successfully logged out!',
        'session' => [
            'flushed' => 'All your active sessions has been successfully flushed!',
        ],
    ],

    'passwordreset' => [
        'already_logged' => 'You are logged in, so you can change password from your account settings.',
    ],

    'verification' => [

        'email' => [
            'expired_token' => 'This email verification link is expired, please request a new one.',
            'already_verified' => 'Your email already verified!',
            'verified' => 'Your email has been verified!',
            'link_sent' => 'We have e-mailed your verification link!',
            'invalid_token' => 'This email verification link is invalid or has been tampered!',
            'invalid_user' => 'We can not find a user with that email address.',
        ],

        'phone' => [
            'verified' => 'Your phone has been verified!',
            'already_verified' => 'Your phone already verified!',
            'sent' => 'We have sent your verification token to your phone!',
            'failed' => 'Weird problem happen while verifying your phone, this issue has been logged and reported to staff.',
            'invalid_user' => 'We can not find a user with that email address.',
        ],

        'twofactor' => [
            'already_logged' => 'You are logged in, so you can control TwoFactor from your account settings.',
            'invalid_token' => 'This verification token is invalid.',
            'totp' => [
                'required' => 'TwoFactor TOTP authentication enabled for your account, authentication code required to proceed.',
                'enabled' => 'TwoFactor TOTP authentication has been enabled and backup codes generated for your account.',
                'disabled' => 'TwoFactor TOTP authentication has been disabled for your account.',
                'rebackup' => 'TwoFactor TOTP authentication backup codes re-generated for your account.',
                'cant_backup' => 'TwoFactor TOTP authentication currently disabled for your account, thus backup codes can not be generated.',
                'invalid_token' => 'Your passcode did not match, or expired after scanning. Remove the old barcode from your app, and try again. Since this process is time-sensitive, make sure your device\'s date and time is set to "automatic."',
            ],
            'phone' => [
                'enabled' => 'TwoFactor phone authentication has been enabled for your account.',
                'disabled' => 'TwoFactor phone authentication has been disabled for your account.',
                'auto_disabled' => 'TwoFactor phone authentication has been disabled for your account. Changing country or phone results in TwoFactor auto disable. You need to enable it again manually.',
                'phone_required' => 'Phone field seems to be missing in your account, and since TwoFactor authentication already activated which require that field, you can NOT login unfortunately. Please contact staff to solve this issue.',
                'country_required' => 'Country field seems to be missing in your account, and since TwoFactor authentication already activated which require that field, you can NOT login unfortunately. Please contact staff to solve this issue.',
            ],
        ],

    ],

];
