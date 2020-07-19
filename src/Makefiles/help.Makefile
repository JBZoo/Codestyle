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

HELP_FUNCTION =                                                              \
    %help; while(<>){push@{$$help{$$2//'Misc'}},[$$1,$$3]                    \
    if/^([\w-_]+)\s*:.*\#\#(?:@(\w+))?\s(.*)$$/};                            \
    print"$$_:\n", map"  $$_->[0]".(" "x(30-length($$_->[0])))."$$_->[1]\n", \
    @{$$help{$$_}},"\n" for keys %help;


help: ## Show this text
	@echo "Usage:"
	@echo "  - \`make [target]\`"
	@echo "  - \`ENV_VAR=value make [target]\`"
	@echo ''
	@perl -e '$(HELP_FUNCTION)' $(MAKEFILE_LIST)
	@echo 'Trick: Add into your "~/.bash_aliases" the line "complete -W "\`make list\`" make" to use TAB'
	@echo ''


list: ## Full list of targets
	@$(MAKE) -pRrq -f $(MAKEFILE_LIST) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'
