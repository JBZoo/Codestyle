#
# JBZoo Toolbox - Codestyle
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    Codestyle
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/Codestyle
#

PHPUNIT_PRETTY_PRINT_PROGRESS ?= true

test: test-phpunit ##@Tests Run unit-tests (alias "test-phpunit")
test-phpunit:
	$(call title,"PHPUnit - Running all tests")
	@php `pwd`/vendor/bin/phpunit \
        --order-by=random         \
        --color                   \
        --verbose


codestyle: ##@Tests Run all codestyle linters at once
	@make test-phplint
	@make test-phpcs
	@make test-phpmd
	@make test-phpmnd
	@make test-phpcpd
	@make test-phpstan
	@make test-psalm
	@make test-phan
	@make test-phploc


test-composer: ##@Tests Validate composer.json and composer.lock
	$(call title,"Composer - Validate system requirements")
	@composer validate --verbose --strict
	@composer check-platform-reqs
	$(call title,"Composer - List of outdated packages")
	@composer outdated --direct --verbose
	$(call title,Composer - Checking dependencies with known security vulnerabilities)
	@php `pwd`/vendor/bin/security-checker security:check



test-phpcs: ##@Tests PHPcs - Checking PHP Codestyle (PSR-12 + PHP Compatibility)
	$(call title,"PHPcs - Checking PHP Codestyle \(PSR-12 + PHP Compatibility\)")
	@echo "Config: $(JBZOO_CONFIG_PHPCS)"
	@php `pwd`/vendor/bin/phpcs $(PATH_SRC)      \
            --standard="$(JBZOO_CONFIG_PHPCS)"   \
            --report=full                        \
            --colors                             \
            -p -s


test-phpmd: ##@Tests PHPmd - Mess Detector Checker
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	@php `pwd`/vendor/bin/phpmd $(PATH_SRC) ansi $(JBZOO_CONFIG_PHPMD) --verbose


test-phpmd-strict: ##@Tests PHPmd - Mess Detector Checker (strict mode)
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	@php `pwd`/vendor/bin/phpmd $(PATH_SRC) ansi $(JBZOO_CONFIG_PHPMD) --verbose --strict


test-phpmnd: ##@Tests PHPmnd - Magic Number Detector
	$(call title,"PHPmnd - Magic Number Detector")
	@php `pwd`/vendor/bin/phpmnd    \
        --progress                  \
        --hint                      \
        $(PATH_SRC)


test-phplint: ##@Tests PHP Linter - Checking syntax of PHP
	$(call title,"PHP Linter - Checking syntax of PHP")
	@php `pwd`/vendor/bin/parallel-lint  \
        --blame                          \
        --colors                         \
        $(PATH_SRC)


test-phpcpd: ##@Tests PHPcpd - Find obvious Copy&Paste
	$(call title,"PHPcpd - Find obvious Copy\&Paste")
	@php `pwd`/vendor/bin/phpcpd $(PATH_SRC)  \
        --verbose                             \
        --progress


test-phpstan: ##@Tests PHPStan - Static Analysis Tool
	$(call title,"PHPStan - Static Analysis Tool")
	@echo "Config: $(JBZOO_CONFIG_PHPSTAN)"
	@php `pwd`/vendor/bin/phpstan analyse       \
        --configuration=$(JBZOO_CONFIG_PHPSTAN) \
        --error-format=table                    \
        $(PATH_SRC)


test-psalm: ##@Tests Psalm - static analysis tool for PHP
	$(call title,"Psalm - static analysis tool for PHP")
	@echo "Config: $(JBZOO_CONFIG_PSALM)"
	@php `pwd`/vendor/bin/psalm         \
        --config=$(JBZOO_CONFIG_PSALM)  \
        --output-format=compact         \
        --show-info=false               \
        --show-snippet=true             \
        --find-unused-psalm-suppress    \
        --long-progress                 \
        --stats


test-phan: ##@Tests Phan - super strict static analyzer for PHP
	$(call title,"Phan - super strict static analyzer for PHP")
	@echo "Config: $(JBZOO_CONFIG_PHAN)"
	@php `pwd`/vendor/bin/phan             \
        --config-file=$(JBZOO_CONFIG_PHAN) \
        --color-scheme=light               \
        --progress-bar                     \
        --backward-compatibility-checks    \
        --print-memory-usage-summary       \
        --markdown-issue-messages          \
        --allow-polyfill-parser            \
        --strict-type-checking             \
        --color


test-phploc: ##@Tests PHPloc - Show code stats
	$(call title,"PHPloc - Show code stats")
	@php `pwd`/vendor/bin/phploc $(PATH_SRC) --verbose
