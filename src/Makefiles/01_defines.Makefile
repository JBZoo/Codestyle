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
CI_REPORT_GA     ?= plain

PHP_BIN           ?= php
VENDOR_BIN        ?= $(PHP_BIN) `pwd`/vendor/bin
COMPOSER_BIN      ?= $(shell if [ $(PHP_BIN) = "php" ]; then echo "composer"; else which composer fi;)
PHP_VERSION_ALIAS ?= $(shell $(PHP_BIN) --version | head -n 1 | cut -d " " -f 2 | cut -c 1,3)
PROJECT_ALIAS     ?= $(shell basename `git rev-parse --show-toplevel` | sed 's/-//g')


ifeq ($(strip $(PHP_BIN)),php)
	COMPOSER_BIN = composer
else
	COMPOSER_BIN = $(PHP_BIN) $(shell which composer)
endif


#### Phar files
PHPCPD_PHAR        = https://phar.phpunit.de/phpcpd.phar
PHPLOC_PHAR        = https://phar.phpunit.de/phploc.phar
PHPCOV_PHAR        = https://phar.phpunit.de/phpcov.phar
BOX_PHAR           = https://github.com/box-project/box/releases/latest/download/composer-require-checker.phar
CO_RC_PHAR         = https://github.com/maglnet/ComposerRequireChecker/releases/latest/download/composer-require-checker.phar
PHP_COVERALLS_PHAR = https://github.com/php-coveralls/php-coveralls/releases/latest/download/php-coveralls.phar

ifneq (, $(wildcard ./src/phpcs.xml))
    JBZOO_CONFIG_PHPCS ?= `pwd`/src/phpcs.xml
else
    JBZOO_CONFIG_PHPCS ?= `pwd`/vendor/jbzoo/codestyle/src/phpcs.xml
endif


ifneq (, $(wildcard ./.php-cs-fixer.php))
    JBZOO_CONFIG_PHPCSFIXER ?= `pwd`/.php-cs-fixer.php
else
    JBZOO_CONFIG_PHPCSFIXER ?= `pwd`/vendor/jbzoo/codestyle/src/PhpCsFixer/php-cs-fixer.php
endif


ifneq (, $(wildcard ./src/phpmd.xml))
    JBZOO_CONFIG_PHPMD ?= `pwd`/src/phpmd.xml
else
    JBZOO_CONFIG_PHPMD ?= `pwd`/vendor/jbzoo/codestyle/src/phpmd.xml
endif


ifneq (, $(wildcard ./phpstan.neon))
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/phpstan.neon
else
    JBZOO_CONFIG_PHPSTAN ?= `pwd`/vendor/jbzoo/codestyle/src/phpstan.neon
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
    JBZOO_CONFIG_PHPBENCH ?= `pwd`/vendor/jbzoo/codestyle/src/phpbench.json
endif


# Render colored title before executing a command
define title
    @echo ""
    @echo "$(C_BACK)>>>> >>>> >>>> >>>> >>>> >>>> $(C_TITLE) $(1) $(CE)"
endef

# Download phar file (if needs) and save it in ./vendor/bin
define download_phar
    @echo "Expected PHAR: $(1)"
    @echo "Binary file  : ./vendor/bin/$(2).phar"
    @test -f "$(PATH_ROOT)/vendor/bin/$(2).phar"                                            \
      &&                                                                                    \
      echo " * File found. No download required."                                           \
      ||                                                                                    \
    (                                                                                       \
      echo " * File not found. Downloading."                                                \
      &&                                                                                    \
      curl $(1) --output "$(PATH_ROOT)/vendor/bin/$(2).phar"                                \
          --connect-timeout 5 --max-time 10 --retry 5 --retry-delay 0 --retry-max-time 40   \
          --location --fail --silent --show-error                                           \
      &&                                                                                    \
      chmod +x "$(PATH_ROOT)/vendor/bin/$(2).phar"                                          \
    )
endef
