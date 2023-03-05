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

namespace JBZoo\CodeStyle\PHPUnit;

use JBZoo\PHPUnit\PHPUnit;
use JBZoo\Utils\Cli;
use Symfony\Component\Finder\Finder;

use function JBZoo\PHPUnit\isTrue;
use function JBZoo\PHPUnit\openFile;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class AbstractCopyrightTest extends PHPUnit
{
    // ### Configurations. Override it if you need for your project. ###################################################

    protected string $packageName      = ''; // Overload me!
    protected string $projectRoot      = PROJECT_ROOT;
    protected string $packageVendor    = 'JBZoo Toolbox';
    protected string $packageLicense   = 'MIT';
    protected string $packageCopyright = 'Copyright (C) JBZoo.com, All rights reserved.';
    protected string $packageLink      = 'https://github.com/JBZoo/_PACKAGE_';
    protected string $packageAuthor    = '';
    protected string $eol              = "\n";
    protected bool   $debugMode        = false;

    /** @var string[] */
    protected array $excludePaths = [
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

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->projectRoot = \trim($this->projectRoot);

        if ($this->packageName === '') {
            throw new Exception('$this->packageName is undefined!');
        }

        if ($this->projectRoot === '') {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!\class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    public function testHeadersPhp(): void
    {
        $phpTemplate = $this->prepareTemplate($this->validHeaderPHP);

        $phpTemplate = "<?php{$this->eol}{$this->eol}{$phpTemplate}";
        $phpTemplate .= \implode($this->eol, [
            '',
            ' */',
            '',
            'declare(strict_types=1);',
            '',
        ]);

        $finder = $this->createFinder(['.php', '.phtml']);
        $this->checkHeaderInFiles($finder, $phpTemplate);
    }

    public function testHeadersJs(): void
    {
        $finder = $this->createFinder(['.js', '.jsx'], ['*.min.js', '*.min.jsx']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderJS));
    }

    public function testHeadersCss(): void
    {
        $finder = $this->createFinder(['.css'], ['*.min.css']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderCSS));
    }

    public function testHeadersLess(): void
    {
        $finder = $this->createFinder(['.less']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderLESS));
    }

    public function testHeadersXml(): void
    {
        $finder = $this->createFinder(['.xml.dist', '.xml']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderXML));
    }

    /**
     * Test copyright headers of INI files.
     */
    public function testHeadersIni(): void
    {
        $finder = $this->createFinder(['.ini']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderINI));
    }

    /**
     * Test copyright headers of SH files.
     */
    public function testHeadersSh(): void
    {
        $finder = $this->createFinder(['.sh']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderSH));
    }

    /**
     * Test copyright headers of SQL files.
     */
    public function testHeadersSql(): void
    {
        $finder = $this->createFinder(['.sql']);
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderSQL));
    }

    /**
     * Test copyright headers for files with hash-like comments.
     */
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
        $this->checkHeaderInFiles($finder, $this->prepareTemplate($this->validHeaderHash));
    }

    protected function checkHeaderInFiles(Finder $finder, string $validHeader): void
    {
        if ($this->debugMode) {
            $parentMethod = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
            Cli::out("Count: {$finder->count()}; Method: {$parentMethod}");
        }

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = (string)openFile($file->getPathname());

            if ($this->debugMode) {
                Cli::out(" * {$file->getPathname()}");
            }

            if ($content !== '') {
                $isValid = \str_starts_with($content, $validHeader);

                $errMessage = \implode("\n", [
                    'The file has no valid copyright in header',
                    "See: {$file}",
                    'Expected file header:',
                    \str_repeat('-', 80),
                    $validHeader,
                    \str_repeat('-', 80),
                ]);

                isTrue($isValid, $errMessage);
            }
        }

        isTrue(true); // One assert is a minimum to complete test
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
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->followLinks();

        foreach ($inclusions as $inclusion) {
            if (\str_contains($inclusion, '.')) {
                $finder
                    ->name("*{$inclusion}")
                    ->name($inclusion);
            } else {
                $finder->name($inclusion);
            }
        }

        foreach ($exclusions as $exclusion) {
            $finder->notPath($exclusion);
        }

        return $finder;
    }

    /**
     * Render copyrights.
     */
    protected function prepareTemplate(array $templateRows): string
    {
        $template = \implode($this->eol, $templateRows);

        $replace = [
            '_DESCRIPTION_PHP_'  => \implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_JS_'   => \implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_CSS_'  => \implode($this->eol . ' * ', $this->packageDesc),
            '_DESCRIPTION_LESS_' => \implode($this->eol . '// ', $this->packageDesc),
            '_DESCRIPTION_XML_'  => \implode($this->eol . '    ', $this->packageDesc),
            '_DESCRIPTION_INI_'  => \implode($this->eol . '; ', $this->packageDesc),
            '_DESCRIPTION_SH_'   => \implode($this->eol . '# ', $this->packageDesc),
            '_DESCRIPTION_SQL_'  => \implode($this->eol . '-- ', $this->packageDesc),
            '_DESCRIPTION_HASH_' => \implode($this->eol . '# ', $this->packageDesc),
            '_LINK_'             => $this->packageLink,
            '_NAMESPACE_'        => '_VENDOR_\_PACKAGE_',
            '_COPYRIGHTS_'       => $this->packageCopyright,
            '_PACKAGE_'          => $this->packageName,
            '_LICENSE_'          => $this->packageLicense,
            '_AUTHOR_'           => $this->packageAuthor,
            '_VENDOR_'           => $this->packageVendor,
        ];

        foreach ($replace as $const => $value) {
            $template = \str_replace($const, $value, $template);
        }

        return $template;
    }
}
