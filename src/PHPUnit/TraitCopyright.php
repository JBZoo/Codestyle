<?php

/**
 * JBZoo Toolbox - Codestyle.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Codestyle
 */

declare(strict_types=1);

namespace JBZoo\Codestyle\PHPUnit;

use Symfony\Component\Finder\Finder;

use function JBZoo\PHPUnit\fail;
use function JBZoo\PHPUnit\skip;
use function JBZoo\PHPUnit\success;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitCopyright
{
    /** @var string[] */
    protected array $excludedPathsForCopyrights = [
        '.git',
        '.idea',
        'bower_components',
        'build',
        'fonts',
        'fixtures',
        'logs',
        'node_modules',
        'resources',
        'vendor',
        'temp',
        'tmp',
    ];

    /** @var string[] */
    protected array $packageDesc = [
        'This file is part of the _VENDOR_ project.',
        'For the full copyright and license information, please view the LICENSE',
        'file that was distributed with this source code.',
    ];

    // ### Patterns of copyrights. #####################################################################################

    /** @var string[] */
    protected array $validHeaderPHP = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_.',
        ' *',
        ' * _DESCRIPTION_PHP_',
        ' *',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @see        _LINK_',
    ];

    /** @var string[] */
    protected array $validHeaderJS = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_.',
        ' *',
        ' * _DESCRIPTION_JS_',
        ' *',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @see        _LINK_',
    ];

    /** @var string[] */
    protected array $validHeaderCSS = [
        '/**',
        ' * _VENDOR_ - _PACKAGE_.',
        ' *',
        ' * _DESCRIPTION_CSS_',
        ' *',
        ' * @license    _LICENSE_',
        ' * @copyright  _COPYRIGHTS_',
        ' * @see        _LINK_',
        ' */',
        '',
    ];

    /** @var string[] */
    protected array $validHeaderLESS = [
        '//',
        '// _VENDOR_ - _PACKAGE_.',
        '//',
        '// _DESCRIPTION_LESS_',
        '//',
        '// @license    _LICENSE_',
        '// @copyright  _COPYRIGHTS_',
        '// @see        _LINK_',
        '//',
    ];

    /** @var string[] */
    protected array $validHeaderXML = [
        '<?xml version="1.0" encoding="UTF-8" ?>',
        '<!--',
        '    _VENDOR_ - _PACKAGE_.',
        '',
        '    _DESCRIPTION_XML_',
        '',
        '    @license    _LICENSE_',
        '    @copyright  _COPYRIGHTS_',
        '    @see        _LINK_',
        '-->',
    ];

    /** @var string[] */
    protected array $validHeaderINI = [
        ';',
        '; _VENDOR_ - _PACKAGE_.',
        ';',
        '; _DESCRIPTION_INI_',
        ';',
        '; @license    _LICENSE_',
        '; @copyright  _COPYRIGHTS_',
        '; @see        _LINK_',
        ';',
        '; Note: All ini files need to be saved as UTF-8 (no BOM)',
        ';',
    ];

    /** @var string[] */
    protected array $validHeaderSH = [
        '#!/usr/bin/env sh',
        '',
        '#',
        '# _VENDOR_ - _PACKAGE_.',
        '#',
        '# _DESCRIPTION_SH_',
        '#',
        '# @license    _LICENSE_',
        '# @copyright  _COPYRIGHTS_',
        '# @see        _LINK_',
        '#',
        '',
    ];

    /** @var string[] */
    protected array $validHeaderSQL = [
        '--',
        '-- _VENDOR_ - _PACKAGE_.',
        '--',
        '-- _DESCRIPTION_SQL_',
        '--',
        '-- @license    _LICENSE_',
        '-- @copyright  _COPYRIGHTS_',
        '-- @see        _LINK_',
        '--',
        '',
    ];

    /** @var string[] */
    protected array $validHeaderHash = [
        '#',
        '# _VENDOR_ - _PACKAGE_.',
        '#',
        '# _DESCRIPTION_HASH_',
        '#',
        '# @license    _LICENSE_',
        '# @copyright  _COPYRIGHTS_',
        '# @see        _LINK_',
        '#',
        '',
    ];

    public function testHeadersPhp(): void
    {
        $phpTemplate = $this->prepareTemplate($this->validHeaderPHP);

        $phpTemplate = "<?php\n\n{$phpTemplate}" . \implode("\n", [
            '',
            ' */',
            '',
            'declare(strict_types=1);',
            '',
        ]);

        $finder = $this->createFinder(['.php', '.phtml']);
        static::checkHeaderInFiles($finder, $phpTemplate);
    }

    public function testHeadersJs(): void
    {
        $finder = $this->createFinder(['.js', '.jsx'], ['*.min.js', '*.min.jsx']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderJS));
    }

    public function testHeadersCss(): void
    {
        $finder = $this->createFinder(['.css'], ['*.min.css']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderCSS));
    }

    public function testHeadersLess(): void
    {
        $finder = $this->createFinder(['.less']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderLESS));
    }

    public function testHeadersXml(): void
    {
        $finder = $this->createFinder(['.xml']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderXML));
    }

    public function testHeadersIni(): void
    {
        $finder = $this->createFinder(['.ini']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderINI));
    }

    public function testHeadersSh(): void
    {
        $finder = $this->createFinder(['.sh', '.bash', '.fish']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderSH));
    }

    public function testHeadersSql(): void
    {
        $finder = $this->createFinder(['.sql']);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderSQL));
    }

    public function testHeadersOtherConfigs(): void
    {
        $finder = $this->createFinder([
            'Makefile',
            '.Makefile',
            '.yml',
            '.yaml',
            '.neon',
            '.htaccess',
            '.editorconfig',
            '.gitattributes',
            '.gitignore',
        ]);
        static::checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderHash));
    }

    // ### Internal tools for test case ################################################################################

    /**
     * @param array<string> $inclusions
     * @param array<string> $exclusions
     */
    protected function createFinder(array $inclusions = [], array $exclusions = []): Finder
    {
        $finder = (new Finder())
            ->files()
            ->in(PROJECT_ROOT)
            ->exclude($this->excludedPathsForCopyrights)
            ->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->followLinks();

        foreach ($inclusions as $inclusion) {
            $finder->name($inclusion);
            if (\str_contains($inclusion, '.')) {
                $finder
                    ->name('/\\' . $inclusion . '$/')
                    ->name('/dist\\' . $inclusion . '$/')
                    ->name('/\\' . $inclusion . '.dist$/');
            }
        }

        foreach ($exclusions as $exclusion) {
            $finder->notName($exclusion);
        }

        return $finder;
    }

    /**
     * Render copyrights.
     */
    protected function prepareTemplate(array $templateRows): string
    {
        $template = \implode("\n", $templateRows);

        // Important! Order of replacements is important!
        $replace = [
            '_DESCRIPTION_PHP_'  => \implode("\n * ", $this->packageDesc),
            '_DESCRIPTION_JS_'   => \implode("\n * ", $this->packageDesc),
            '_DESCRIPTION_CSS_'  => \implode("\n * ", $this->packageDesc),
            '_DESCRIPTION_LESS_' => \implode("\n// ", $this->packageDesc),
            '_DESCRIPTION_XML_'  => \implode("\n    ", $this->packageDesc),
            '_DESCRIPTION_INI_'  => \implode("\n; ", $this->packageDesc),
            '_DESCRIPTION_SH_'   => \implode("\n# ", $this->packageDesc),
            '_DESCRIPTION_SQL_'  => \implode("\n-- ", $this->packageDesc),
            '_DESCRIPTION_HASH_' => \implode("\n# ", $this->packageDesc),
            '_LINK_'             => $this->copyrightSee,
            '_NAMESPACE_'        => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'       => $this->copyrightRights,
            '_PACKAGE_'          => $this->packageName,
            '_LICENSE_'          => $this->copyrightLicense,
            '_VENDOR_NS_'        => $this->vendorName,
            '_VENDOR_'           => $this->copyrightVendorName,
        ];

        foreach ($replace as $const => $value) {
            $template = \str_replace($const, $value, $template);
        }

        return $template;
    }

    protected static function checkHeaderInFiles(Finder $finder, string $validHeader): void
    {
        $invalidFiles = [];

        $testFunction = static function (string $content, string $pathname) use ($validHeader, &$invalidFiles): void {
            if (!\str_starts_with($content, $validHeader)) {
                $invalidFiles[] = $pathname;
            }
        };

        $testName   = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        $filesCount = AbstractPackageTest::checkFiles($testName, $finder, $testFunction);

        if (\count($invalidFiles) > 0) {
            $errorMessage = \implode("\n", [
                'The file has no valid copyright in header.',
                'Expected file header:',
                \str_repeat('-', 60),
                $validHeader,
                \str_repeat('-', 60),
                'Invalid Files:',
                'See: ' . \implode("\nSee: ", $invalidFiles),
                '',
            ]);
            fail($errorMessage);
        }

        if ($filesCount > 0) {
            success();
        } else {
            skip('Files not found');
        }
    }
}
