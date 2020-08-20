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

TC_REPORT      ?= tc-tests
TC_REPORT_MND  ?= tc-inspections

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


ifneq (, $(wildcard ./.phan/config.php))
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


# Render nice title before executing the command
define title
    @echo ""
    @echo "$(C_BACK)>>>> >>>> >>>> >>>> >>>> >>>> $(C_TITLE) $(1) $(CE)"
endef
