<?php

namespace BoxUK\Describr\Plugins\BoxUK;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 * @package   BoxUK\Describr\plugins\BoxUK
 */
class ImagePluginTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * If GD is installed, we check that the dependencies check passes.
     *
     * If GD is not installed, we check that the dependencies check fails.
     *
     * Arguably this is a poor test, but there does not appear to be any way
     * of simulating an extension being loaded/not loaded in PHP.
     */
    public function testCheckDependenciesEnsuresGdIsInstalled() {
        $imagePlugin = new ImagePlugin();

        if (extension_loaded('gd') && function_exists('gd_info')) {
            // extension is loaded - check response
            $imagePlugin->checkDependencies();
        } else {
            // extension is not loaded
            try {
                $imagePlugin->checkDependencies();
                $this->fail("GD is not installed on this machine, but "
                    . "checkDependencies on ImagePlugin does not appear to be "
                    . "picking this up"
                );
            } catch(\BoxUK\Describr\plugins\UnmetDependencyException $e) {
                // exception was thrown as expected
            }
        }
    }

    public function testMatchingMimeTypesAreAllImageTypes() {
        $imagePlugin = new ImagePlugin();
        $mimeTypes = $imagePlugin->getMatchingMimeTypes();
        $this->assertContains('image/jpeg', $mimeTypes);
        $this->assertContains('image/png', $mimeTypes);
        $this->assertContains('image/gif', $mimeTypes);
        $this->assertContains('image/bmp', $mimeTypes);
    }

    public function testGetAttributesTagsFileSizeOrientationAndDimensionsWorksForJpegFiles() {
        $imagePlugin = new ImagePlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/mostlyGreen.jpg';
        $imagePlugin->setFile($pathToFile);

        $attributes = $imagePlugin->getAttributes();
        $this->assertEquals('Landscape', $attributes['orientation']);
        $this->assertEquals('Small', $attributes['dimensions']);
        $this->assertEquals('SeaGreen', $attributes['mainColour']);
    }

    public function testGetAttributesTagsFileSizeOrientationAndDimensionsWorksForGifFiles() {
        $imagePlugin = new ImagePlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/mostlyYellow.gif';
        $imagePlugin->setFile($pathToFile);

        $attributes = $imagePlugin->getAttributes();
        $this->assertEquals('Portrait', $attributes['orientation']);
        $this->assertEquals('Medium', $attributes['dimensions']);
        $this->assertEquals('Yellow', $attributes['mainColour']);
    }

    public function testGetAttributesTagsFileSizeOrientationAndDimensionsWorksForPngFiles() {
        $imagePlugin = new ImagePlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/mostlyBlue.png';
        $imagePlugin->setFile($pathToFile);

        $attributes = $imagePlugin->getAttributes();
        $this->assertEquals('Landscape', $attributes['orientation']);
        $this->assertEquals('Medium', $attributes['dimensions']);
        $this->assertEquals('RoyalBlue', $attributes['mainColour']);
    }

    public function testGetAttributesTagsFileSizeOrientationAndDimensionsWorksFor16bitBitmapFiles() {
        $imagePlugin = new ImagePlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/sample_16bit.bmp';
        $imagePlugin->setFile($pathToFile);

        $attributes = $imagePlugin->getAttributes();
        $this->assertEquals('Square', $attributes['orientation']);
        $this->assertEquals('Extra Small', $attributes['dimensions']);
    }
    
    public function testGetAttributesTagsFileSizeOrientationAndDimensionsWorksFor24bitBitmapFiles() {
        $imagePlugin = new ImagePlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/sample_24bit.bmp';
        $imagePlugin->setFile($pathToFile);

        $attributes = $imagePlugin->getAttributes();
        $this->assertEquals('Square', $attributes['orientation']);
        $this->assertEquals('Extra Small', $attributes['dimensions']);
    }
}