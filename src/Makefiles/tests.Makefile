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

#### General Tests #####################################################################################################

test: test-phpunit ##@Tests Runs unit-tests (alias "test-phpunit-manual")

test-phpunit: ##@Tests Runs all codestyle linters at once
	$(call title,"PHPUnit - Run all tests")
	@echo "Config: $(JBZOO_CONFIG_PHPUNIT)"
	@if [ -n "$(TEAMCITY_VERSION)" ]; then    \
        make test-phpunit-teamcity;           \
    elif [ -n "$(GITHUB_ACTIONS)" ]; then     \
        make test-phpunit-ga;                 \
    else                                      \
        make test-phpunit-local;              \
    fi;


test-phpunit-teamcity:
	@echo "##teamcity[progressStart 'PHPUnit Tests']"
	@XDEBUG_MODE=coverage $(PHP_BIN) `pwd`/vendor/bin/phpunit       \
        --configuration="$(JBZOO_CONFIG_PHPUNIT)"                   \
        --order-by=random                                           \
        --colors=always                                             \
        --teamcity                                                  \
        --verbose
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter teamcity:stats \
        --input-format="phpunit-clover-xml"                         \
        --input-file="$(PATH_BUILD)/coverage_xml/main.xml"
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter teamcity:stats \
            --input-format="junit-xml"                              \
            --input-file="$(PATH_BUILD)/coverage_junit/main.xml"
	@echo "##teamcity[progressFinish 'PHPUnit Tests']"


test-phpunit-local:
	@XDEBUG_MODE=coverage $(PHP_BIN) `pwd`/vendor/bin/phpunit       \
        --configuration="$(JBZOO_CONFIG_PHPUNIT)"                   \
        --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer  \
        --order-by=random                                           \
        --colors=always                                             \
        --verbose


test-phpunit-ga:
	@XDEBUG_MODE=coverage $(PHP_BIN) `pwd`/vendor/bin/phpunit       \
        --configuration="$(JBZOO_CONFIG_PHPUNIT)"                   \
        --printer=mheap\\GithubActionsReporter\\Printer             \
        --order-by=random                                           \
        --colors=always                                             \
        --verbose


#### All Coding Standards ##############################################################################################

codestyle: ##@Tests Runs all codestyle linters at once
	@if [ -n "$(TEAMCITY_VERSION)" ]; then    \
        make codestyle-teamcity;              \
    elif [ -n "$(GITHUB_ACTIONS)" ]; then     \
        make codestyle-ga;                    \
    else                                      \
        make codestyle-local;                 \
    fi;


codestyle-local:
	@make test-phpcs
	@make test-phpmd
	@make test-phpmnd
	@make test-phpcpd
	@make test-phpstan
	@make test-psalm
	@make test-phan
	@make test-composer
	@-make test-composer-reqs


codestyle-ga:
	@make test-phpcs-ga
	@make test-phpmd-ga
	@make test-phpmnd-ga
	@make test-phpcpd-ga
	@make test-phpstan-ga
	@make test-psalm-ga
	@make test-phan-ga
	@make test-composer-ga
	@-make test-composer-reqs-ga


codestyle-teamcity:
	@echo "##teamcity[progressStart 'Checking Coding Standards']"
	@make test-phpcs-teamcity
	@make test-phpmd-teamcity
	@make test-phpmnd-teamcity
	@make test-phpcpd-teamcity
	@make test-phpstan-teamcity
	@make test-psalm-teamcity
	@make test-phan-teamcity
	@make report-pdepend
	@make report-phploc
	@echo "##teamcity[progressFinish 'Checking Coding Standards']"


#### Composer ##########################################################################################################

test-composer: ##@Tests Validates composer.json and composer.lock
	$(call title,"Composer - Checking common issue")
	@-$(COMPOSER_BIN) diagnose
	$(call title,"Composer - Validate system requirements")
	@$(COMPOSER_BIN) validate --verbose
	@$(COMPOSER_BIN) check-platform-reqs
	$(call title,"Composer - List of outdated packages")
	@$(COMPOSER_BIN) outdated --direct --verbose


test-composer-ga:
	@echo "::group::Composer Validate"
	@make test-composer
	@echo "::endgroup::"


