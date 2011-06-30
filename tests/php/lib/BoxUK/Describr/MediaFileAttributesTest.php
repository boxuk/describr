<?php
namespace BoxUK\Describr;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2011. Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
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
}