# Upgrade Guide

## Version 2 to 3
***

Version 3 adjusts classes to be compatible with version 3 of
[Tatter\Handlers](https://github.com/tattersoftware/codeigniter4-handlers). If you have
created any custom export handlers be sure to read the
[Upgrade Guide](https://github.com/tattersoftware/codeigniter4-handlers/blob/develop/UPGRADING.md)
for that library as well.

* Export handlers have been dubbed "Exporters" to differentiate them from the library; adjust any class extensions appropriately
* The interface method has been renamed `doProcess()` to move away from legacy underscore methods
* The "Image Preview Exporter" class has been renamed `PreviewExporter` to be consistent with its name and ID
