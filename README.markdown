# Describr

Describr is an object-oriented library for describing files. It uses a series
of plugins to determine as much information as it can about a given file type.

Describr can be run from the command line via a supplied script, or it can
be integrated into any PHP project to gather informatino about a variety of
file types. You can write your own plugins, and a few of the file types Describr
supports "out of the box" with the bundled plugins include:

 * GIF
 * JPEG
 * PNG
 * WMV
 * MP4
 * MP3
 * AVI
 * many more!

If there's a file you want to interrogate and it isn't supported, you can add a plugin /opt/BoxUK/describr/lib/BoxUK/Describr/Plugins and it will automatically be picked up and ready for use!

## Requirements:

 * PHP 5.3+
 * Fileinfo extension

Plugins may have their own requirements. Some of the requirements for bundled
plugins are:

 * GD extension - needed by the image file analysis plugin
 * [PHP-reader 1.8.1 or better](http://code.google.com/p/php-reader/ "PHP-Reader version 1.8.1 or better") - needed by the audio/video file analysis plugins
 * [Zend Framework](http://framework.zend.com/) - needed by PHP-Reader

## Getting started

Download or checkout PHP-reader

Download or clone describr

In the root of describr, copy lib/bootstrap.custom.php-sample to
lib/bootstrap.custom.php. You'll need to edit lib/bootstrap.custom.php and provide
a path to PHP-Reader:

```php
<?php
$describr_pathToPHPReaderLibrary = '/home/you/yourProject/lib/php-reader-1.8.1/src';
```

Now, test that describr is working. We've provided a simple command line
executable script to help you do this, so chmod it to 755 if you're on Linux/OSX
and then you should be able to do something like:

```bash
gavd@gavd-desktop:~$ /home/you/yourProject/lib/describr/bin/describr tmp.txt
array(2) {
  ["BoxUK\General"]=>
  array(6) {
    ["errors"]=>
    array(0) {
    }
    ["extension"]=>
    string(3) "txt"
    ["type"]=>
    string(8) "document"
    ["mimeType"]=>
    string(10) "text/plain"
    ["fileSizeInBytes"]=>
    int(12647)
    ["fileSize"]=>
    string(11) "Extra Small"
  }
  ["BoxUK\PlainText"]=>
  array(4) {
    ["errors"]=>
    array(0) {
    }
    ["lines"]=>
    int(45)
    ["characters"]=>
    int(12647)
    ["words"]=>
    int(1624)
  }
}
```

If you look in {describr}/bin/describr.php, you'll see an example of how to
load Describr and use it in your projects!

### Troubleshooting

If there are any problems loading files, please check that :

1. Make sure you've chmodded {describr}/bin/describr to 755 if you're on Linux/OSX
2. $describr_pathToPHPReaderLibrary is set correctly. It must point to the
   "library" or "src" directory (depending on the version of php-reader you're
   using) - the one that contains the directory "Zend" - or describr cannot load
   the php-reader library
3. Zend Framework should be installed. Installing this by PEAR is probably the
   cleanest way to do this, but if you are not able to use PEAR (e.g. you're on
   restrictive shared hosting, you can add the following to {describr}/lib/bootstrap.custom.php:

```php
<?php
$describr_pathToPHPReaderLibrary = '/home/you/yourProject/lib/php-reader-1.8.1/src';
set_include_path('.:/home/you/yourProject/lib/ZendFramework-1.11.1/library');
```

Of course, you will have to adjust the paths to point to where Zend is installed.
This is only as a last resort, it's better to use PEAR.

## Plugins

describr is based around plugins. Each plugin has a list of file types that it
knows how to describe. 

If a plugin's dependencies are not met (i.e. checkDependencies() fails), that
plugin cannot be used, and will fail silently if PHP error reporting is turned
off, or will just throw the error if PHP error reporting is turned on.

The built-in plugins live in {describr}/lib/BoxUK/Describr/Plugins/BoxUK. Each
plugin has one class that implements Plugin, mainly by extending AbstractPlugin.
Each plugin also has a .ini file that can be used to configure its settings.

### Creating a plugin

Let's create a trivial plugin. This plugin will just estimate the number of tags
in an XML file by counting the < characters and dividing by two - of course, you'd
probably not do this in a production application but it should serve to illustrate
describr plugins!

We create a file {describr}/lib/BoxUK/Describr/Plugins/custom/XmlPlugin.php with
the following contents:

```php
<?php

namespace BoxUK\Describr\Plugins\custom;

/**
 * Plugin for automatically describing XML files
 *
 * @package   BoxUK\Describr\Plugins\BoxUK
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
class XmlPlugin extends \BoxUK\Describr\Plugins\AbstractPlugin
{

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'text/xml'
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'xml',
            'xsl',
        )
    }
    
    /**
     * Stub out configuration loading
     */
    protected function loadConfiguration() {}

    /**
     * @return array With key 'tags' which is a count of tags in this document
     */
    protected function setAttributes() {
        $fileContents = file_get_contents($this->fullPathToFileOnDisk);
        $tagOpenCount = substr_count($fileContents, '<');
        $tagCloseCount = substr_count($fileContents, '</');
        $tagCount = $tagOpenCount - $tagCloseCount;
                
        $this->attributes['tags'] = $tagCount;
    }
}
```

Now, if we run our test script against describr's own build.xml, the XML plugin
should automatically be used!

```bash
gavd@gavd-desktop:/opt/BoxUK/describr$ bin/describr build.xml
array(3) {
  ["custom\Xml"]=>
  array(2) {
    ["errors"]=>
    array(0) {
    }
    ["tags"]=>
    int(6)
  }
  ["BoxUK\General"]=>
  array(5) {
    ["errors"]=>
    array(0) {
    }
    ["extension"]=>
    string(3) "xml"
    ["type"]=>
    NULL
    ["mimeType"]=>
    string(15) "application/xml"
    ["fileSizeInBytes"]=>
    int(351)
  }
  ["BoxUK\PlainText"]=>
  array(4) {
    ["errors"]=>
    array(0) {
    }
    ["lines"]=>
    int(11)
    ["characters"]=>
    int(351)
    ["words"]=>
    int(39)
  }
}
```

## Accessing through your project

Using Describr on the command line is all well and good, but the meat of it is
using it in a project to tell you about files. You can do something like:

```php
<?php
// ...
$responseFromDescribr = $this->describr->describeFile($pathToFile);
```

This will give you a BoxUK\Describr\MediaFileAttributes object, which you can
interrogate to find out which plugins said what about the file! The toArray method
gives you what we've used so far in the command line scripts, or you can access
it plugin-by-plugin for more fine-grained and powerful control.

## Get involved!

If you find this project useful, or you've found a cool way of using it, let us
know!