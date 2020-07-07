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

# General Makefile configuration
.PHONY: app bin build src  tests vendor help list test tests
.DEFAULT_GOAL := help
SHELL    = /bin/sh


.EXPORT_ALL_VARIABLES:
COLUMNS                  ?= 120
JBZOO_MAKEFILE           ?= 1
PHAN_ALLOW_XDEBUG        ?= 1
PHAN_DISABLE_XDEBUG_WARN ?= 1


# Bootstrap
CODESTYLE_DIR := $(patsubst %/,%,$(dir $(abspath $(lastword $(MAKEFILE_LIST)))))
include $(CODESTYLE_DIR)/Makefiles/*.Makefile
