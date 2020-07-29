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

test: test-phpunit ##@Tests Runs unit-tests (alias "test-phpunit-manual")
test-phpunit:
	$(call title,"PHPUnit - Running all tests")
	@echo "Config: $(JBZOO_CONFIG_PHPUNIT)"
	@if [[ -z "${TEAMCITY_VERSION}" ]]; then                           \
        php `pwd`/vendor/bin/phpunit                                   \
            --configuration="$(JBZOO_CONFIG_PHPUNIT)"                  \
            --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer \
            --order-by=random                                          \
            --colors=always                                            \
            --verbose;                                                 \
    else                                                               \
        php `pwd`/vendor/bin/phpunit                                   \
            --configuration="$(JBZOO_CONFIG_PHPUNIT)"                  \
            --order-by=random                                          \
            --colors=always                                            \
            --teamcity                                                 \
            --verbose;                                                 \
    fi;


codestyle: ##@Tests Runs all codestyle linters at once
	@make test-phpcs
	@make test-phpmd
	@make test-phpmnd
	@make test-phpcpd
	@make test-phpstan
	@make test-psalm
	@make test-phan
	@make test-phploc
	@make test-composer
	@-make test-composer-reqs


test-composer: ##@Tests Validates composer.json and composer.lock
	$(call title,"Composer - Checking common issue")
	@-composer diagnose
	$(call title,"Composer - Validate system requirements")
	@composer validate --verbose
	@composer check-platform-reqs
	$(call title,"Composer - List of outdated packages")
	@composer outdated --verbose
	$(call title,Composer - Checking dependencies with known security vulnerabilities)
	@php `pwd`/vendor/bin/security-checker security:check


test-composer-reqs: ##@Tests Checks composer.json the defined dependencies against your code
	$(call title,Composer - Check the defined dependencies against your code)
	@echo "Config: $(JBZOO_CONFIG_COMPOSER_REQ_CHECKER)"
	@php `pwd`/vendor/bin/composer-require-checker check   \
        --config-file=$(JBZOO_CONFIG_COMPOSER_REQ_CHECKER) \
        -vvv                                               \
        $(PATH_ROOT)/composer.json


test-phpcs: ##@Tests PHPcs - Checking PHP Codestyle (PSR-12 + PHP Compatibility)
	$(call title,"PHPcs - Checks PHP Codestyle \(PSR-12 + PHP Compatibility\)")
	@echo "Config: $(JBZOO_CONFIG_PHPCS)"
	@php `pwd`/vendor/bin/phpcs "$(PATH_SRC)"  \
            --standard="$(JBZOO_CONFIG_PHPCS)" \
            --report=full                      \
            --colors                           \
            -p -s


test-phpmd: ##@Tests PHPmd - Mess Detector Checker
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	@php `pwd`/vendor/bin/phpmd "$(PATH_SRC)" ansi "$(JBZOO_CONFIG_PHPMD)" --verbose


test-phpmd-strict: ##@Tests PHPmd - Mess Detector Checker (strict mode)
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	@php `pwd`/vendor/bin/phpmd "$(PATH_SRC)" ansi "$(JBZOO_CONFIG_PHPMD)" --verbose --strict


test-phpmnd: ##@Tests PHPmnd - Magic Number Detector
	$(call title,"PHPmnd - Magic Number Detector")
	@php `pwd`/vendor/bin/phpmnd \
        --progress               \
        "$(PATH_SRC)"


test-phpcpd: ##@Tests PHPcpd - Find obvious Copy&Paste
	$(call title,"PHPcpd - Find obvious Copy\&Paste")
	@php `pwd`/vendor/bin/phpcpd "$(PATH_SRC)" \
        --verbose                              \
        --progress


test-phpstan: ##@Tests PHPStan - Static Analysis Tool
	$(call title,"PHPStan - Static Analysis Tool")
	@echo "Config: $(JBZOO_CONFIG_PHPSTAN)"
	@php `pwd`/vendor/bin/phpstan analyse         \
        --configuration="$(JBZOO_CONFIG_PHPSTAN)" \
        --error-format=table                      \
        "$(PATH_SRC)"


test-psalm: ##@Tests Psalm - static analysis tool for PHP
	$(call title,"Psalm - static analysis tool for PHP")
	@echo "Config: $(JBZOO_CONFIG_PSALM)"
	@php `pwd`/vendor/bin/psalm          \
        --config="$(JBZOO_CONFIG_PSALM)" \
        --output-format=compact          \
        --show-info=true                 \
        --show-snippet=true              \
        --long-progress                  \
        --shepherd


test-phan: ##@Tests Phan - super strict static analyzer for PHP
	$(call title,"Phan - super strict static analyzer for PHP")
	@echo "Config: $(JBZOO_CONFIG_PHAN)"
	@php `pwd`/vendor/bin/phan                             \
        --config-file="$(JBZOO_CONFIG_PHAN)"               \
        --color-scheme=light                               \
        --progress-bar                                     \
        --backward-compatibility-checks                    \
        --print-memory-usage-summary                       \
        --markdown-issue-messages                          \
        --allow-polyfill-parser                            \
        --strict-type-checking                             \
        --analyze-twice	                                   \
        --color


test-phploc: ##@Tests PHPloc - Show code stats
	$(call title,"PHPloc - Show code stats")
	@php `pwd`/vendor/bin/phploc "$(PATH_SRC)" --verbose


test-performance: ##@Tests Run benchmarks and performance tests
	$(call title,"Run benchmarks and performance tests")
	@echo "Config: $(JBZOO_CONFIG_PHPBENCH)"
	@rm    -fr "$(PATH_BUILD)/phpbench"
	@mkdir -pv "$(PATH_BUILD)/phpbench"
	@php `pwd`/vendor/bin/phpbench run         \
        --config="$(JBZOO_CONFIG_PHPBENCH)"    \
        --tag=jbzoo                            \
        --warmup=2                             \
        --store                                \
        --stop-on-error
	@make report-performance


test-performance-travis: ##@Tests Travis wrapper for benchmarks
	$(call title,"Run benchmark tests \(Travis Mode\)")
	@if [ $(XDEBUG_OFF) = "yes" ]; then                      \
       make test-performance;                                \
    else                                                     \
       echo "Performance test works only if XDEBUG_OFF=yes"; \
    fi;
