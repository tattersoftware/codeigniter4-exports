# Tatter\Exports
Modular file exports, for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-exports/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-exports/actions/workflows/test.yml)
[![](https://github.com/tattersoftware/codeigniter4-exports/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-exports/actions/workflows/analyze.yml)
[![](https://github.com/tattersoftware/codeigniter4-exports/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-exports/actions/workflows/inspect.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-exports/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-exports?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/exports`
2. Load a handler: `$handler = new \Tatter\Exports\Exports\DownloadHandler($myFile);`
3. Run the export: `return $handler->process();`

## Description

**Exports** defines small classes that can be used to direct files to various destinations.
Each class is a handler discoverable by `Tatter\Handlers` with a distinct set of attributes
and its own `doProcess()` method to do the actual export. Think of an export as something
you might see on a "share menu" from a mobile device: supported destinations for a file type.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
```bash
composer require tatter/exports
```

Or, install manually by downloading the source files and adding the directory to
**app/Config/Autoload.php**.

## Usage

You may load Export handlers directly, or use the `ExportersFactory` to locate them based
on their attributes:
```php
// Loaded directly
$handler = new \Tatter\Exports\Exporters\DownloadExporter($myFile);`

// Located by Handlers
$class    = ExporterFactory::find('download');
$exporter = new $class($myFile);
```

Every handler supports basic setters to provide your file and optional overrides for file
metadata:
```php
$exporter->setFile('/path/to/file');
// or...
$file = new \CodeIgniter\Files\File('/path/to/file');
$exporter->setFile($file);

$exporter->setFileName('RenameFileDuringExport.bam');
$exporter->setFileMime('overriding/mimetype');
```

To execute the export, call its `process()` method, which will return a `ResponseInterface`:
```php
use Tatter\Exports\ExporterFactory;

class MyController
{
    public function previewFile($path)
    {
        $class    = ExporterFactory::find('preview');
        $exporter = new $class();

        return $exporter->setFile($path)->process();
    }
}
```