test-composer-reqs: ##@Tests Checks composer.json the defined dependencies against your code
	$(call title,Composer - Check the defined dependencies against your code)
	$(call download_phar,$(CO_RC_PHAR),"composer-require-checker")
	@echo "Config: $(JBZOO_CONFIG_COMPOSER_REQ_CHECKER)"
	@$(PHP_BIN) `pwd`/vendor/bin/composer-require-checker.phar check   \
        --config-file=$(JBZOO_CONFIG_COMPOSER_REQ_CHECKER)             \
        -vvv                                                           \
        $(PATH_ROOT)/composer.json


test-composer-reqs-ga:
	@echo "::group::Composer Require Checker"
	@make test-composer-reqs
	@echo "::endgroup::"


#### PHP Code Sniffer ##################################################################################################

test-phpcs: ##@Tests PHPcs - Checking PHP Code Sniffer (PSR-12 + PHP Compatibility)
	$(call title,"PHPcs - Checks PHP Code Sniffer \(PSR-12 + PHP Compatibility\)")
	@echo "Config: $(JBZOO_CONFIG_PHPCS)"
	@$(PHP_BIN) `pwd`/vendor/bin/phpcs "$(PATH_SRC)"  \
            --standard="$(JBZOO_CONFIG_PHPCS)"        \
            --report=full                             \
            --colors                                  \
            -p -s


test-phpcs-teamcity:
	@rm -f "$(PATH_BUILD)/phpcs-checkstyle.xml"
	@-$(PHP_BIN) `pwd`/vendor/bin/phpcs "$(PATH_SRC)"           \
            --standard="$(JBZOO_CONFIG_PHPCS)"                  \
            --report=checkstyle                                 \
            --report-file="$(PATH_BUILD)/phpcs-checkstyle.xml"  \
            --no-cache                                          \
            --no-colors                                         \
            -s -q > /dev/null
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="checkstyle"                             \
        --output-format="$(TC_REPORT)"                          \
        --suite-name="PHPcs"                                    \
        --root-path="`pwd`"                                     \
        --input-file="$(PATH_BUILD)/phpcs-checkstyle.xml"


test-phpcs-ga:
	@echo "::group::PHPcs"
	@make TC_REPORT=github-cli test-phpcs-teamcity
	@echo "::endgroup::"


#### PHP Mess Detector #################################################################################################

test-phpmd: ##@Tests PHPmd - Mess Detector Checker
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	$(call download_phar,$(PHPMD_PHAR),"phpmd")
	@$(PHP_BIN) `pwd`/vendor/bin/phpmd --version
	@$(PHP_BIN) `pwd`/vendor/bin/phpmd "$(PATH_SRC)" ansi "$(JBZOO_CONFIG_PHPMD)" --verbose


test-phpmd-strict: ##@Tests PHPmd - Mess Detector Checker (strict mode)
	$(call title,"PHPmd - Mess Detector Checker")
	@echo "Config: $(JBZOO_CONFIG_PHPMD)"
	$(call download_phar,$(PHPMD_PHAR),"phpmd")
	@$(PHP_BIN) `pwd`/vendor/bin/phpmd --version
	@$(PHP_BIN) `pwd`/vendor/bin/phpmd "$(PATH_SRC)" ansi "$(JBZOO_CONFIG_PHPMD)" --verbose --strict


test-phpmd-teamcity:
	@rm -f "$(PATH_BUILD)/phpmd.json"
	$(call download_phar,$(PHPMD_PHAR),"phpmd")
	@-$(PHP_BIN) `pwd`/vendor/bin/phpmd "$(PATH_SRC)" json "$(JBZOO_CONFIG_PHPMD)" > "$(PATH_BUILD)/phpmd.json"
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="phpmd-json"                             \
        --output-format="$(TC_REPORT)"                          \
        --suite-name="PHPmd"                                    \
        --root-path="`pwd`"                                     \
        --input-file="$(PATH_BUILD)/phpmd.json"


test-phpmd-ga:
	@echo "::group::PHPmd"
	@make TC_REPORT=github-cli test-phpmd-teamcity
	@echo "::endgroup::"


#### PHP Magic Number Detector #########################################################################################

