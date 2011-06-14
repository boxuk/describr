<?php
namespace BoxUK\Describr;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 * @package   BoxUK\Describr
 */
class MediaFileAttributesTest extends \PHPUnit_Framework_TestCase
{
    public function testDescribeFileReturnsMediaFileAttributes() {
        $facade = new Facade();
        $results = $facade->describeFile(dirname(__FILE__) . '/../../../../resources/test.wmv');
        $this->assertTrue($results->hasPlugin('\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin'));
        $this->assertFalse($results->hasPlugin('foo'));
        $this->assertEquals(2, count($results->listPlugins()));
        $this->assertTrue(\is_array($results->getPluginResults('\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin')));
        $this->assertNull($results->getPluginResults('xxx'));
    }
    
    public function testCanUseShortNamesToAccessPluginResults() {
        $facade = new Facade();
        $results = $facade->describeFile(dirname(__FILE__) . '/../../../../resources/test.wmv');
        $this->assertTrue($results->hasPlugin('BoxUK\General'));
        $this->assertTrue(\is_array($results->getPluginResults('BoxUK\General')));

        $this->assertContains('BoxUK\General', $results->listPlugins());
        $this->assertNotContains('BoxUK\General', $results->listFullPluginNames());

        $this->assertEquals(count($results->listPlugins()), count($results->listFullPluginNames()));
    }

    public function testErrorHandling() {
        $mfa = new MediaFileAttributes();
        try {
            throw new Exception('foo');
        } catch(Exception $e) {
            $mfa->addError('\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin', $e);
        }
        try {
            throw new Exception('bar');
        } catch(Exception $e) {
            $mfa->addError('\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin', $e);
        }
        try {
            throw new Exception('baz');
        } catch(Exception $e) {
            $mfa->addError('\BoxUK\Describr\Plugins\BoxUK\ImagePlugin', $e);
        }

        $aErrors = $mfa->getErrors();

        $aGeneral = $aErrors['BoxUK\General'];
        $this->AssertEquals(2, count($aGeneral));

        $aImage = $aErrors['BoxUK\Image'];
        $this->AssertEquals(1, count($aImage));
    }
}