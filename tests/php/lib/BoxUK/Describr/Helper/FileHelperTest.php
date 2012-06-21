<?php
namespace BoxUK\Describr\Helper;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2011. Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class FileHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForAsf() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.asf';
        $this->assertEquals('video/x-ms-asf', FileHelper::getMimeType($pathToFile));
    }
    
    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForMp3() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test-id3v1tag.mp3';
        $this->assertEquals('audio/mpeg', FileHelper::getMimeType($pathToFile));
    }
    
    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForJpg() {    
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/mostlyGreen.jpg';
        $this->assertEquals('image/jpeg', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForGif () {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/mostlyYellow.gif';
        $this->assertEquals('image/gif', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForPng() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/mostlyBlue.png';
        $this->assertEquals('image/png', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForFlv() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.flv';
        $this->assertEquals('video/x-flv', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForPlainText() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.txt';
        $this->assertEquals('text/plain', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForMpeg() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.mpeg';
        $this->assertEquals('video/mpeg', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForMov() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.mov';
        $this->assertEquals('video/quicktime', FileHelper::getMimeType($pathToFile));
    }

    public function testDetectsCorrectMimeTypeUsingMagicNumbersAndFallingBackToExtensionLookupForSwf() {
        $pathToFile = dirname(__FILE__) . '/../../../../../resources/test.swf';
        $this->assertEquals('application/x-shockwave-flash', FileHelper::getMimeType($pathToFile));
    }

    public function testGetFileTypeFromExtensionReturnsNullIfExtensionNotKnown() {
        $this->assertNull(FileHelper::getFileTypeFromExtension('fklehjafkleja'));
    }
}