test-phpmnd: ##@Tests PHPmnd - Magic Number Detector
	$(call title,"PHPmnd - Magic Number Detector")
	@$(PHP_BIN) `pwd`/vendor/bin/phpmnd "$(PATH_SRC)" --progress


test-phpmnd-teamcity:
	@$(PHP_BIN) `pwd`/vendor/bin/phpmnd "$(PATH_SRC)" --quiet --hint --xml-output="$(PATH_BUILD)/phpmnd.xml"
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="phpmnd"                                 \
        --output-format="$(TC_REPORT_MND)"                      \
        --suite-name="PHP Magic Numbers"                        \
        --root-path="$(PATH_SRC)"                               \
        --input-file="$(PATH_BUILD)/phpmnd.xml"


test-phpmnd-ga:
	@echo "::group::PHPmnd"
	@make TC_REPORT=github-cli test-phpmnd-teamcity
	@echo "::endgroup::"


#### PHP Copy@Paste Detector ###########################################################################################

test-phpcpd: ##@Tests PHPcpd - Find obvious Copy&Paste
	$(call title,"PHPcpd - Find obvious Copy\&Paste")
	$(call download_phar,$(PHPCPD_PHAR),"phpcpd")
	@-XDEBUG_MODE=off $(PHP_BIN) `pwd`/vendor/bin/phpcpd.phar "$(PATH_SRC)"


test-phpcpd-teamcity:
	$(call download_phar,$(PHPCPD_PHAR),"phpcpd")
	@-XDEBUG_MODE=off $(PHP_BIN) `pwd`/vendor/bin/phpcpd.phar $(PATH_SRC) --log-pmd="$(PATH_BUILD)/phpcpd.xml"
	@echo ""
	@echo "##teamcity[importData type='pmdCpd' path='$(PATH_BUILD)/phpcpd.xml' verbose='true']"


test-phpcpd-ga:
	@echo "::group::PHPcpd"
	@make TC_REPORT=github-cli test-phpcpd-teamcity
	@echo "::endgroup::"


#### PHPstan - Static Analysis Tool ####################################################################################

test-phpstan: ##@Tests PHPStan - Static Analysis Tool
	$(call title,"PHPStan - Static Analysis Tool")
	@echo "Config: $(JBZOO_CONFIG_PHPSTAN)"
	@$(PHP_BIN) `pwd`/vendor/bin/phpstan analyse  \
        --configuration="$(JBZOO_CONFIG_PHPSTAN)" \
        --error-format=table                      \
        "$(PATH_SRC)"


test-phpstan-teamcity:
	@rm -f "$(PATH_BUILD)/phpstan-checkstyle.xml"
	@-$(PHP_BIN) `pwd`/vendor/bin/phpstan analyse               \
        --configuration="$(JBZOO_CONFIG_PHPSTAN)"               \
        --error-format=checkstyle                               \
        --no-progress                                           \
        "$(PATH_SRC)" > "$(PATH_BUILD)/phpstan-checkstyle.xml"
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="checkstyle"                             \
        --output-format="$(TC_REPORT)"                          \
        --suite-name="PHPstan"                                  \
        --root-path="`pwd`"                                     \
        --input-file="$(PATH_BUILD)/phpstan-checkstyle.xml"


test-phpstan-ga:
	@echo "::group::PHPStan"
	@make TC_REPORT=github-cli test-phpstan-teamcity
	@echo "::endgroup::"


#### Psalm - Static Analysis Tool ######################################################################################

test-psalm: ##@Tests Psalm - static analysis tool for PHP
	$(call title,"Psalm - static analysis tool for PHP")
	@echo "Config:   $(JBZOO_CONFIG_PSALM)"
	@echo "Baseline: $(JBZOO_CONFIG_PSALM_BASELINE)"
	@$(PHP_BIN) `pwd`/vendor/bin/psalm.phar --version
	@$(PHP_BIN) `pwd`/vendor/bin/psalm.phar                     \
        --config="$(JBZOO_CONFIG_PSALM)"                        \
        --use-baseline="$(JBZOO_CONFIG_PSALM_BASELINE)"         \
        --show-snippet=true                                     \
        --report-show-info=true                                 \
        --find-unused-psalm-suppress                            \
        --no-cache                                              \
        --output-format=compact                                 \
        --long-progress                                         \
        --shepherd


