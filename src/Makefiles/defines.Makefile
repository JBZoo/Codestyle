#
# JBZoo Codestyle
#
# This file is part of the JBZoo CCK package.
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
PATH_ROOT  ?= `pwd`
PATH_SRC   ?= $(PATH_ROOT)/src
PATH_BIN   ?= $(PATH_ROOT)/bin
PATH_BUILD ?= $(PATH_ROOT)/build
PATH_TESTS ?= $(PATH_ROOT)/tests


ifeq (, $(wildcard ./src/phpcs/ruleset.xml))
    JBZOO_CONFIG_PHPCS ?= `pwd`/vendor/jbzoo/codestyle/src/phpcs/ruleset.xml
else
    JBZOO_CONFIG_PHPCS ?= `pwd`/src/phpcs/ruleset.xml
endif


ifeq (, $(wildcard ./src/phpmd/jbzoo.xml))
    JBZOO_CONFIG_PHPMD ?= `pwd`/vendor/jbzoo/codestyle/src/phpmd/jbzoo.xml
else
    JBZOO_CONFIG_PHPMD ?= `pwd`/src/phpmd/jbzoo.xml
endif


ifeq (, $(wildcard ./phpstan.neon))
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/vendor/jbzoo/codestyle/phpstan.neon
else
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/phpstan.neon
endif


ifeq (, $(wildcard ./psalm.xml))
    JBZOO_CONFIG_PSALM ?= `pwd`/vendor/jbzoo/codestyle/psalm.xml
else
    JBZOO_CONFIG_PSALM ?= `pwd`/psalm.xml
endif


ifeq (, $(wildcard ./.phan/config.php))
    JBZOO_CONFIG_PHAN ?= `pwd`/vendor/jbzoo/codestyle/.phan/config.php
else
    JBZOO_CONFIG_PHAN ?= `pwd`/.phan/config.php
endif


ifeq (, $(wildcard ./.coveralls.yml))
    JBZOO_CONFIG_COVERALLS ?= `pwd`/vendor/jbzoo/codestyle/.coveralls.yml
else
    JBZOO_CONFIG_COVERALLS ?= `pwd`/.coveralls.yml
endif


# Render nice title before executing the command
define title
    @echo "$(C_BACK)>>> >>> >>> >>> $(C_TITLE) $(1) $(CE)"
endef