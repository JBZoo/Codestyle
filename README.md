# JBZoo - Codestyle
 
[![Build Status](https://travis-ci.org/JBZoo/Codestyle.svg?branch=master)](https://travis-ci.org/JBZoo/Codestyle)    [![Coverage Status](https://coveralls.io/repos/JBZoo/Codestyle/badge.svg)](https://coveralls.io/github/JBZoo/Codestyle?branch=master)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/Codestyle/coverage.svg)](https://shepherd.dev/github/JBZoo/Codestyle)    [![Codacy Badge](https://app.codacy.com/project/badge/Grade/2da2dd592c1640d7b2a536a59dd728ad)](https://www.codacy.com/gh/JBZoo/Codestyle)    
[![Latest Stable Version](https://poser.pugx.org/JBZoo/Codestyle/v)](https://packagist.org/packages/JBZoo/Codestyle)    [![Latest Unstable Version](https://poser.pugx.org/JBZoo/Codestyle/v/unstable)](https://packagist.org/packages/JBZoo/Codestyle)    [![Dependents](https://poser.pugx.org/JBZoo/Codestyle/dependents)](https://packagist.org/packages/JBZoo/Codestyle/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/JBZoo/Codestyle)](https://github.com/JBZoo/Codestyle/issues)    [![Total Downloads](https://poser.pugx.org/JBZoo/Codestyle/downloads)](https://packagist.org/packages/JBZoo/Codestyle/stats)    [![GitHub License](https://img.shields.io/github/license/JBZoo/Codestyle)](https://github.com/JBZoo/Codestyle/blob/master/LICENSE)


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
