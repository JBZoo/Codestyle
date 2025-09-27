# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

JBZoo Codestyle is a comprehensive PHP code quality and standards library that provides tools and configurations for PHPStan, Psalm, PHP-CS-Fixer, PHPUnit, PHPMD, Phan, and other QA tools. It's designed to be included in other JBZoo projects to enforce consistent coding standards.

## Core Architecture

### Makefile-Based Build System
The project uses a sophisticated Makefile system with modular components:
- `src/init.Makefile` - Main entry point included by project Makefiles
- `src/Makefiles/` - Modular Makefile components:
  - `01_defines.Makefile` - Variable definitions and paths
  - `03_tests.Makefile` - All test and linting commands
  - `04_reports.Makefile` - Coverage and analysis reports

### PHPUnit Testing Framework
Custom PHPUnit base classes and traits in `src/PHPUnit/`:
- `AbstractPackageTest` - Base test class for JBZoo packages
- `AbstractPhpStormProxyTest` - PhpStorm integration tests
- `TraitComposer`, `TraitCopyright`, `TraitGithubActions`, etc. - Modular test functionality

### Code Style Configuration
- `src/PhpCsFixer/PhpCsFixerCodingStandard.php` - Main PHP-CS-Fixer configuration class
- Configuration files for all major PHP QA tools (PHPStan, Psalm, PHPMD, etc.)

## Common Commands

### Development
```bash
make update          # Install/update dependencies
make autoload        # Dump optimized autoloader
```

### Testing
```bash
make test           # Run PHPUnit tests
make test-all       # Run all tests and code style checks
make codestyle      # Run all linters at once
```

### Individual QA Tools
```bash
make test-phpstan        # Static analysis with PHPStan
make test-psalm          # Static analysis with Psalm
make test-phpcs          # Code sniffer (PSR-12 + compatibility)
make test-phpcsfixer     # Check PHP-CS-Fixer rules
make test-phpcsfixer-fix # Auto-fix with PHP-CS-Fixer
make test-phpmd          # Mess detector
make test-phan          # Phan static analyzer
```

### Reports
```bash
make report-all         # Generate all reports
make report-phpmetrics  # PHP Metrics report
make report-pdepend     # PHP Depend report
```

## Code Standards

### PHP Requirements
- PHP 8.2+ required
- Strict types declaration required (`declare(strict_types=1)`)
- PSR-12 coding standard with additional JBZoo rules

### Testing Patterns
When creating tests that extend the JBZoo package testing framework:
1. Extend `AbstractPackageTest` for package-level tests
2. Use available traits for specific functionality (TraitComposer, TraitReadme, etc.)
3. Override protected properties like `$packageName`, `$vendorName` as needed

### PHP-CS-Fixer Configuration
The main coding standard is defined in `PhpCsFixerCodingStandard.php`:
- Combines @Symfony, @PhpCsFixer, @PSR12 presets with custom rules
- Strict import ordering and unused import removal
- Custom PHPDoc formatting rules
- PHPUnit-specific formatting

## File Structure Notes

- `src/compatibility.php` - Backward compatibility functions
- `src/phan.php` - Phan static analyzer configuration
- `src/phpcs.xml` - PHP CodeSniffer ruleset
- `src/phpmd.xml` - PHP Mess Detector rules
- Configuration files are in project root: `phpstan.neon`, `psalm.xml`, etc.

## Integration Usage

This package is designed to be included in other projects via:
```makefile
ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif
```

## Testing Environment Detection

The test suite automatically detects CI environments:
- TeamCity: Uses teamcity-specific reporting
- GitHub Actions: Generates GitHub-compatible test reports
- Local: Standard PHPUnit output with coverage
