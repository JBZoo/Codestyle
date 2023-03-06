#
# JBZoo Toolbox - Codestyle.
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @see        https://github.com/JBZoo/Codestyle
#

report-all: ##@Reports Build all reports at once
	@make build-download-phars
	@-make report-composer-graph
	@-make report-phpmetrics
	@-make report-phploc
	@-make report-pdepend


report-coveralls: ##@Reports Send coverage report to coveralls.io
	$(call title,"Send coverage to coveralls.io")
	$(call download_phar,$(PHP_COVERALLS_PHAR),"php-coveralls")
	@$(VENDOR_BIN)/php-coveralls.phar --version
	@$(VENDOR_BIN)/php-coveralls.phar                        \
        --coverage_clover="$(PATH_BUILD)/coverage_xml/*.xml" \
        --json_path="$(PATH_BUILD)/coveralls.json"           \
        --root_dir="$(PATH_ROOT)"                            \
        -vvv


report-merge-coverage: ##@Reports Merge all coverage reports in one clover file
	$(call title,"Merge all coverage reports in one clover file")
	$(call download_phar,$(PHPCOV_PHAR),"phpcov")
	@mkdir -pv "$(PATH_BUILD)/coverage_cov"
	@$(VENDOR_BIN)/phpcov.phar merge                          \
        --clover="$(PATH_BUILD)/coverage_xml/all_merged.xml"  \
        --html="$(PATH_BUILD)/coverage_html"                  \
        "$(PATH_BUILD)/coverage_cov"                          \
        -v


report-composer-diff: ##@Reports What has changed after a composer update
	$(call title,"What has changed after a composer update")
	@-$(CO_DIFF_BIN)                                  \
        --source="master:composer.lock"               \
        --target="`pwd`/composer.lock"
	@-$(CO_DIFF_BIN)                                  \
        --source="master:composer.lock"               \
        --target="`pwd`/composer.lock"                \
        --output=markdown --no-ansi                   > `pwd`/build/composer-upgrade-log.md
	@rm `pwd`/build/composer.lock
	@echo 'Markdown text: "cat ./build/composer-upgrade-log.md"'


update-extend: ##@Reports Checks new compatible versions of 3rd party libraries
	@$(COMPOSER_BIN) outdated --direct
	@cp -f `pwd`/composer.lock `pwd`/build/composer.lock
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@$(COMPOSER_BIN) update --no-progress $(JBZOO_COMPOSER_UPDATE_FLAGS)
	@-$(CO_DIFF_BIN)                                  \
        --source="`pwd`/build/composer.lock"          \
        --target="`pwd`/composer.lock"
	@-$(CO_DIFF_BIN)                                  \
        --source="`pwd`/build/composer.lock"          \
        --target="`pwd`/composer.lock"                \
        --output=markdown --no-ansi                   > `pwd`/build/composer-upgrade-log.md
	@echo 'Markdown text: "cat ./build/composer-upgrade-log.md"'
	@rm `pwd`/build/composer.lock


report-composer-graph: ##@Reports Build composer graph of dependencies
	$(call title,"Build composer graph of dependencies")
	@mkdir -p "$(PATH_BUILD)/composer-graph"
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/mini.html"         \
        -v
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/extensions.html"   \
        --show-ext                                                \
        -v
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/versions.html"     \
        --show-link-versions                                      \
        --show-package-versions                                   \
        -v
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/suggests.html"     \
        --show-suggests                                           \
        -v
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/dev.html"          \
        --show-dev                                                \
        -v
	@$(CO_GRAPH_BIN)                                              \
        --output="$(PATH_BUILD)/composer-graph/full.html"         \
        --show-ext                                                \
        --show-dev                                                \
        --show-suggests                                           \
        --show-link-versions                                      \
        --show-package-versions                                   \
        -v


report-performance: ##@Reports Build performance summary report
	$(call title,"Performance report - CLI")
	@$(VENDOR_BIN)/phpbench report                 \
        --config="$(JBZOO_CONFIG_PHPBENCH)"        \
        --uuid=tag:jbzoo                           \
        --report=summary                           \
        --precision=2
	$(call title,"Performance report - HTML")
	@$(VENDOR_BIN)/phpbench report                 \
        --config="$(JBZOO_CONFIG_PHPBENCH)"        \
        --uuid=tag:jbzoo                           \
        --report=summary                           \
        --output=jbzoo-html-summary
	$(call title,"Performance report - Markdown")
	@$(VENDOR_BIN)/phpbench report                 \
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
	@$(VENDOR_BIN)/phpmetrics "$(PATH_SRC)"                \
        --report-html="$(PATH_BUILD)/phpmetrics"           \
        --report-violations="$(PATH_BUILD)/phpmetrics.xml" \
        --report-json="$(PATH_BUILD)/phpmetrics.json"      \
        --git=/usr/bin/git                                 \
        --no-interaction                                   || true


report-pdepend: ##@Reports Build PHP Depend Report
	@if [ -z "$(TEAMCITY_VERSION)" ]; then                                \
        $(VENDOR_BIN)/pdepend                                             \
            --dependency-xml="$(PATH_BUILD)/pdepend-dependency.xml"       \
            --jdepend-chart="$(PATH_BUILD)/pdepend-jdepend.svg"           \
            --overview-pyramid="$(PATH_BUILD)/pdepend-pyramid.svg"        \
            --summary-xml="$(PATH_BUILD)/pdepend-summary.xml"             \
            "$(PATH_SRC)";                                                \
    else                                                                  \
        $(VENDOR_BIN)/pdepend                                             \
            --dependency-xml="$(PATH_BUILD)/pdepend-dependency.xml"       \
            --jdepend-chart="$(PATH_BUILD)/pdepend-jdepend.svg"           \
            --overview-pyramid="$(PATH_BUILD)/pdepend-pyramid.svg"        \
            --summary-xml="$(PATH_BUILD)/pdepend-summary.xml"             \
            --quiet                                                       \
            "$(PATH_SRC)";                                                \
        $(CO_CI_REPORT_BIN) teamcity:stats                  \
            --input-format="pdepend-xml"                                  \
            --tc-flow-id="$(FLOW_ID)"                                     \
            --input-file="$(PATH_BUILD)/pdepend-summary.xml";             \
    fi;                                                                   \


report-phploc: ##@Reports PHPloc - Show code stats
	$(call title,"PHPloc - Show code stats")
	$(call download_phar,$(PHPLOC_PHAR),"phploc")
	@if [ -z "$(TEAMCITY_VERSION)" ]; then                                \
        $(VENDOR_BIN)/phploc.phar "$(PATH_SRC)";                          \
    else                                                                  \
        $(VENDOR_BIN)/phploc.phar "$(PATH_SRC)"                           \
            --log-json="$(PATH_BUILD)/phploc.json" --quiet;               \
        $(CO_CI_REPORT_BIN) teamcity:stats                  \
            --input-format="phploc-json"                                  \
            --input-file="$(PATH_BUILD)/phploc.json";                     \
    fi;                                                                   \
