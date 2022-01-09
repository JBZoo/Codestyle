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

# Colors
CE      = \033[0m
C_RED   = \033[0;97;41m
C_GREEN = \033[0;97;42m
C_BACK  = \033[0;33m
C_TITLE = \033[0;30;46m

# Paths
PATH_ROOT      ?= `pwd`
PATH_SRC       ?= $(PATH_ROOT)/src
PATH_BIN       ?= $(PATH_ROOT)/bin
PATH_BUILD     ?= $(PATH_ROOT)/build
PATH_TESTS     ?= $(PATH_ROOT)/tests

XDEBUG_OFF     ?= no

CI_REPORT        ?= tc-tests
CI_REPORT_MND    ?= tc-inspections
CI_NON_ZERO_CODE ?= no

# Legacy vars. Will be removed ASAP
CI_REPORT        ?= $(TC_REPORT)
CI_REPORT_MND    ?= $(TC_REPORT_MND)

PHP_BIN           ?= php
COMPOSER_BIN      ?= $(shell if [ $(PHP_BIN) = "php" ]; then echo "composer"; else which composer fi;)
PHP_VERSION_ALIAS ?= $(shell $(PHP_BIN) --version | head -n 1 | cut -d " " -f 2 | cut -c 1,3)
PROJECT_ALIAS     ?= $(shell basename `git rev-parse --show-toplevel` | sed 's/-//g')
PHPUNIT_PRETTY_PRINT_PROGRESS ?= true


ifeq ($(strip $(PHP_BIN)),php)
	COMPOSER_BIN = composer
else
	COMPOSER_BIN = $(PHP_BIN) $(shell which composer)
endif


#### Phar files
ifeq ($(strip $(PHP_VERSION_ALIAS)),72)
	PHPCPD_PHAR = https://phar.phpunit.de/phpcpd-4.1.0.phar
	PHPLOC_PHAR = https://phar.phpunit.de/phploc-5.0.0.phar
	PHPCOV_PHAR = https://phar.phpunit.de/phpcov-6.0.1.phar
	BOX_PHAR    = https://github.com/box-project/box/releases/download/3.9.1/box.phar
else
	PHPCPD_PHAR = https://phar.phpunit.de/phpcpd.phar
	PHPLOC_PHAR = https://phar.phpunit.de/phploc.phar
	PHPCOV_PHAR = https://phar.phpunit.de/phpcov.phar
	BOX_PHAR    = https://github.com/box-project/box/releases/download/3.14.0/box.phar
endif

ifeq ($(strip $(PHP_VERSION_ALIAS)),72)
	CO_RC_PHAR = https://github.com/maglnet/ComposerRequireChecker/releases/download/2.1.0/composer-require-checker.phar
else ifeq ($(strip $(PHP_VERSION_ALIAS)),73)
	CO_RC_PHAR = https://github.com/maglnet/ComposerRequireChecker/releases/download/2.1.0/composer-require-checker.phar
else ifeq ($(strip $(PHP_VERSION_ALIAS)),74)
	CO_RC_PHAR = https://github.com/maglnet/ComposerRequireChecker/releases/download/3.8.0/composer-require-checker.phar
else
	CO_RC_PHAR = https://github.com/maglnet/ComposerRequireChecker/releases/latest/download/composer-require-checker.phar
endif


PHAN_PHAR          = https://github.com/phan/phan/releases/latest/download/phan.phar
PHP_COVERALLS_PHAR = https://github.com/php-coveralls/php-coveralls/releases/latest/download/php-coveralls.phar
PDEPEND_PHAR       = https://github.com/pdepend/pdepend/releases/download/2.9.0/pdepend.phar
#PHPMD_PHAR        = https://github.com/phpmd/phpmd/releases/latest/download/phpmd.phar
#CO_DIFF_PHAR      = https://github.com/JBZoo/Composer-Diff/releases/latest/download/composer-diff.phar
#CO_GRAPH_PHAR     = https://github.com/JBZoo/Composer-Graph/releases/latest/download/composer-graph.phar

ifneq (, $(wildcard ./src/phpcs/ruleset.xml))
    JBZOO_CONFIG_PHPCS ?= `pwd`/src/phpcs/ruleset.xml
else
    JBZOO_CONFIG_PHPCS ?= `pwd`/vendor/jbzoo/codestyle/src/phpcs/ruleset.xml
endif


ifneq (, $(wildcard ./src/phpmd/jbzoo.xml))
    JBZOO_CONFIG_PHPMD ?= `pwd`/src/phpmd/jbzoo.xml
else
    JBZOO_CONFIG_PHPMD ?= `pwd`/vendor/jbzoo/codestyle/src/phpmd/jbzoo.xml
endif


ifneq (, $(wildcard ./phpstan.neon))
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/phpstan.neon
else
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/vendor/jbzoo/codestyle/phpstan.neon
endif


ifneq (, $(wildcard ./psalm.xml))
    JBZOO_CONFIG_PSALM ?= `pwd`/psalm.xml
else
    JBZOO_CONFIG_PSALM ?= `pwd`/vendor/jbzoo/codestyle/psalm.xml
endif


ifneq (, $(wildcard ./psalm-baseline.xml))
    JBZOO_CONFIG_PSALM_BASELINE ?= `pwd`/psalm-baseline.xml
else
    JBZOO_CONFIG_PSALM_BASELINE ?= `pwd`/vendor/jbzoo/codestyle/psalm-baseline.xml
endif


ifneq (, $(wildcard ./.phan.php))
    JBZOO_CONFIG_PHAN ?= `pwd`/.phan.php
else ifneq (, $(wildcard ./.phan/config.php))
    JBZOO_CONFIG_PHAN ?= `pwd`/.phan/config.php
else
    JBZOO_CONFIG_PHAN ?= `pwd`/vendor/jbzoo/codestyle/.phan/config.php
endif


ifneq (, $(wildcard ./.phpqa.yml))
    JBZOO_CONFIG_PHPQA ?= `pwd`
else
    JBZOO_CONFIG_PHPQA ?= `pwd`/vendor/jbzoo/codestyle/src/phpqa
endif


ifneq (, $(wildcard ./phpunit.xml))
    JBZOO_CONFIG_PHPUNIT ?= `pwd`/phpunit.xml
else
    JBZOO_CONFIG_PHPUNIT ?= `pwd`/phpunit.xml.dist
endif


ifneq (, $(wildcard ./composer-require-checker.json))
    JBZOO_CONFIG_COMPOSER_REQ_CHECKER ?= `pwd`/composer-require-checker.json
else
    JBZOO_CONFIG_COMPOSER_REQ_CHECKER ?= `pwd`/vendor/jbzoo/codestyle/src/composer-require-checker.json
endif


ifneq (, $(wildcard ./phpbench.json))
    JBZOO_CONFIG_PHPBENCH ?= `pwd`/phpbench.json
else
    JBZOO_CONFIG_PHPBENCH ?= `pwd`/vendor/jbzoo/codestyle/src/phpbench/phpbench.json
endif


# Render colored title before executing a command
define title
    @echo ""
    @echo "$(C_BACK)>>>> >>>> >>>> >>>> >>>> >>>> $(C_TITLE) $(1) $(CE)"
endef

# Download phar file (if needs) and save it in ./vendor/bin
define download_phar
    @echo "Expected PHAR-file: $(CO_RC_PHAR)"
    @test -f "$(PATH_ROOT)/vendor/bin/$(2).phar" && true || (curl $(1) --output "$(PATH_ROOT)/vendor/bin/$(2).phar" -L && chmod +x "$(PATH_ROOT)/vendor/bin/$(2).phar")
endef
