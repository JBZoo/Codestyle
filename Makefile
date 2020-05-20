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

ifneq (, $(wildcard ./src/init.Makefile))
    include ./src/init.Makefile
endif


test-all: ##@Project Run all project tests at once
	@make codestyle
	@make test
	@make test-composer-reqs
	@make report-phpqa
	@make report-composer-graph
