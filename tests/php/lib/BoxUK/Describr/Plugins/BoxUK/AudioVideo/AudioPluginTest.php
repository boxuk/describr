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
class AudioPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testDependsOnPhpReader() {
        $plugin = new AudioPlugin();
        $plugin->checkDependencies();
    }

    public function testMatchingMimeTypesAreAllAudioTypes() {
        $plugin = new AudioPlugin();
        $mimeTypes = $plugin->getMatchingMimeTypes();

        $this->assertContains('audio/mpeg', $mimeTypes);
    }

    public function testPluginAnalysesMp3sWithId3v1Tags() {
        $plugin = new AudioPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test-id3v1tag.mp3';
        $plugin->setFile($pathToFile);

        $attributes = $plugin->getAttributes();
        $this->assertEquals('Test MP3', $attributes['title']);
        $this->assertEquals('Test artist', $attributes['artist']);
        $this->assertEquals('Test album', $attributes['album']);
        $this->assertEquals('2010', $attributes['year']);
        $this->assertEquals('1', $attributes['track']);
        $this->assertEquals('Other', $attributes['genre']);
        $this->assertContains('Here is a test comment', $attributes['comment']);
    }

    public function testPluginAnalysesMp3sWithId3v2Tags() {
        $plugin = new AudioPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test-id3v2tag.mp3';
        $plugin->setFile($pathToFile);

        $attributes = $plugin->getAttributes();
        $this->assertEquals('test static', $attributes['title']);
    }

    public function testPluginAnalysesMp3sWithoutId3TagsReadableMpegAttributes() {
        $plugin = new AudioPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../../resources/test-no-id3.mp3';
        $plugin->setFile($pathToFile);

        $attributes = $plugin->getAttributes();
        $this->assertContains('80', strval($attributes['estimatedBitrate']));
        $this->assertEquals('0.261', $attributes['duration']);
    }
}