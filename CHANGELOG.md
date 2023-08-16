# Cortex Auth Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v9.2.6] - 2023-08-16
- Replace unique rule validation with unique_with
  - fix different scenario issues on model create, and model update

## [v9.2.5] - 2023-07-31
- Move username, email, and password validation rules to config file
- Update and tweak password validation rule
- Fix user password confirmed validation rule to work only in form requests, but not in model saving validation

## [v9.2.4] - 2023-07-12
- Drop using attributes

## [v9.2.3] - 2023-07-03
- Update composer dependencies
- Drop using Rinvex\Oauth\Traits\HasApiTokens
- Replace Carbon\Carbon with Illuminate\Support\Carbon

## [v9.2.2] - 2023-07-03
- Fix migration paths

## [v9.2.1] - 2023-07-02
- Remove ScopeBouncer::class call from cortex/auth

## [v9.2.0] - 2023-06-23
- Update auth adminarea menu order
- Update member model validation rules
- Split tenancy features to a separate module extension
- Fix cortex/auth::common.timezone language phrase namespace
- Apply fixes from StyleCI (#252)
- Move tenant features to cortex/tenants module from cortex/foundation
- Check if IoC service container has `request.tenant` registered first before using
- Remove useless condition on member account sidebar
- Add managerarea sidebar menus for members & managers
- Fix changelog format

## [v9.1.0] - 2023-05-02
- Add support for Laravel v11, and drop support for Laravel v9
- Upgrade yajra/laravel-datatables-oracle to v10.4 from v10.0
- Upgrade yajra/laravel-datatables-html to v10.0 from v9.0
- Upgrade yajra/laravel-datatables-buttons to v10.0 from v9.0
- Upgrade spatie/laravel-schemaless-attributes to v2.4 from v2.3
- Upgrade spatie/laravel-activitylog to v4.7 from v4.4
- Upgrade proengsoft/laravel-jsvalidation to v4.8 from v4.7
- Upgrade laravel/socialite to v5.6 from v5.5
- Update yajra/laravel-datatables-fractal to v10.0 from v9.0
- Update propaganistas/laravel-phone to v5.0 from v4.4
- Update phpunit to v10.1 from v9.5
- Fix tenant name

## [v9.0.0] - 2023-01-09
- Drop PHP v8.0 support and update composer dependencies
- Utilize PHP 8.1 attributes feature for artisan commands
- Remove useless attributes routes

## [v8.3.11] - 2022-12-30
- Whitelist datatable columns to avoid invalid columns sent from client-side which might be a security issue in some scenarios

## [v8.3.10] - 2022-10-03
- Move auth middleware registration to cortex/auth from cortex/foundation
- Extend the default laravel AuthenticateSession middleware and simply code
- Sync with the latest Laravel updates
- Move SetAuthDefaults middleware to cortex/auth from cortex/foundation

## [v8.3.9] - 2022-09-06
- Tweak entity types list 
- Use configurable model names instead of custom ones

## [v8.3.8] - 2022-08-30
- Replace hardcoded models with IoC container services
- Update exists and unique validation rules to use models instead of tables
- Remove all rinvex attributes dead code
- Apply tenancy on member model
- Clean the breadcrumbs definition and utilize parent features

## [v8.3.7] - 2022-07-24
- Fix datatables checkbox select-row options
- Fix audit ability check for import logs
- Add missing export ability

## [v8.3.6] - 2022-07-02
- Fix daterangepicker input field type to text instead of date to fix the picker issues

## [v8.3.5] - 2022-06-22
- Fix datatables ajax method signature

## [v8.3.4] - 2022-06-20
- Add support for list item attributes
- Update composer dependencies
  - league/fractal to ^0.20.0 from ^0.19.0
  - yajra/laravel-datatables-html to ^9.0.0 from ^4.41.0
  - yajra/laravel-datatables-fractal to ^9.0.0 from ^1.6.0
  - yajra/laravel-datatables-buttons to ^9.0.0 from ^4.13.0
  - yajra/laravel-datatables-oracle to ^10.0.0 from ^9.19.0

## [v8.3.3] - 2022-05-17
- Add support for menu list item attributes
- Fix correct naming for daterangepicker from datepicker
- Fix reset password missing expiration token issue
- Override Spatie Media model to support Hashids

## [v8.3.2] - 2022-05-01
- use today instead of today_date (#216)
- Require rfc & dns email validation rules
- Validate birthday before today date (#215)

## [v8.3.1] - 2022-03-12
- WIP Refactor & Simplify datatables import functionality
- Update composer dependency codedungeon/phpunit-result-printer
- Enforce form actions routePrefix consistency
- Add datatables routePrefix support

## [v8.3.0] - 2022-02-14
- Use PHP v8 nullsafe operator
- Update composer dependencies to Laravel v9
- L9: Update password validation rule to current_password
- Move Relation::morphMap and BouncerFacade to module bootstrap
- Add broadcast routes for each accessarea
- Update routes to use class based definitions
- Fix broadcasts naming convensions
- Add support for model HasFactory

## [v8.2.3] - 2022-01-02
- Drop using useless complex string variable syntax
- Remove manager autologin, this requires a redirect to managerarea first, we can't login them into

## [v8.2.2] - 2021-10-22
- Refactor route domain variables to be accessarea specific
- Fix request accessareas method call
- Update .styleci.yml fixers

## [v8.2.1] - 2021-10-11
- Rename route parameter 'central_domain' to 'routeDomain'
- Improve UpdateLastActivity middleware

## [v8.2.0] - 2021-08-22
- Drop PHP v7 support, and upgrade rinvex package dependencies to next major version

## [v8.1.0] - 2021-08-21
- Major changes breaking changes (session isolation per guard), supposed to come in v8.0.0 but got delayed
- Tweak logout to utilize logoutCurrentGuard and session isolation
- Fix AuthenticateSession middleware to use the new session isolation per guard method
- Extend authentication and support logoutCurrentGuard
- Extend authentication and override SessionGuard
- Rename attachRequestMacro to extendRequest
- Use fully qualified namespace of BouncerFacade

## [v8.0.1] - 2021-08-18
- Update composer dependency cortex/foundation to v7

## [v8.0.0] - 2021-08-18
- Breaking Change: Update composer dependency rinvex/laravel-tenants to v7
- Tweak ScopeBouncer middleware and register by default for web group
- Register routes to either central or tenant domains
- Fix accessarea resource usage
- Move route binding, patterns, and middleware to module bootstrap

## [v7.0.24] - 2021-08-07
- Upgrade spatie/laravel-activitylog to v4

## [v7.0.23] - 2021-08-06
- Simplify route prefixes
- Fix docblock GenericException namespace
- Fix wrong middleware spelling
- Update composer dependency codedungeon/phpunit-result-printer

## [v7.0.22] - 2021-06-20
- Fix namespace naming convention

## [v7.0.21] - 2021-05-27
- Rollback AccountException to GenericException and move to cortex/foundation

## [v7.0.20] - 2021-05-27
- Fix social authentication driver check
- Fix auth redirection routes

## [v7.0.19] - 2021-05-27
- Add option to check if social authentication driver is supported or not, or even social is completely disabled. Also drop social auth from adminarea & managerarea

## [v7.0.18] - 2021-05-25
- Replace deprecated `Breadcrumbs::register` with `Breadcrumbs::for`
- Update composer dependencies diglactic/laravel-breadcrumbs to v7

## [v7.0.17] - 2021-05-24
- Merge rules instead of resetting, to allow adequate model override
- Fix datatables export issues
- Drop common blade views in favor for accessarea specific views
- Refactor GenericException to AccountException and move to cortex/auth and return more accurate HTTP status code

## [v7.0.16] - 2021-05-11
- Fix constructor initialization order (fill attributes should come next after merging fillables & rules)
- Set validation rules in constructor for consistency & flexibility

## [v7.0.15] - 2021-05-07
- Upgrade to GitHub-native Dependabot (#195)
- Rename migrations to always run after rinvex core packages

## [v7.0.14] - 2021-05-04
- Update spatie/laravel-schemaless-attributes package
- Fix container service check issue
  - Use container check instead of make app()->has('request.tenant')
- Fix app('request.tenant') check

## [v7.0.13] - 2021-04-07
- Update timezone by query statement to avoid validation exception (#194)
- Utilize SoftDeletes

## [v7.0.12] - 2021-03-02
- Autoload artisan commands
- Tweak user managed abilities

## [v7.0.11] - 2021-02-28
- Check if user is authenticated, before authorizing their api token
- Drop `Authenticate` middleware override
- Fix logout redirect route for adminarea and managerarea
- Fix wrong logout route
- Rename `MemberRegistrationController` controller
- Utilize `UnauthenticatedController` for guest controllers
- Simplify and utilize request()->user() and request()->guard() and request()->passwordResetBroker() and request()->emailVerifierBroker()
- Use overridden `FormRequest` instead of native class
- Utilize `UnauthenticatedController` for guest controllers
- Use overridden `FormRequest` instead of native class
- Simplify and utilize request()->accessarea()
- Revert "Change entity_id field type in permissions table to support non-numeric primary IDs"
- Override Authorize middleware
- Utilize IoC service container instead of hardcoded models for menu permissions
- Change entity_id field type in permissions table to support non-numeric primary IDs
- Move abilities & roles mutators to traits instead of models

## [v7.0.10] - 2021-02-11
- Add `HasApiTokens` to `User` model
- Replace form timestamps with common blade view
- Define morphMany parameters explicitly

## [v7.0.9] - 2021-02-07
- Reverse "Add missing facade alias" commit

## [v7.0.8] - 2021-02-07
- Add missing facade alias

## [v7.0.7] - 2021-02-07
- Delete useless silber/bouncer overrides since we're using now custom modified tmp version
- Replace silber/bouncer package with custom modified tmp version

## [v7.0.6] - 2021-02-06
- Simplify service provider model registration into IoC
- Add support for runtime configurable model to allow model override (fix abilities/permission issues)
- Make entity_type optional as it's not required for abilities (ex: access-adminarea)
- Override bouncer to properly support owned entities permissions
- Skip publishing module resources unless explicitly specified, for simplicity

## [v7.0.5] - 2021-01-15
- Add model replication feature
- handle if twofactor session is null (#166)

## [v7.0.4] - 2021-01-02
- Move cortex:autoload & cortex:activate commands to cortex/foundation module responsibility

## [v7.0.3] - 2021-01-01
- Move cortex:autoload & cortex:activate commands to cortex/foundation module responsibility
  - This is because :autoload & :activate commands are registered only if the module already autoloaded, so there is no way we can execute commands of unloaded modules
  - cortex/foundation module is always autoloaded, so it's the logical and reasonable place to register these :autoload & :activate module commands and control other modules from outside

## [v7.0.2] - 2020-12-31
- Add ScopeBouncer middleware
- Rename seeders directory
- Enable StyleCI risky mode
- Add module activate, deactivate, autoload, unload artisan commands
- Fix: add missing middleware $next($request)

## [v7.0.1] - 2020-12-25
- Add support for PHP v8

## [v7.0.0] - 2020-12-22
- Upgrade to Laravel v8

## [v6.1.12] - 2020-12-12
- Drop OAuth dependency

## [v6.1.11] - 2020-12-11
- Add support for OAuth to user model
- Rename broadcast channels file to avoid accessarea naming
- Rename routes, channels, menus, breadcrumbs, datatable & form IDs to follow same modular naming conventions
- Whitelist broadcast controller action from middleware
- Tweak datatables realtime
- Refactor and tweak Eloquent Events
- Type hint Authorizable user parameter
- Move datatables query filters to separate query scope class
- Simplify transformers to be accessarea independant
- Enforce consistent user object call with the correct guard
- Enforce consistent datatables request object usage

## [v6.1.10] - 2020-10-24
- Add maximum number of password characters

## [v6.1.9] - 2020-10-05
- Drop useless Models class override

## [v6.1.8] - 2020-09-22
- Fix authentication guards for registration auto login

## [v6.1.7] - 2020-09-22
- Skip unnecessary attemptUser step for email verification

## [v6.1.6] - 2020-09-19
- Add calendar icon to birthday fields
- Change birthdate field from text to date
- Move datatable filters to a blade partial

## [v6.1.5] - 2020-09-08
- Check for app()->bound('request.guard') first before using in controller constructors

## [v6.1.4] - 2020-08-28
- Refactor request params setup

## [v6.1.3] - 2020-08-25
- Enforce controller API consistency
- fix account settings media route parameter name in managerarea (#158)
- Activate module after installation

## [v6.1.2] - 2020-07-23
- Flash timezone update message into session without issuing a redirect
- Cast birthday to string instead of date to avoid wrong casting datetime format!
  - https://github.com/laravel/framework/issues/22560#issuecomment-500483281

## [v6.1.1] - 2020-07-17
- add timezone to fillable array in manager model (#146)
- Update timezone validation rule
- Cast birthday to datetime

## [v6.1.0] - 2020-07-16
- Update datatables user filters
- Refactor timezone fields
- Automatically update timezone if missing
- Utilize timezones
- Fix wrong redirection routes
- Use app('request.tenant') instead of $currentTenant
- Use app('request.user') instead of $currentUser
- Add timezone field to user system
- Apply fixes from StyleCI
- Filter members by role and creation date
- Update validation rules

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
- Drop wrong useless adminarea.cortex.auth.account.register route

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

[v9.2.6]: https://github.com/rinvex/cortex-auth/compare/v9.2.5...v9.2.6
[v9.2.5]: https://github.com/rinvex/cortex-auth/compare/v9.2.4...v9.2.5
[v9.2.4]: https://github.com/rinvex/cortex-auth/compare/v9.2.3...v9.2.4
[v9.2.3]: https://github.com/rinvex/cortex-auth/compare/v9.2.2...v9.2.3
[v9.2.2]: https://github.com/rinvex/cortex-auth/compare/v9.2.1...v9.2.2
[v9.2.1]: https://github.com/rinvex/cortex-auth/compare/v9.2.0...v9.2.1
[v9.2.0]: https://github.com/rinvex/cortex-auth/compare/v9.1.0...v9.2.0
[v9.1.0]: https://github.com/rinvex/cortex-auth/compare/v9.0.0...v9.1.0
[v9.1.0]: https://github.com/rinvex/cortex-auth/compare/v9.0.0...v9.1.0
[v9.0.0]: https://github.com/rinvex/cortex-auth/compare/v8.3.11...v9.0.0
[v8.3.11]: https://github.com/rinvex/cortex-auth/compare/v8.3.10...v8.3.11
[v8.3.10]: https://github.com/rinvex/cortex-auth/compare/v8.3.9...v8.3.10
[v8.3.9]: https://github.com/rinvex/cortex-auth/compare/v8.3.8...v8.3.9
[v8.3.8]: https://github.com/rinvex/cortex-auth/compare/v8.3.7...v8.3.8
[v8.3.7]: https://github.com/rinvex/cortex-auth/compare/v8.3.6...v8.3.7
[v8.3.6]: https://github.com/rinvex/cortex-auth/compare/v8.3.5...v8.3.6
[v8.3.5]: https://github.com/rinvex/cortex-auth/compare/v8.3.4...v8.3.5
[v8.3.4]: https://github.com/rinvex/cortex-auth/compare/v8.3.3...v8.3.4
[v8.3.3]: https://github.com/rinvex/cortex-auth/compare/v8.3.2...v8.3.3
[v8.3.2]: https://github.com/rinvex/cortex-auth/compare/v8.3.1...v8.3.2
[v8.3.1]: https://github.com/rinvex/cortex-auth/compare/v8.3.0...v8.3.1
[v8.3.0]: https://github.com/rinvex/cortex-auth/compare/v8.2.3...v8.3.0
[v8.2.3]: https://github.com/rinvex/cortex-auth/compare/v8.2.2...v8.2.3
[v8.2.2]: https://github.com/rinvex/cortex-auth/compare/v8.2.1...v8.2.2
[v8.2.1]: https://github.com/rinvex/cortex-auth/compare/v8.2.0...v8.2.1
[v8.2.0]: https://github.com/rinvex/cortex-auth/compare/v8.1.0...v8.2.0
[v8.1.0]: https://github.com/rinvex/cortex-auth/compare/v8.0.1...v8.1.0
[v8.0.1]: https://github.com/rinvex/cortex-auth/compare/v8.0.0...v8.0.1
[v8.0.0]: https://github.com/rinvex/cortex-auth/compare/v7.0.24...v8.0.0
[v7.0.24]: https://github.com/rinvex/cortex-auth/compare/v7.0.23...v7.0.24
[v7.0.23]: https://github.com/rinvex/cortex-auth/compare/v7.0.22...v7.0.23
[v7.0.22]: https://github.com/rinvex/cortex-auth/compare/v7.0.21...v7.0.22
[v7.0.21]: https://github.com/rinvex/cortex-auth/compare/v7.0.20...v7.0.21
[v7.0.20]: https://github.com/rinvex/cortex-auth/compare/v7.0.19...v7.0.20
[v7.0.19]: https://github.com/rinvex/cortex-auth/compare/v7.0.18...v7.0.19
[v7.0.18]: https://github.com/rinvex/cortex-auth/compare/v7.0.17...v7.0.18
[v7.0.17]: https://github.com/rinvex/cortex-auth/compare/v7.0.16...v7.0.17
[v7.0.16]: https://github.com/rinvex/cortex-auth/compare/v7.0.15...v7.0.16
[v7.0.15]: https://github.com/rinvex/cortex-auth/compare/v7.0.14...v7.0.15
[v7.0.14]: https://github.com/rinvex/cortex-auth/compare/v7.0.13...v7.0.14
[v7.0.13]: https://github.com/rinvex/cortex-auth/compare/v7.0.12...v7.0.13
[v7.0.12]: https://github.com/rinvex/cortex-auth/compare/v7.0.11...v7.0.12
[v7.0.11]: https://github.com/rinvex/cortex-auth/compare/v7.0.10...v7.0.11
[v7.0.10]: https://github.com/rinvex/cortex-auth/compare/v7.0.9...v7.0.10
[v7.0.9]: https://github.com/rinvex/cortex-auth/compare/v7.0.8...v7.0.9
[v7.0.8]: https://github.com/rinvex/cortex-auth/compare/v7.0.7...v7.0.8
[v7.0.7]: https://github.com/rinvex/cortex-auth/compare/v7.0.6...v7.0.7
[v7.0.6]: https://github.com/rinvex/cortex-auth/compare/v7.0.5...v7.0.6
[v7.0.5]: https://github.com/rinvex/cortex-auth/compare/v7.0.4...v7.0.5
[v7.0.4]: https://github.com/rinvex/cortex-auth/compare/v7.0.3...v7.0.4
[v7.0.3]: https://github.com/rinvex/cortex-auth/compare/v7.0.2...v7.0.3
[v7.0.2]: https://github.com/rinvex/cortex-auth/compare/v7.0.1...v7.0.2
[v7.0.1]: https://github.com/rinvex/cortex-auth/compare/v7.0.0...v7.0.1
[v7.0.0]: https://github.com/rinvex/cortex-auth/compare/v6.1.12...v7.0.0
[v6.1.12]: https://github.com/rinvex/cortex-auth/compare/v6.1.11...v6.1.12
[v6.1.11]: https://github.com/rinvex/cortex-auth/compare/v6.1.10...v6.1.11
[v6.1.10]: https://github.com/rinvex/cortex-auth/compare/v6.1.9...v6.1.10
[v6.1.9]: https://github.com/rinvex/cortex-auth/compare/v6.1.8...v6.1.9
[v6.1.8]: https://github.com/rinvex/cortex-auth/compare/v6.1.7...v6.1.8
[v6.1.7]: https://github.com/rinvex/cortex-auth/compare/v6.1.6...v6.1.7
[v6.1.6]: https://github.com/rinvex/cortex-auth/compare/v6.1.5...v6.1.6
[v6.1.5]: https://github.com/rinvex/cortex-auth/compare/v6.1.4...v6.1.5
[v6.1.4]: https://github.com/rinvex/cortex-auth/compare/v6.1.3...v6.1.4
[v6.1.3]: https://github.com/rinvex/cortex-auth/compare/v6.1.2...v6.1.3
[v6.1.2]: https://github.com/rinvex/cortex-auth/compare/v6.1.1...v6.1.2
[v6.1.1]: https://github.com/rinvex/cortex-auth/compare/v6.1.0...v6.1.1
[v6.1.0]: https://github.com/rinvex/cortex-auth/compare/v6.0.3...v6.1.0
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
