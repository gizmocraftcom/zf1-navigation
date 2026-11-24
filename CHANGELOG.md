# Changelog

## 2.0.0

Fork of `diablomedia/zendframework1-navigation`, published as `gizmocraft/zendframework1-navigation`.

### Breaking changes

- Requires PHP `>=8.5`. Tested on PHP 8.5 and 8.6.
- Package renamed from `diablomedia/zendframework1-navigation` to `gizmocraft/zendframework1-navigation`. It now requires `gizmocraft/zendframework1-exception`, `-loader`, `-acl`, `-controller` and `-view` (each `^2.0.0`) and `-config` (`^3.0.0`).
- `Zend_Navigation_Page::hashCode()` now returns a 32-character hexadecimal string derived from `spl_object_id()` instead of `spl_object_hash()`, which is deprecated in PHP 8.6. The return type (string) is unchanged, but the concrete values differ.

### Other changes

- Dev dependencies upgraded (PHPUnit 13, PHPStan 2.3, php-cs-fixer 3.95, GrumPHP 2.27). Dropped `composer-require-checker`, which has no release for PHP 8.6.
- Tests migrated to PHPUnit 13 attributes; the removed `assertContainsOnly()` calls use `assertContainsOnlyInstancesOf()`.
- CI runs PHP 8.5 and 8.6.
