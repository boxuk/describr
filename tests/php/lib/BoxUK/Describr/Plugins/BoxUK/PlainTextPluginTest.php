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
class PlainTextPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testHasNoDependencies() {
        $textPlugin = new PlainTextPlugin();
        $textPlugin->checkDependencies();
    }

    public function testMatchingMimeTypesAreAllTextTypes() {
        $textPlugin = new PlainTextPlugin();
        $mimeTypes = $textPlugin->getMatchingMimeTypes();
        
        $this->assertContains('text/plain', $mimeTypes);
        $this->assertContains('text/css', $mimeTypes);
        $this->assertContains('text/html', $mimeTypes);
        $this->assertContains('text/tab-separated-values', $mimeTypes);
        $this->assertContains('text/x-vcard', $mimeTypes);
        $this->assertContains('text/csv', $mimeTypes);
        $this->assertContains('text/comma-separated-values', $mimeTypes);
    }

    public function testHugeTextFilesCannotBeAnalysedByThePlugin() {
        $textPlugin = new PlainTextPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/hugeTextFile.txt';
        $textPlugin->setFile($pathToFile);

        $attributes = $textPlugin->getAttributes();
        $this->assertArrayHasKey('errors', $attributes);
        $this->assertGreaterThan(0, count('errors'));
    }

    public function testTextFilesCanBeAnalysedByThePlugin() {
        $textPlugin = new PlainTextPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/test.txt';
        $textPlugin->setFile($pathToFile);

        $attributes = $textPlugin->getAttributes();
        $this->assertArrayNotHasKey('tooLarge', $attributes);
        $this->assertArrayHasKey('lines', $attributes);
        $this->assertArrayHasKey('characters', $attributes);
        $this->assertArrayHasKey('words', $attributes);
    }

    public function testPluginCountsLines() {
        $textPlugin = new PlainTextPlugin();
        $pathToFile = dirname(__FILE__) . '/../../../../../../resources/20lines.txt';
        $textPlugin->setFile($pathToFile);

        $attributes = $textPlugin->getAttributes();
        $this->assertEquals(20, $attributes['lines']);
        $this->assertEquals(40, $attributes['characters']);
        $this->assertEquals(0, $attributes['words']);
    }
}