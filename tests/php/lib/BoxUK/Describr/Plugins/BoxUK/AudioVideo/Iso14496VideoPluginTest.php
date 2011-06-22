<?php

namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2011. Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class Iso14496VideoPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testDependsOnPhpReader() {
        $plugin = new Iso14496VideoPlugin();
        $plugin->checkDependencies();
    }

    public function testMatchingMimeTypesAreAllAsfVideoTypes() {
        $plugin = new Iso14496VideoPlugin();
        $mimeTypes = $plugin->getMatchingMimeTypes();

        $this->assertContains('video/quicktime', $mimeTypes);
        $this->assertContains('video/mp4', $mimeTypes);
    }

    public function testPluginAnalysesVideoLengthForMpegFiles() {
        $videoPlugin = new Iso14496VideoPlugin();

        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test.mp4';
        $videoPlugin->setFile($pathToFile);

        $attributes = $videoPlugin->getAttributes();
        $this->assertEquals('2034', $attributes['duration']);
        $this->assertEquals('1000', $attributes['timescale']);
    }
}