test-psalm-teamcity:
	@rm -f "$(PATH_BUILD)/psalm-checkstyle.json"
	@-$(PHP_BIN) `pwd`/vendor/bin/psalm.phar                    \
        --config="$(JBZOO_CONFIG_PSALM)"                        \
        --use-baseline="$(JBZOO_CONFIG_PSALM_BASELINE)"         \
        --show-snippet=true                                     \
        --report-show-info=true                                 \
        --find-unused-psalm-suppress                            \
        --no-cache                                              \
        --output-format=json                                    \
        --no-progress                                           \
        --monochrome > "$(PATH_BUILD)/psalm-checkstyle.json"
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="psalm-json"                             \
        --output-format="$(TC_REPORT)"                          \
        --suite-name="Psalm"                                    \
        --root-path="`pwd`"                                     \
        --input-file="$(PATH_BUILD)/psalm-checkstyle.json"


test-psalm-ga:
	@echo "::group::Psalm"
	@make TC_REPORT=github-cli test-psalm-teamcity
	@echo "::endgroup::"


#### Phan - Static Analysis Tool #######################################################################################

test-phan: ##@Tests Phan - super strict static analyzer for PHP
	$(call title,"Phan - super strict static analyzer for PHP")
	$(call download_phar,$(PHAN_PHAR),"phan")
	@echo "Config: $(JBZOO_CONFIG_PHAN)"
	@$(PHP_BIN) `pwd`/vendor/bin/phan.phar --version
	@$(PHP_BIN) `pwd`/vendor/bin/phan.phar                      \
        --config-file="$(JBZOO_CONFIG_PHAN)"                    \
        --project-root-directory="`pwd`"                        \
        --color-scheme=light                                    \
        --no-progress-bar                                       \
        --backward-compatibility-checks                         \
        --print-memory-usage-summary                            \
        --markdown-issue-messages                               \
        --allow-polyfill-parser                                 \
        --strict-type-checking                                  \
        --analyze-twice	                                        \
        --color


test-phan-teamcity:
	$(call download_phar,$(PHAN_PHAR),"phan")
	@rm -f "$(PATH_BUILD)/phan-checkstyle.xml"
	@-$(PHP_BIN) `pwd`/vendor/bin/phan.phar                     \
        --config-file="$(JBZOO_CONFIG_PHAN)"                    \
        --project-root-directory="`pwd`"                        \
        --output-mode="checkstyle"                              \
        --output="$(PATH_BUILD)/phan-checkstyle.xml"            \
        --no-progress-bar                                       \
        --backward-compatibility-checks                         \
        --markdown-issue-messages                               \
        --allow-polyfill-parser                                 \
        --strict-type-checking                                  \
        --analyze-twice	                                        \
        --no-color
	@$(PHP_BIN) `pwd`/vendor/bin/ci-report-converter convert    \
        --input-format="checkstyle"                             \
        --output-format="$(TC_REPORT)"                          \
        --suite-name="Phan"                                     \
        --root-path="`pwd`"                                     \
        --input-file="$(PATH_BUILD)/phan-checkstyle.xml"


test-phan-ga:
	@echo "::group::Phan"
	@make TC_REPORT=github-cli test-phan-teamcity
	@echo "::endgroup::"


#### Testing Performance ###############################################################################################

test-performance: ##@Tests Run benchmarks and performance tests
	$(call title,"Run benchmarks and performance tests")
	@echo "Config: $(JBZOO_CONFIG_PHPBENCH)"
	@rm    -fr "$(PATH_BUILD)/phpbench"
	@mkdir -pv "$(PATH_BUILD)/phpbench"
	@$(PHP_BIN) `pwd`/vendor/bin/phpbench run                   \
        --config="$(JBZOO_CONFIG_PHPBENCH)"                     \
        --tag=jbzoo                                             \
        --warmup=2                                              \
        --store                                                 \
        --stop-on-error
	@make report-performance


test-performance-travis: ##@Tests Travis wrapper for benchmarks
	$(call title,"Run benchmark tests \(Travis Mode\)")
	@if [ $(XDEBUG_OFF) = "yes" ]; then                         \
       make test-performance;                                   \
    else                                                        \
       echo "Performance test works only if XDEBUG_OFF=yes";    \
    fi;
