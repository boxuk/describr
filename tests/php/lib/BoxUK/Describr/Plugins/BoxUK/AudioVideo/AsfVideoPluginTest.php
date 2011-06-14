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
class AsfVideoPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testDependsOnPhpReader() {
        $plugin = new AsfVideoPlugin();
        $plugin->checkDependencies();
    }

    public function testMatchingMimeTypesAreAllAsfVideoTypes() {
        $plugin = new AsfVideoPlugin();
        $mimeTypes = $plugin->getMatchingMimeTypes();

        $this->assertContains('video/x-la-asf', $mimeTypes);
        $this->assertContains('video/x-ms-asf', $mimeTypes);
        $this->assertContains('video/x-ms-wm', $mimeTypes);
        $this->assertContains('video/x-ms-wmv', $mimeTypes);
        $this->assertContains('video/x-ms-wvx', $mimeTypes);
        $this->assertContains('video/x-msvideo', $mimeTypes);
    }

    public function testPluginAnalysesVideoLengthForWmvFiles() {
        $videoPlugin = new AsfVideoPlugin();

        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test.wmv';
        $videoPlugin->setFile($pathToFile);

        $attributes = $videoPlugin->getAttributes();
        $this->assertEquals('51890000', $attributes['playDuration']);
        $this->assertEquals('3100', $attributes['preRoll']);
        $this->assertEquals('48', $attributes['dataPacketsCount']);
    }

    public function testPluginAnalysesVideoLengthForAsfFiles() {
        $videoPlugin = new AsfVideoPlugin();

        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test.asf';
        $videoPlugin->setFile($pathToFile);

        $attributes = $videoPlugin->getAttributes();
        $this->assertEquals('52420000', $attributes['playDuration']);
        $this->assertEquals('3100', $attributes['preRoll']);
    }
}