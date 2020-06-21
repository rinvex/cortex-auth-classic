# Cortex Auth Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v6.0.3] - 2020-06-21
- Check if container service is bound or not before using

## [v6.0.2] - 2020-06-20
- Move macroable logic to rinvex/laravel-support package

## [v6.0.1] - 2020-06-19
- Fix request.guard binding issue when running in console
- Drop rinvex/laravel-attributes reference as it's no longer required by this package

## [v6.0.0] - 2020-06-19
- Refactor active tenant to container service binding, instead of runtime config value
- Refactor route parameters to container service binding
- Stick to composer version constraints recommendations and ease minimum required version of modules

## [v5.2.0] - 2020-06-15
- Autoload config, views, language, menus, breadcrumbs, and migrations
  - This is now done automatically through cortex/foundation, so no need to manually wire it here anymore
- Merge additional fillable, casts, and rules instead of overriding
- Drop PHP 7.2 & 7.3 support from travis

## [v5.1.1] - 2020-05-30
- Update composer dependencies

## [v5.1.0] - 2020-05-30
- With the significance of recent updates, new minor release required

## [v5.0.8] - 2020-05-30
- Refactor datatables query() method override to use parent::query()
- Add datatables checkbox column for bulk actions
- Use getRouteKey() attribute for all redirect identifiers
- Drop using strip_tags on redirect identifiers as they will use ->getRouteKey() which is already safe
- Fix wrong container service names
- Add support for datatable listing get and post requests
- Refactor model CRUD dispatched events
- Add bulk action routes
- Remove useless "DT_RowId" column from transformers
- Register channel broadcasting routes
- Add broadcasting authentication route
- Add listener queues
- Fire custom model events from CRUD actions
- Explicitly specify relationship attributes
- Fix tags query
- Rename datatables container names
- Load module routes automatically
- Strip tags breadcrumbs of potential user inputs
- Apply fixes from StyleCI (#120)
- Strip tags of language phrase parameters with potential user inputs
- Escape language phrases
- Add strip_tags validation rule to string fields
- Remove default indent size config
- Fix compatibility with recent rinvex/laravel-menus package update

## [v5.0.7] - 2020-04-12
- Fix ServiceProvider registerCommands method compatibility

## [v5.0.6] - 2020-04-09
- Tweak artisan command registration
- Add missing config publishing command
- Refactor publish command and allow multiple resource values
- Reverse commit "Convert database int fields into bigInteger"

## [v5.0.5] - 2020-04-04
- Enforce consistent artisan command tag namespacing
- Enforce consistent package namespace
- Drop laravel/helpers usage as it's no longer used
- Upgrade silber/bouncer composer package

## [v5.0.4] - 2020-03-20
- Add shortcut -f (force) for artisan publish commands
- Fix migrations path condition
- Convert database int fields into bigInteger
- Upgrade spatie/laravel-medialibrary to v8.x
- Fix couple issues and enforce consistency

## [v5.0.3] - 2020-03-16
- Update proengsoft/laravel-jsvalidation composer package

## [v5.0.2] - 2020-03-15
- Fix incompatible package version league/fractal

## [v5.0.1] - 2020-03-15
- Fix wrong package version laravelcollective/html

## [v5.0.0] - 2020-03-15
- Upgrade to Laravel v7.1.x & PHP v7.4.x

## [v4.1.3] - 2020-03-13
- Tweak TravisCI config
- Refactor session management and flush process
- Tweak logout process
-  Patch AuthenticateSession: The middleware needs to call `Auth::logoutCurrentDevice` instead of `Auth::logout` to avoid rotate the remember_token
  + https://github.com/laravel/framework/commit/1f5ec13c0e229db8f70f7b560b57a5ce1d0777d0#diff-0539afd6b48ba531ec1873776b3d1d85
- Add migrations autoload option to the package
- Tweak service provider `publishesResources` & `autoloadMigrations`
- Update StyleCI config
- Drop using global helpers
- Check if ability exists before seeding

## [v4.1.2] - 2019-12-18
- Fix route regex pattern to include underscores
  - This way it's compatible with validation rule `alpha_dash`
- Fix `migrate:reset` args as it doesn't accept --step
- Refactor events and event listeners

## [v4.1.1] - 2019-12-04
- Add ajax filters capabilities to datatables (admins, managers, members)
- Add DT_RowId field to datatables
- Fix undefined phone_verified_at attribute and file size validation rule

## [v4.1.0] - 2019-11-23
- change account display name as the two children account and settings have the same name (#87)
- Refactor Reauthentication feature to be compatible with the new Laravel v6.2 feature
  https://laravel-news.com/new-password-confirmation-in-laravel-6-2
- Update Reauthenticate middleware to be compatible with Laravel v6.3
  Use contracts for the RequirePassword middleware (#30215)
  https://github.com/laravel/framework/commit/53b64719d6fb398bfc2aa2baf121e887d21b7aea
- Laravel v6.4.x / Handle ajax requests in RequirePassword middleware (#30390, 331c354)
  https://blog.laravel.com/laravel-v6-4-0-released
  https://github.com/laravel/framework/pull/30390
  https://github.com/laravel/framework/commit/331c354e586a5a27a9edc9b9a49d23aa872e4b32
- Move "Remember previous URL for later redirect back" to exception handler
- Refactor Login Throttle to use "ThrottleRequests" middleware

## [v4.0.5] - 2019-10-14
- Update menus & breadcrumbs event listener to accessarea.ready
- Fix wrong dependencies letter case

## [v4.0.4] - 2019-10-06
- Refactor menus and breadcrumb bindings to utilize event dispatcher
- Drop wrong useless adminarea.register route

## [v4.0.3] - 2019-09-24
- Add missing laravel/helpers composer package

## [v4.0.2] - 2019-09-23
- Fix outdated package version

## [v4.0.1] - 2019-09-23
- Fix outdated package version

## [v4.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

## [v3.0.6] - 2019-09-03
- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator
- Fix size validation rule

## [v3.0.5] - 2019-09-03
- Fix wrong hardcoded members guard name

## [v3.0.4] - 2019-09-03
- Enforce profile_picture and cover_photo image validation rules & update media config
- Because session last_activity store timestamp we should comapre by time function instead of now (#82)
- Fix redirection routes for different accessareas
- Use $_SERVER instead of $_ENV for PHPUnit
- Remove new password validation from broker
- Update HttpKernel to use Authenticate middleware under App namespace

## [v3.0.3] - 2019-08-03
- Tweak menus & breadcrumbs performance
- Fix menu issues

## [v3.0.2] - 2019-08-03
- Update composer dependencies

## [v3.0.1] - 2019-08-03
- Upgrade rinvex/laravel-auth package

## [v3.0.0] - 2019-08-03
- Upgrade composer dependencies
- Upgrade socialite to v4 (#77)
- Override AuthenticateSession middleware to support multi-guard authentication
- Merge cortex/auth-b2b2c2 into this core cortex/auth module

## [v2.1.3] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.2] - 2019-06-03
- Update publish commands to support both packages and modules natively

## [v2.1.1] - 2019-06-02
- Fix yajra/laravel-datatables-fractal and league/fractal compatibility

## [v2.1.0] - 2019-06-02
- Fix twofactor route
- Update composer deps
- Drop PHP 7.1 travis test
- Add settings to managerarea menu
- Refactor migrations and artisan commands, and tweak service provider publishes functionality

## [v2.0.0] - 2019-03-03
- Require PHP 7.2 & Laravel 5.8
- Utilize includeWhen blade directive
- Replace __ CLASS __ with self::class (potentially deprecated in PHP 7.4)
- Fix json/array casting type
- Add files option to the form to allow file upload
- Fix wrong authorization check method for superadmins
- Refactor abilities seeding
- Refactor managed roles/abilities retrieval
- Drop role Tenantability, and use bouncer native scopes features
- Refactor isManager & isSupermanager methods
- Drop ownership feature of tenants

## [v1.0.6] - 2019-01-04
- Drop member & manager seeds

## [v1.0.5] - 2019-01-03
- Add settings link to logged-in user menu
- Seed member & manager accounts
- Force save password regardless of any other fields validation errors
  - This action only deal with password change anyway.
- Simplify and flatten create & edit form controller actions
- Tweak and simplify FormRequest validations

## [v1.0.4] - 2018-12-25
- Rename environment variable QUEUE_DRIVER to QUEUE_CONNECTION
- Fix wrong media destroy route
- Fix cortex:seed:auth

## [v1.0.3] - 2018-12-23
- Apply spatie/laravel-activitylog updates
- Fix macroable recursive calls

## [v1.0.2] - 2018-12-23
- Fix wrong pragmarx/google2fa dependency version

## [v1.0.1] - 2018-12-22
- Update composer dependencies
- Add PHP 7.3 support to travis

## [v1.0.0] - 2018-10-01
- Support Laravel v5.7, bump versions and enforce consistency

## [v0.0.3] - 2018-09-22
- Too much changes to list here!!

## [v0.0.2] - 2018-02-17
- Complete package refactor!

## v0.0.1 - 2017-03-13
- Tag first release

[v6.0.3]: https://github.com/rinvex/cortex-auth/compare/v6.0.2...v6.0.3
[v6.0.2]: https://github.com/rinvex/cortex-auth/compare/v6.0.1...v6.0.2
[v6.0.1]: https://github.com/rinvex/cortex-auth/compare/v6.0.0...v6.0.1
[v6.0.0]: https://github.com/rinvex/cortex-auth/compare/v5.2.0...v6.0.0
[v5.2.0]: https://github.com/rinvex/cortex-auth/compare/v5.1.1...v5.2.0
[v5.1.1]: https://github.com/rinvex/cortex-auth/compare/v5.1.0...v5.1.1
[v5.1.0]: https://github.com/rinvex/cortex-auth/compare/v5.0.8...v5.1.0
[v5.0.8]: https://github.com/rinvex/cortex-auth/compare/v5.0.7...v5.0.8
[v5.0.7]: https://github.com/rinvex/cortex-auth/compare/v5.0.6...v5.0.7
[v5.0.6]: https://github.com/rinvex/cortex-auth/compare/v5.0.5...v5.0.6
[v5.0.5]: https://github.com/rinvex/cortex-auth/compare/v5.0.4...v5.0.5
[v5.0.4]: https://github.com/rinvex/cortex-auth/compare/v5.0.3...v5.0.4
[v5.0.3]: https://github.com/rinvex/cortex-auth/compare/v5.0.2...v5.0.3
[v5.0.2]: https://github.com/rinvex/cortex-auth/compare/v5.0.1...v5.0.2
[v5.0.1]: https://github.com/rinvex/cortex-auth/compare/v5.0.0...v5.0.1
[v5.0.0]: https://github.com/rinvex/cortex-auth/compare/v4.1.3...v5.0.0
[v4.1.3]: https://github.com/rinvex/cortex-auth/compare/v4.1.2...v4.1.3
[v4.1.2]: https://github.com/rinvex/cortex-auth/compare/v4.1.1...v4.1.2
[v4.1.1]: https://github.com/rinvex/cortex-auth/compare/v4.1.0...v4.1.1
[v4.1.0]: https://github.com/rinvex/cortex-auth/compare/v4.0.5...v4.1.0
[v4.0.5]: https://github.com/rinvex/cortex-auth/compare/v4.0.4...v4.0.5
[v4.0.4]: https://github.com/rinvex/cortex-auth/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/rinvex/cortex-auth/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/rinvex/cortex-auth/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/rinvex/cortex-auth/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/rinvex/cortex-auth/compare/v3.0.6...v4.0.0
[v3.0.6]: https://github.com/rinvex/cortex-auth/compare/v3.0.5...v3.0.6
[v3.0.5]: https://github.com/rinvex/cortex-auth/compare/v3.0.4...v3.0.5
[v3.0.4]: https://github.com/rinvex/cortex-auth/compare/v3.0.3...v3.0.4
[v3.0.3]: https://github.com/rinvex/cortex-auth/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/rinvex/cortex-auth/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/rinvex/cortex-auth/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/cortex-auth/compare/v2.1.2...v3.0.0
[v2.1.2]: https://github.com/rinvex/cortex-auth/compare/v2.1.1...v2.1.2
[v2.1.1]: https://github.com/rinvex/cortex-auth/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/cortex-auth/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/cortex-auth/compare/v1.0.6...v2.0.0
[v1.0.6]: https://github.com/rinvex/cortex-auth/compare/v1.0.5...v1.0.6
[v1.0.5]: https://github.com/rinvex/cortex-auth/compare/v1.0.4...v1.0.5
[v1.0.4]: https://github.com/rinvex/cortex-auth/compare/v1.0.3...v1.0.4
[v1.0.3]: https://github.com/rinvex/cortex-auth/compare/v1.0.2...v1.0.3
[v1.0.2]: https://github.com/rinvex/cortex-auth/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/rinvex/cortex-auth/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/cortex-auth/compare/v0.0.2...v1.0.0
[v0.0.2]: https://github.com/rinvex/cortex-auth/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/cortex-auth/compare/v0.0.1...v0.0.2
