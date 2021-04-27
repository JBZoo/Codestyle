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

report-all: ##@Reports Build all reports at once
	@-make report-composer-diff
	@-make report-composer-graph
	@-make report-phpmetrics
	@-make report-phploc
	@-make report-pdepend
	@-make report-performance


report-phpqa: ##@Reports PHPqa - Build user-friendly code reports
	$(call title,"PHPqa - Build user-friendly code reports")
	@echo "Config: $(JBZOO_CONFIG_PHPQA)"
	@rm -rf "$(PATH_BUILD)/phpqa"
	@$(PHP_BIN) `pwd`/vendor/bin/phpqa   \
        --config="$(JBZOO_CONFIG_PHPQA)" \
        --analyzedDirs="$(PATH_SRC)"     \
        --buildDir="$(PATH_BUILD)/phpqa"


report-coveralls: ##@Reports Send coverage report to coveralls.io
	$(call title,"Send coverage to coveralls.io")
	$(call download_phar,$(PHP_COVERALLS_PHAR),"php-coveralls")
	@$(PHP_BIN) `pwd`/vendor/bin/php-coveralls.phar --version
	@$(PHP_BIN) `pwd`/vendor/bin/php-coveralls.phar          \
        --coverage_clover="$(PATH_BUILD)/coverage_xml/*.xml" \
        --json_path="$(PATH_BUILD)/coveralls.json"           \
        --root_dir="$(PATH_ROOT)"                            \
        -vvv


report-merge-coverage: ##@Reports Merge all coverage reports in one clover file
	$(call title,"Merge all coverage reports in one clover file")
	@mkdir -pv "$(PATH_BUILD)/coverage_cov"
	@$(PHP_BIN) `pwd`/vendor/bin/phpcov merge                 \
        --clover="$(PATH_BUILD)/coverage_xml/all_merged.xml"  \
        --html="$(PATH_BUILD)/coverage_html"                  \
        "$(PATH_BUILD)/coverage_cov"                          \
        -v


report-composer-diff: ##@Reports What has changed after a composer update
	$(call title,"What has changed after a composer update")
	@-$(PHP_BIN) `pwd`/vendor/bin/composer-diff       \
        --source="master:composer.lock"               \
        --target="`pwd`/composer.lock"
	@-$(PHP_BIN) `pwd`/vendor/bin/composer-diff       \
        --source="master:composer.lock"               \
        --target="`pwd`/composer.lock"                \
        --output=markdown --no-ansi                   > `pwd`/build/composer-upgrade-log.md
	@rm `pwd`/build/composer.lock
	@echo 'Markdown text: "cat ./build/composer-upgrade-log.md"'


update-extend: ##@Reports Checks new compatible versions of 3rd party libraries
	@$(COMPOSER_BIN) outdated --direct --verbose
	@cp -f `pwd`/composer.lock `pwd`/build/composer.lock
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@$(COMPOSER_BIN) update --no-progress $(JBZOO_COMPOSER_UPDATE_FLAGS)
	@-$(PHP_BIN) `pwd`/vendor/bin/composer-diff       \
        --source="`pwd`/build/composer.lock"          \
        --target="`pwd`/composer.lock"
	@-$(PHP_BIN) `pwd`/vendor/bin/composer-diff       \
        --source="`pwd`/build/composer.lock"          \
        --target="`pwd`/composer.lock"                \
        --output=markdown --no-ansi                   > `pwd`/build/composer-upgrade-log.md
	@echo 'Markdown text: "cat ./build/composer-upgrade-log.md"'
	@rm `pwd`/build/composer.lock


report-composer-graph: ##@Reports Build composer graph of dependencies
	$(call title,"Build composer graph of dependencies")
	@mkdir -p "$(PATH_BUILD)/composer-graph"
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/mini.html"         \
        -v
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/extensions.html"   \
        --show-ext                                                \
        -v
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/versions.html"     \
        --show-link-versions                                      \
        --show-package-versions                                   \
        -v
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/suggests.html"     \
        --show-suggests                                           \
        -v
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/dev.html"          \
        --show-dev                                                \
        -v
	@$(PHP_BIN) `pwd`/vendor/bin/composer-graph                   \
        --output="$(PATH_BUILD)/composer-graph/full.html"         \
        --show-ext                                                \
        --show-dev                                                \
        --show-suggests                                           \
        --show-link-versions                                      \
        --show-package-versions                                   \
        -v


