# Cortex Auth Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


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
