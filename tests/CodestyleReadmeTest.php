<?php

/**
 * JBZoo Toolbox - Codestyle
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Codestyle
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Codestyle
 */

declare(strict_types=1);

namespace JBZoo\PHPUnit;

/**
 * Class CodestyleReadmeTest
 *
 * @package JBZoo\PHPUnit
 */
class CodestyleReadmeTest extends AbstractReadmeTest
{
    protected $packageName = 'Codestyle';

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->params['scrutinizer'] = true;
        $this->params['codefactor'] = true;
        $this->params['strict_types'] = true;
        $this->params['travis'] = false;
    }
}
