# Tatter\Exports
Modular file exports, for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/exports`
2. Load a handler: `$handler = new \Tatter\Exports\Exports\DownloadHandler($myFile);`
3. Run the export: `return $handler->process();`

## Description

**Exports** defines small classes that can be used to direct files to various destinations.
Each class is a handler that extends `Tatter\Handlers\BaseHandler` and has a distinct set
of `$attributes` (see `Tatter\Handlers`) and its own `_process()` method to do the actual
export. Think of an export as something you might see on a "share menu" from a mobile device:
any supported destination for a certain file type.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/exports`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Usage

You may load Export handlers directly, or use the `Handlers` service to locate them based
on their attributes:
```
	// Loaded directly
	$handler = new \Tatter\Exports\Exports\DownloadHandler($myFile);`

	// Located by Handlers
	$class = service('handlers')
		->setPath('Exports')
		->where(['extensions has' => 'pdf'])
		->first();
	$handler = new $class($myFile);
```

Every handler supports basic setters to provide your file and optional overrides for file
metadata:
```
	$handler->setPath('/path/to/file');
	// or...
	$file = new \CodeIgniter\Files\File('/path/to/file');
	$handler->setPath($file);

	$handler->setFileName('RenameFileDuringExport.bam');
	$handler->setFileMime('overriding/mimetype');
```

To execute the export, call its `process()` method, which will return a `ResponseInterface`
(or in some cases `null`):
```
	class MyController
	{
		public function sendFile($path)
		{
			helper('handlers');
			$handler = handlers('Exports')->find('DownloadHandler');
			
			return $handler->setFile($path)->process();
		}
	}
```
