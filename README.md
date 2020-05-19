#  JBZoo Codestyle and QA tools [![Build Status](https://travis-ci.org/JBZoo/Codestyle.svg?branch=master)](https://travis-ci.org/JBZoo/Codestyle)

Provides popular tools and general code style standards for all JBZoo projects.

### Makefile

Add into your Makefile the line to have predefined commands like `test-*`, `help`, `list`, etc.
```shell
ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif
```

### Makefile Build-in help

```
Usage:
  - `make [target]`
  - `ENV_VAR=value make [target]`

Reports:
  report-phpqa                  PHPqa - Build user-friendly code reports
  report-coveralls              Send coverage report to coveralls.io
  report-composer-diff          What has changed after a composer update
  report-composer-graph         Build composer graph of dependencies
                                
Project:
  install                       Install all 3rd party dependencies
  update                        Update all 3rd party dependencies
  test-all                      Run all test
  clean-build                   Cleanup build directory
  autoload                      Dump optimized autoload file for PHP
                                
Tests:
  test                          Run unit-tests (alias "test-phpunit")
  codestyle                     Run all codestyle linters at once
  test-composer                 Validate composer.json and composer.lock
  test-phpcs                    PHPcs - Checking PHP Codestyle (PSR-12 + PHP Compatibility)
  test-phpmd                    PHPmd - Mess Detector Checker
  test-phpmd-strict             PHPmd - Mess Detector Checker (strict mode)
  test-phpmnd                   PHPmnd - Magic Number Detector
  test-phplint                  PHP Linter - Checking syntax of PHP
  test-phpcpd                   PHPcpd - Find obvious Copy&Paste
  test-phpstan                  PHPStan - Static Analysis Tool
  test-psalm                    Psalm - static analysis tool for PHP
  test-phan                     Phan - super strict static analyzer for PHP
  test-phploc                   PHPloc - Show code stats
                                
Misc:
  help                          Show this text
  list                          Full list of targets
                                
Trick: Add into your "~/.bash_aliases" the line "complete -W "\`make list\`" make" to use TAB
```
