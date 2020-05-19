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

report-phpqa: ##@Reports PHPqa - Build user-friendly code reports
	$(call title,"PHPqa - Build user-friendly code reports")
	@echo "Config: $(JBZOO_CONFIG_PHPQA)"
	@rm -rf $(PATH_BUILD)/phpqa
	@php `pwd`/vendor/bin/phpqa         \
        --config=$(JBZOO_CONFIG_PHPQA)  \
        --analyzedDirs=$(PATH_SRC)      \
        --buildDir=$(PATH_BUILD)/phpqa


report-coveralls: ##@Reports Send coverage report to coveralls.io
	$(call title,"Send coverage to coveralls.io")
	@php `pwd`/vendor/bin/php-coveralls                    \
        --coverage_clover=$(PATH_BUILD)/coverage_xml/*.xml \
        --json_path=$(PATH_BUILD)/coveralls.json           \
        --root_dir=$(PATH_ROOT)                            \
        -vvv


report-merge-coverage: ##@Reports Merge all coverage reports in one clover file
	$(call title,"Merge all coverage reports in one clover file")
	@mkdir -pv $(PATH_BUILD)/coverage_cov
	@php `pwd`/vendor/bin/phpcov merge                 \
        --clover=$(PATH_BUILD)/coverage_xml/all.xml    \
        --html=$(PATH_BUILD)/coverage_html_merged      \
        $(PATH_BUILD)/coverage_cov                     \
        -v


report-composer-diff: ##@Reports What has changed after a composer update
	$(call title,"What has changed after a composer update")
	@php `pwd`/vendor/bin/composer-lock-diff --md


report-composer-graph: ##@Reports Build composer graph of dependencies
	$(call title,"Build composer graph of dependencies")
	@php `pwd`/vendor/bin/graph-composer export $(PATH_ROOT) $(PATH_BUILD)/composer-graph.png  --verbose --format=png
	@echo "See ./composer-graph.png"
