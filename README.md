# JBZoo / Codestyle

[![CI](https://github.com/JBZoo/Codestyle/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/Codestyle/actions/workflows/main.yml?query=branch%3Amaster)    [![Coverage Status](https://coveralls.io/repos/JBZoo/Codestyle/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/Codestyle?branch=master)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Codestyle/coverage.svg)](https://shepherd.dev/github/JBZoo/Codestyle)    [![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/codestyle/badge)](https://www.codefactor.io/repository/github/jbzoo/codestyle/issues)    [![PHP Strict Types](https://img.shields.io/badge/strict__types-%3D1-brightgreen)](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict)    
[![Stable Version](https://poser.pugx.org/jbzoo/codestyle/version)](https://packagist.org/packages/jbzoo/codestyle)    [![Total Downloads](https://poser.pugx.org/jbzoo/codestyle/downloads)](https://packagist.org/packages/jbzoo/codestyle/stats)    [![Dependents](https://poser.pugx.org/jbzoo/codestyle/dependents)](https://packagist.org/packages/jbzoo/codestyle/dependents?order_by=downloads)    [![Visitors](https://visitor-badge.glitch.me/badge?page_id=jbzoo.codestyle)]()    [![GitHub License](https://img.shields.io/github/license/jbzoo/codestyle)](https://github.com/JBZoo/Codestyle/blob/master/LICENSE)


Provides popular tools and general code style standards for all JBZoo projects.

### Makefile

Add into your Makefile the line to have predefined commands like `test-*`, `help`, `list`, etc.

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

### Makefile Build-in help

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