report-performance: ##@Reports Build performance summary report
	$(call title,"Performance report - CLI")
	@$(PHP_BIN) `pwd`/vendor/bin/phpbench report   \
        --config="$(JBZOO_CONFIG_PHPBENCH)"        \
        --uuid=tag:jbzoo                           \
        --report=summary                           \
        --precision=2
	$(call title,"Performance report - HTML")
	@$(PHP_BIN) `pwd`/vendor/bin/phpbench report   \
        --config="$(JBZOO_CONFIG_PHPBENCH)"        \
        --uuid=tag:jbzoo                           \
        --report=summary                           \
        --output=jbzoo-html-summary
	$(call title,"Performance report - Markdown")
	@$(PHP_BIN) `pwd`/vendor/bin/phpbench report   \
        --config="$(JBZOO_CONFIG_PHPBENCH)"        \
        --uuid=tag:jbzoo                           \
        --report=jbzoo                             \
        --output=jbzoo-md                          \
        --precision=2
	@cat $(PATH_BUILD)/phpbench/for-readme.md


report-phpmetrics: ##@Reports Build PHP Metrics Report
	$(call title,"PHP Metrics Reports")
	@rm -fr   $(PATH_BUILD)/phpmetrics
	@mkdir -p $(PATH_BUILD)/phpmetrics
	@$(PHP_BIN) `pwd`/vendor/bin/phpmetrics  "$(PATH_SRC)" \
        --report-html="$(PATH_BUILD)/phpmetrics"           \
        --report-violations="$(PATH_BUILD)/phpmetrics.xml" \
        --report-json="$(PATH_BUILD)/phpmetrics.json"      \
        --junit="$(PATH_BUILD)/coverage_junit/main.xml"    \
        --git=/usr/bin/git                                 \
        --no-interaction                                   || true


report-pdepend: ##@Reports Build PHP Depend Report
	$(call download_phar,$(PDEPEND_PHAR),"pdepend")
	@if [ -z "$(TEAMCITY_VERSION)" ]; then                                \
        $(PHP_BIN) `pwd`/vendor/bin/pdepend.phar                          \
            --dependency-xml="$(PATH_BUILD)/pdepend-dependency.xml"       \
            --jdepend-chart="$(PATH_BUILD)/pdepend-jdepend.svg"           \
            --overview-pyramid="$(PATH_BUILD)/pdepend-pyramid.svg"        \
            --summary-xml="$(PATH_BUILD)/pdepend-summary.xml"             \
            "$(PATH_SRC)";                                                \
    else                                                                  \
        $(PHP_BIN) `pwd`/vendor/bin/pdepend.phar                          \
            --dependency-xml="$(PATH_BUILD)/pdepend-dependency.xml"       \
            --jdepend-chart="$(PATH_BUILD)/pdepend-jdepend.svg"           \
            --overview-pyramid="$(PATH_BUILD)/pdepend-pyramid.svg"        \
            --summary-xml="$(PATH_BUILD)/pdepend-summary.xml"             \
            --quiet                                                       \
            "$(PATH_SRC)";                                                \
        $(PHP_BIN) `pwd`/vendor/bin/ci-report-converter teamcity:stats    \
            --input-format="pdepend-xml"                                  \
            --tc-flow-id="$(FLOW_ID)"                                     \
            --input-file="$(PATH_BUILD)/pdepend-summary.xml";             \
    fi;                                                                   \


report-phploc: ##@Reports PHPloc - Show code stats
	$(call title,"PHPloc - Show code stats")
	$(call download_phar,$(PHPLOC_PHAR),"phploc")
	@if [ -z "$(TEAMCITY_VERSION)" ]; then                                \
        $(PHP_BIN) `pwd`/vendor/bin/phploc.phar "$(PATH_SRC)" --verbose;  \
    else                                                                  \
        $(PHP_BIN) `pwd`/vendor/bin/phploc.phar "$(PATH_SRC)"             \
            --log-json="$(PATH_BUILD)/phploc.json" --quiet;               \
        $(PHP_BIN) `pwd`/vendor/bin/ci-report-converter teamcity:stats    \
            --input-format="phploc-json"                                  \
            --input-file="$(PATH_BUILD)/phploc.json";                     \
    fi;                                                                   \
