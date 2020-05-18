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

update: ##@Project Update all 3rd party dependencies
	$(call title,"Update all 3rd party dependencies")
	@composer update --optimize-autoloader


install: ##@Project Install all 3rd party dependencies
	$(call title,"Install all 3rd party dependencies")
	@composer install --optimize-autoloader


clean-build: ##@Project Cleanup build directory
	$(call title,"Cleanup build directory")
	@rm    -fr `pwd`/build
	@mkdir -pv `pwd`/build
	@touch     `pwd`/build/.gitkeep


autoload: ##@Project Dump optimized autoload file for PHP
	$(call title,"Composer - Dump optimized autoload file for PHP")
	@composer dump-autoload --optimize
