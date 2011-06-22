<?php

namespace BoxUK\Describr;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2011. Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class FacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testFacadeIsAwareOfAllPluginsInThePluginsDirectoryButDoesNotLoadAbstractPlugin() {
        $facade = new Facade();
        $availablePlugins = $facade->listAvailablePlugins();
        $this->assertNotContains('\BoxUK\Describr\Plugins\Abstract', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\ImagePlugin', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\AudioVideo\AudioPlugin', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\AudioVideo\AsfVideoPlugin', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\AudioVideo\Iso14496VideoPlugin', $availablePlugins);
        $this->assertContains('\BoxUK\Describr\Plugins\BoxUK\AudioVideo\MpegVideoPlugin', $availablePlugins);
    }

    /**
     * @expectedException BoxUK\Describr\FileNotFoundException
     */
    public function testDescribeFileThrowsExceptionIfFileNotFound() {
        $facade = new Facade();
        $aResults = $facade->describeFileAsArray(dirname(__FILE__) . '/../../../../resources/NOFILE.jpg');
    }

    public function testDescribeFileTakesAFileAndUsesAllPluginsThatMatchTheMimeTypeOfThatFileToCollateInformationAboutIt() {
        $facade = new Facade();
        $aResults = $facade->describeFileAsArray(dirname(__FILE__) . '/../../../../resources/mostlyGreen.jpg');
        $this->assertArrayHasKey('BoxUK\General', $aResults);
        $this->assertArrayHasKey('BoxUK\Image', $aResults);
        $this->assertArrayNotHasKey('BoxUK\PlainText', $aResults);
    }


    public function testDescribeFileTakesAFileAndUsesAllPluginsThatMatchTheMimeTypeOfThatFileToCollateInformationAboutIt2() {
        $facade = new Facade();
        $aResults = $facade->describeFileAsArray(dirname(__FILE__) . '/../../../../resources/test.txt');

        $this->assertArrayHasKey('BoxUK\General', $aResults);
        $this->assertArrayNotHasKey('BoxUK\Image', $aResults);
        $this->assertArrayHasKey('BoxUK\PlainText', $aResults);

        $this->assertEquals(3,  $aResults['BoxUK\PlainText']['lines']);
        $this->assertEquals(17, $aResults['BoxUK\PlainText']['characters']);
        $this->assertEquals(4,  $aResults['BoxUK\PlainText']['words']);
    }

    public function testDescribeFileTakesAFile() {
        $facade = new Facade();
        $aResults = $facade->describeFileAsArray(dirname(__FILE__) . '/../../../../resources/test-id3v1tag.mp3');

        $this->assertArrayHasKey('BoxUK\General', $aResults);
        $this->assertArrayHasKey('BoxUK\AudioVideo\Audio', $aResults);
        $this->assertArrayNotHasKey('BoxUK\Image', $aResults);
        $this->assertArrayNotHasKey('BoxUK\PlainText', $aResults);
    }

//    public function testDescribeFileTakesAFile2() {
//        $facade = new Facade();
//        $aResults = $facade->describeFile(dirname(__FILE__) . '/../../../../resources/mpeg4.avi');
//
//        $this->assertArrayHasKey('BoxUK\General', $aResults);
//        $this->assertArrayNotHasKey('BoxUK\AudioVideo\Audio\Audio', $aResults);
//        $this->assertArrayNotHasKey('BoxUK\Image', $aResults);
//        $this->assertArrayNotHasKey('BoxUK\PlainText', $aResults);
//    }

    public function testDescribeFileTakesAFile3() {
        $facade = new Facade();
        $aResults = $facade->describeFileAsArray(dirname(__FILE__) . '/../../../../resources/test.wmv');

        $this->assertArrayHasKey('BoxUK\General', $aResults);
        $this->assertArrayHasKey('BoxUK\AudioVideo\AsfVideo', $aResults);
        $this->assertArrayNotHasKey('BoxUK\AudioVideo\MpegVideo', $aResults);
        $this->assertArrayNotHasKey('BoxUK\AudioVideo\Audio', $aResults);
        $this->assertArrayNotHasKey('BoxUK\Image', $aResults);
        $this->assertArrayNotHasKey('BoxUK\PlainText', $aResults);
    }
}