# JBZoo / Codestyle

[![Build Status](https://travis-ci.org/JBZoo/Codestyle.svg)](https://travis-ci.org/JBZoo/Codestyle)    [![Coverage Status](https://coveralls.io/repos/JBZoo/Codestyle/badge.svg)](https://coveralls.io/github/JBZoo/Codestyle)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Codestyle/coverage.svg)](https://shepherd.dev/github/JBZoo/Codestyle)    
[![Stable Version](https://poser.pugx.org/jbzoo/codestyle/version)](https://packagist.org/packages/jbzoo/codestyle)    [![Latest Unstable Version](https://poser.pugx.org/jbzoo/codestyle/v/unstable)](https://packagist.org/packages/jbzoo/codestyle)    [![Dependents](https://poser.pugx.org/jbzoo/codestyle/dependents)](https://packagist.org/packages/jbzoo/codestyle/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/codestyle)](https://github.com/JBZoo/Codestyle/issues)    [![Total Downloads](https://poser.pugx.org/jbzoo/codestyle/downloads)](https://packagist.org/packages/jbzoo/codestyle/stats)    [![GitHub License](https://img.shields.io/github/license/jbzoo/codestyle)](https://github.com/JBZoo/Codestyle/blob/master/LICENSE)


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
  - `ENV_VAR=value make [target]`

Project:
  update                        Install/Update all 3rd party dependencies
  test-all                      Run all project tests at once
  clean                         Cleanup only build directory
  clean-vendor                  Cleanup all
  autoload                      Dump optimized autoload file for PHP

Reports:
  report-all                    Build all reports at once
  report-phpqa                  PHPqa - Build user-friendly code reports
  report-coveralls              Send coverage report to coveralls.io
  report-merge-coverage         Merge all coverage reports in one clover file
  report-composer-diff          What has changed after a composer update
  update-extend                 Checks new compatible versions of 3rd party libraries
  report-composer-graph         Build composer graph of dependencies
  report-performance            Build performance summary report
  report-phpmetrics             Build PHP Metrics Report
  report-pdepend                Build PHP Depend Report
  report-phploc                 PHPloc - Show code stats

Tests:
  test                          Runs unit-tests (alias "test-phpunit-manual")
  codestyle                     Runs all codestyle linters at once
  codestyle-local               Runs all codestyle linters at once (Internal - Regular Mode)
  codestyle-teamcity            Runs all codestyle linters at once (Internal - Teamcity Mode)
  test-composer                 Validates composer.json and composer.lock
  test-composer-reqs            Checks composer.json the defined dependencies against your code
  test-phpcs                    PHPcs - Checking PHP Codestyle (PSR-12 + PHP Compatibility)
  test-phpmd                    PHPmd - Mess Detector Checker
  test-phpmd-strict             PHPmd - Mess Detector Checker (strict mode)
  test-phpmnd                   PHPmnd - Magic Number Detector
  test-phpcpd                   PHPcpd - Find obvious Copy&Paste
  test-phpstan                  PHPStan - Static Analysis Tool
  test-psalm                    Psalm - static analysis tool for PHP
  test-phan                     Phan - super strict static analyzer for PHP
  test-performance              Run benchmarks and performance tests
  test-performance-travis       Travis wrapper for benchmarks
                                
Misc:
  help                          Show this text
  list                          Full list of targets

Trick: Add into your "~/.bash_aliases" the line "complete -W "\`make list\`" make" to use TAB
```
