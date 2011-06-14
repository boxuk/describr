<?php

namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 * @package   BoxUK\Describr\plugins\BoxUK\AudioVideo
 */
class MpegVideoPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testDependsOnPhpReader() {
        $plugin = new MpegVideoPlugin();
        $plugin->checkDependencies();
    }

    public function testMatchingMimeTypesAreAllMpegVideoTypes() {
        $plugin = new MpegVideoPlugin();
        $mimeTypes = $plugin->getMatchingMimeTypes();

        $this->assertContains('video/mpeg', $mimeTypes);
    }

    public function testPluginAnalysesVideoLengthForMpegFiles() {
        $videoPlugin = new MpegVideoPlugin();

        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test.mpeg';
        $videoPlugin->setFile($pathToFile);

        $attributes = $videoPlugin->getAttributes();
        $this->assertEquals('2.041', $attributes['length']);
        $this->assertEquals('2.041', $attributes['length_formatted']);
    }
}