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

# General Makefile configuration
.PHONY: app bin build tests vendor help list test
.DEFAULT_GOAL := help
SHELL = /bin/sh

# Bootstrap
CODESTYLE_DIR := $(patsubst %/,%,$(dir $(abspath $(lastword $(MAKEFILE_LIST)))))
include $(CODESTYLE_DIR)/Makefiles/*.Makefile