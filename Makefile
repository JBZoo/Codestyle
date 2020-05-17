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

.DEFAULT_GOAL := update

CE   = \033[0m
C_AR = \033[0;33m
C_T  = \033[0;30;46m


update:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Update/Install all dependencies $(CE)"
	@composer update


test-all:
	@make test-composer-system
	@make test-composer-outdated
	@make test-composer-security


test-composer-system:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer - Validate system requirements $(CE)"
	@composer validate --verbose --strict
	@composer check-platform-reqs


test-composer-outdated:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer - List of outdated packages $(CE)"
	@composer outdated --direct --verbose


test-composer-security:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Composer - Checking dependencies with known security vulnerabilities $(CE)"
	@php `pwd`/vendor/bin/security-checker security:check


#### Temp Examples #####################################################################################################
composer-graph:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Build composer graph of dependencies $(CE)"
	@php `pwd`/vendor/bin/graph-composer export `pwd` `pwd`/build/composer-graph.png  --verbose --format=png


phpmd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check PHPmd $(CE)"
	@php `pwd`/vendor/bin/phpmd $(SRC_PATH),$(BIN_PATH) ansi        \
        cleancode,codesize,controversial,design,naming,unusedcode   \
        --verbose


phpcs:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Code Style $(CE)"
	@php `pwd`/vendor/bin/phpcs $(SRC_PATH) $(BIN_PATH)   \
        --standard=PSR12                                  \
        --report=full                                     \
        --colors                                          \
        -p -s


phpstan: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHPStan $(CE)"
	@php `pwd`/vendor/bin/phpstan analyse   \
        --level=max                         \
        --error-format=table                \
        $(SRC_PATH) $(BIN_PATH)


phpmnd: ## Check by PHP Magic Number Detector
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by PHP Magic Number Detector (phpmnd) $(CE)"
	@php `pwd`/vendor/bin/phpmnd    \
        --progress                  \
        --hint                      \
        $(SRC_PATH) $(BIN_PATH)


psalm: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by psalm $(CE)"
	@php `pwd`/vendor/bin/psalm         \
        --config=`pwd`/psalm.xml        \
        --output-format=compact         \
        --show-info=true               \
        --show-snippet=true             \
        --find-unused-psalm-suppress    \
        --long-progress                 \
        --stats

phpqa: ## Check PHP code by PHPStan
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Checking by psalm $(CE)"
	@php `pwd`/vendor/bin/phpqa


phpcpd:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Check Copy&Paste $(CE)"
	@php `pwd`/vendor/bin/phpcpd $(SRC_PATH) $(BIN_PATH) --verbose --progress


phploc:
	@echo "$(C_AR)>>> >>> >>> >>> $(C_T) Show stats $(CE)"
	@php `pwd`/vendor/bin/phploc $(SRC_PATH) $(BIN_PATH) --verbose
