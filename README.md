# JBZoo / Codestyle

[![CI](https://github.com/JBZoo/Codestyle/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/Codestyle/actions/workflows/main.yml?query=branch%3Amaster)
[![Coverage Status](https://coveralls.io/repos/github/JBZoo/Codestyle/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/Codestyle?branch=master)
[![Psalm Coverage](https://shepherd.dev/github/JBZoo/Codestyle/coverage.svg)](https://shepherd.dev/github/JBZoo/Codestyle)
[![Psalm Level](https://shepherd.dev/github/JBZoo/Codestyle/level.svg)](https://shepherd.dev/github/JBZoo/Codestyle)
[![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/codestyle/badge)](https://www.codefactor.io/repository/github/jbzoo/codestyle/issues)

[![Stable Version](https://poser.pugx.org/jbzoo/codestyle/version)](https://packagist.org/packages/jbzoo/codestyle/)
[![Total Downloads](https://poser.pugx.org/jbzoo/codestyle/downloads)](https://packagist.org/packages/jbzoo/codestyle/stats)
[![Dependents](https://poser.pugx.org/jbzoo/codestyle/dependents)](https://packagist.org/packages/jbzoo/codestyle/dependents?order_by=downloads)
[![GitHub License](https://img.shields.io/github/license/jbzoo/codestyle)](https://github.com/JBZoo/Codestyle/blob/master/LICENSE)


Comprehensive collection of QA tools and code quality standards for PHP 8.2+ projects. Provides configurations and wrappers for PHPStan, Psalm, PHP-CS-Fixer, PHPUnit, PHPMD, Phan and other popular code analysis tools.

## Requirements

- **PHP 8.2+** - Modern PHP version with strict typing support
- **Composer** - For dependency management
- **Make** - Build automation tool

## Installation

```bash
composer require --dev jbzoo/codestyle
```

## Quick Start

Add the following to your project's `Makefile` to get access to all QA tools:

```makefile
ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif

update: ##@Project Install/Update all 3rd party dependencies
    $(call title,"Install/Update all 3rd party dependencies")
    @echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
    @composer update $(JBZOO_COMPOSER_UPDATE_FLAGS)

test-all: ##@Project Run all project tests at once
    @make test
    @make codestyle
```

## Available Tools

This package includes configurations for:

- **PHPStan** - Static analysis tool with strict rules
- **Psalm** - Advanced static analysis with type coverage
- **PHP-CS-Fixer** - Code style fixer with PSR-12 and custom rules
- **PHPCS** - Code sniffer for PSR-12 and PHP compatibility
- **PHPMD** - Mess detector for code quality issues
- **PHPUnit** - Unit testing framework with coverage reporting
- **Phan** - Super strict static analyzer
- **PHPMND** - Magic number detector
- **PHPCPD** - Copy-paste detector

## Common Commands

### Development
```bash
make update          # Install/update dependencies
make autoload        # Dump optimized autoloader
make clean           # Cleanup build directory
```

### Testing
```bash
make test           # Run PHPUnit tests
make test-all       # Run all tests and code style checks
make codestyle      # Run all code style linters
```

### Individual QA Tools
```bash
make test-phpstan        # Static analysis with PHPStan
make test-psalm          # Static analysis with Psalm
make test-phpcs          # Code sniffer (PSR-12 + compatibility)
make test-phpcsfixer     # Check PHP-CS-Fixer rules
make test-phpcsfixer-fix # Auto-fix with PHP-CS-Fixer
make test-phpmd          # Mess detector
make test-phan          # Phan static analyzer
```

### Reports
```bash
make report-all         # Generate all reports
make report-phpmetrics  # PHP Metrics report
make report-pdepend     # PHP Depend report
```

## Complete Command Reference

```
Usage:
  - `make [target]`
  - `make [target] OPTION=value`
  - `ENV_VAR=value make [target]`

Misc:
  help                          Show this text
  list                          Full list of targets

Project:
  autoload                      Dump optimized autoload file for PHP
  build-phar                    Compile phar file
  clean                         Cleanup only build directory
  clean-vendor                  Cleanup all
  test-all                      Run all project tests at once
  update                        Install/Update all 3rd party dependencies

Tests:
  codestyle                     Launch all codestyle linters at once
  test                          Launch PHPUnit Tests (alias "test-phpunit")
  test-composer                 Validates composer.json and composer.lock
  test-composer-reqs            Checks composer.json the defined dependencies against your code
  test-performance              Run benchmarks and performance tests
  test-phan                     Phan - super strict static analyzer for PHP
  test-phpcpd                   PHPcpd - Find obvious Copy&Paste
  test-phpcs                    PHPcs - Checking PHP Code Sniffer (PSR-12 + PHP Compatibility)
  test-phpcsfixer               PhpCsFixer - Check code to follow stylish standards
  test-phpcsfixer-fix           PhpCsFixer - Auto fix code to follow stylish standards
  test-phpmd                    PHPmd - Mess Detector Checker
  test-phpmd-strict             PHPmd - Mess Detector Checker (strict mode)
  test-phpmnd                   PHPmnd - Magic Number Detector
  test-phpstan                  PHPStan - Static Analysis Tool
  test-phpunit                  PHPUnit - Launch General Tests
  test-psalm                    Psalm - static analysis tool for PHP

Reports:
  report-all                    Build all reports at once
  report-composer-diff          What has changed after a composer update
  report-composer-graph         Build composer graph of dependencies
  report-coveralls              Send coverage report to coveralls.io
  report-merge-coverage         Merge all coverage reports in one clover file
  report-pdepend                Build PHP Depend Report
  report-performance            Build performance summary report
  report-phploc                 PHPloc - Show code stats
  report-phpmetrics             Build PHP Metrics Report
  update-extend                 Checks new compatible versions of 3rd party libraries

Trick: Add into your "~/.bash_aliases" the line "complete -W "\`make list\`" make" to use TAB
```

## Integration with IDEs

The package provides configurations that work seamlessly with:

- **PhpStorm** - Built-in support for all tools
- **VS Code** - Extensions available for all included tools
- **Vim/Neovim** - LSP support through various plugins

## Advanced Usage

### Custom PHP-CS-Fixer Rules

Extend the base configuration in your `.php-cs-fixer.php`:

```php
use JBZoo\Codestyle\PhpCsFixer\PhpCsFixerCodingStandard;

return (new PhpCsFixerCodingStandard(__DIR__))
    ->addCustomRules([
        'your_custom_rule' => true,
    ])
    ->getFixerConfig();
```

### PHPUnit Base Classes

Use the provided base test classes for consistent testing:

```php
use JBZoo\Codestyle\PHPUnit\AbstractPackageTest;

class YourPackageTest extends AbstractPackageTest
{
    protected string $packageName = 'your-package-name';
}
```

## Contributing

Contributions are welcome! Please ensure:

1. All tests pass: `make test-all`
2. Code follows our standards: `make codestyle`
3. Coverage remains high: `make report-all`

## License

MIT License. See [LICENSE](LICENSE) file for details.
