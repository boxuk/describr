# Describr

Describr is an object-oriented library for describing files. It uses a plugins to determine as much information
as it can about a file.

Describr can be run from the command line via a supplied script, or it can
be integrated into any PHP project to gather information about a variety of
file types. You can write your own plugins, and a few of the file types Describr
supports "out of the box" with the bundled plugins include:

 * GIF
 * JPEG
 * PNG
 * WMV
 * MP4
 * MP3
 * AVI
 * ... and many more!

If there's a file you want to interrogate and its type isn't supported, you can add a plugin to {describr root}/lib/BoxUK/Describr/Plugins
and it will automatically be picked up and ready for use!

## Requirements:

 * PHP 5.3+
 * Fileinfo extension

Plugins may have their own requirements. Some of the requirements for bundled
plugins are:

 * GD extension - needed by the image file analysis plugin
 * [PHP-reader 1.8.1 or better](http://code.google.com/p/php-reader/ "PHP-Reader version 1.8.1 or better") - needed by the audio/video file analysis plugins
 * [Zend Framework](http://framework.zend.com/) - needed by PHP-Reader

## Installation

It's easy to get Describring! If you just want to use it without writing plugins, you can install through our PEAR channel,
pear.boxuk.net (check our [Box UK Labs page](http://www.boxuk.com/labs/ "Box UK Labs - Web experiments and prototypes") for details on that),
but we're assuming that because you're here on our Github you want to get stuck in and grab the source. Great! Here's how you do it:

 1. Install Zend Framework version 1.(latest). Probably best installed via PEAR so it's automatically on your PHP path,
    else you'll need to add it to the path manually.
 2. Download or checkout [PHP-reader 1.8.1 or better](http://code.google.com/p/php-reader/ "PHP-Reader version 1.8.1 or better")
    and add it to your PHP include path.
 3. Grab the source from [Github](https://github.com/boxuk/describr) by cloning the repo (or downloading the zip).

### What if I can't add things to my php include path?

If, in stages (1) and (2), you weren't able to add Zend and PHP-Reader to your PHP include path, you can do the following:

In the root of Describr, copy {describr root}/lib/bootstrap.custom.php-sample to {describr root}/lib/bootstrap.custom.php. You'll now need to edit
{describr root}/lib/bootstrap.custom.php and provide a path to PHP-Reader. You might also need to set a path to Zend Framework in
there too.

If you don't include the Zend Framework and PHP-Reader, Describr will still work but you will not be able to use it
to get much information about audio/video files.

## Using Describr

It's time to test that Describr is installed and ready to rock! We've provided a simple command line
executable script to help you do this, so if you're on Linux/OSX/Unix, chmod {describr root}/bin/describr to be executable. On Windows,
you should just be able to run {describr root}/bin/describr.bat:

```bash
gavd@gavd-desktop:/opt/BoxUK/describr$ bin/describr
describr - tell me about your file...
(c) 2011 Box UK
Usage: describr [path to file]
       describr [path to file 1] [path to file 2] ... [path to file N]
```

If you don't see the above, head back to the Installation section and see if there's anything you've missed. Failing
that, there's a Troubleshooting section below

and then you should be able to do something like:

```bash
gavd@gavd-desktop:/opt/BoxUK/describr$ bin/describr tmp.txt
Analysing tmp.txt...
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
    int(4)
    ["fileSize"]=>
    string(11) "Extra Small"
  }
  ["BoxUK\PlainText"]=>
  array(4) {
    ["errors"]=>
    array(0) {
    }
    ["lines"]=>
    int(1)
    ["characters"]=>
    int(4)
    ["words"]=>
    int(1)
  }
}
```

So, we're up and running! Unless we're not, in which case:

### Troubleshooting

Please check that:

1. Make sure you've chmodded {describr}/bin/describr to be executable if you're on Linux/Unix/OSX:
```bash
    gavd@gavd-desktop:/opt/BoxUK/describr$ chmod u+x bin/describr
```
2. Zend Framework and PHP-Reader are both on the PHP include path OR you are using a custom bootstrap.php. In the latter
   case, check that {describr root}lib/bootstrap.custom.php exists and $describr_pathToPHPReaderLibrary is set correctly. It must point to the
   "library" or "src" directory (depending on the version of php-reader you're
   using) - the one that contains the directory "Zend"
3. Zend Framework should be installed. Installing this by PEAR is probably the
   cleanest way to do this, but if you are not able to use PEAR (e.g. you're on
   restrictive shared hosting, you can add the following to {describr root}/lib/bootstrap.custom.php:

```php
<?php
// ... add the line below to the end of the file
set_include_path('.:/home/you/yourProject/lib/ZendFramework-1.11.1/library');
```

Of course, you will have to adjust the paths to point to where Zend is installed.
This is only as a last resort, it's better to use PEAR.

Any other problems, please put in an issue on the Github project for Describr and we'll try to help you out. As much
info as possible, please!

## Plugins

Describr is based around plugins. Each plugin has a list of file types that it knows how to describe.
The built-in plugins live in {describr root}/lib/BoxUK/Describr/Plugins/BoxUK. Each
plugin has one class that implements Plugin, mainly by extending AbstractPlugin.

### Plugin dependencies

You specify the dependencies for each plugin in the plugin's code. Here's an example from ImagePlugin.php:

```php
/**
 * Make sure that this plugin has everything that it needs - i.e. GD
 *
 * @throws UnmetDependencyException If a dependency is not met
 */
public function checkDependencies() {
    if (!extension_loaded('gd') || !function_exists('gd_info')) {
        throw new UnmetDependencyException('GD is not installed');
    }
}
```

If the dependencies of a plugin are not met (i.e. checkDependencies() fails), that plugin cannot be used, and will fail
silently and record an error when it finds a file it can match but can't report on because of missing dependencies:

```bash
gavd@gavd-desktop:/opt/BoxUK/describr$ bin/describr tests/resources/test.mov
Analysing tests/resources/test.mov...
array(2) {
  ["BoxUK\General"]=>
    *removed for brevity*
  ["BoxUK\AudioVideo\Iso14496Video"]=>
  array(1) {
    ["errors"]=>
    array(1) {
      [0]=>
      string(1236) "This plugin matched the file tests/resources/test.mov, but the dependencies could not be matched. Details:
exception 'BoxUK\Describr\Plugins\UnmetDependencyException' with message 'Class Zend_Media_Iso14496 is not loaded - please ensure the php-reader library is on the include path if you wish to use this plugin' in /opt/BoxUK/describr/lib/BoxUK/Describr/Plugins/BoxUK/AudioVideo/Iso14496VideoPlugin.php:30
Stack trace: *removed for brevity*

    }
  }
}
```

### Creating a plugin

Let's create a trivial plugin. This plugin will just estimate the number of tags in an XML file by counting the <
characters and dividing by two - of course, you'd probably not do this in a production application but it should serve
to illustrate Describr plugins!

We create a file {describr root}/lib/BoxUK/Describr/Plugins/Custom/XmlPlugin.php with the following contents:

```php
<?php

namespace BoxUK\Describr\Plugins\Custom;

/**
 * Plugin for automatically describing XML files
 *
 * @package   BoxUK\Describr\Plugins\BoxUK
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.5
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
        );
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

Notice we haven't specified any dependencies here - it's all vanilla PHP.

Now, if we run our test script against Describr's own build.xml (for example), the XML plugin should automatically be used!

```bash
gavd@gavd-desktop:/opt/BoxUK/describr$ bin/describr build.xml
Analysing build.xml...
array(3) {
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
    int(1191)
  }
  ["BoxUK\PlainText"]=>
  array(4) {
    ["errors"]=>
    array(0) {
    }
    ["lines"]=>
    int(34)
    ["characters"]=>
    int(1191)
    ["words"]=>
    int(122)
  }
  ["Custom\Xml"]=>
  array(2) {
    ["errors"]=>
    array(0) {
    }
    ["tags"]=>
    int(15)
  }
}
```

## Accessing through your project

Using Describr on the command line is all well and good for getting going and testing plugins, but the meat of it is
using it in a project to tell you about files. You can do something like:

```php
<?php
// ...
$this->describr = new \BoxUK\Describr\Facade();
$responseFromDescribr = $this->describr->describeFile($pathToFile);
```

This will give you a BoxUK\Describr\MediaFileAttributes object, which you can interrogate to find out which plugins said
what about the file! The toArray method gives you what we've used so far in the command line scripts, or you can access
it plugin-by-plugin for more fine-grained and powerful control.

## Get involved!

If you find this project useful, or you've found a cool way of using it, let us know! If you've written any useful
plugins, then by all means submit a pull request and we'll take a look!