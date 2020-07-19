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

clean-build: ##@Project Cleanup only build directory
	$(call title,"Cleanup build directory")
	@rm    -fr `pwd`/build
	@mkdir -pv `pwd`/build
	@touch     `pwd`/build/.gitkeep


clean: ##@Project Cleanup all
	$(call title,"Cleanup build directory")
	@make clean-build
	@rm -fr `pwd`/vendor
	@rm -fr `pwd`/composer.lock


autoload: ##@Project Dump optimized autoload file for PHP
	$(call title,"Composer - Dump optimized autoload file for PHP")
	@composer dump-autoload --optimize
