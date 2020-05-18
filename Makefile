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

include ./src/init.Makefile

test-all: ##@Project Run all project tests at once
	@make test-composer
	@make codestyle
	@make report-phpqa
	@make report-composer-graph