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

clean-build: clean
clean: ##@Project Cleanup only build directory
	$(call title,"Cleanup only build directory")
	@rm    -fr $(PATH_BUILD)
	@mkdir -pv $(PATH_BUILD)
	@touch     $(PATH_BUILD)/.gitkeep


clean-vendor: ##@Project Cleanup all
	$(call title,"Cleanup build and vendor directories")
	@make clean
	@rm -fr $(PATH_ROOT)/vendor
	@rm -fr $(PATH_ROOT)/composer.lock


autoload: ##@Project Dump optimized autoload file for PHP
	$(call title,"Composer - Dump optimized autoload file for PHP")
	@$(COMPOSER_BIN) dump-autoload --optimize


build-phar: ##@Project Compile phar file
	$(call download_phar,$(BOX_PHAR),"box")
	@$(BOX_BIN) --version
	@$(BOX_BIN) validate -vvv || true
	@$(COMPOSER_BIN) config autoloader-suffix $(PROJECT_ALIAS) -v
	@$(BOX_BIN) compile --allow-composer-check-failure -vv --composer-bin="php ./composer.phar"
	@$(COMPOSER_BIN) config autoloader-suffix --unset          -v


# TODO: remove this.
build-download-phars:
	$(call download_phar,$(CO_DIFF_PHAR),"composer-diff")
	$(call download_phar,$(CO_GRAPH_PHAR),"composer-graph")
	$(call download_phar,$(CO_CI_REPORT_PHAR),"ci-report-converter")
