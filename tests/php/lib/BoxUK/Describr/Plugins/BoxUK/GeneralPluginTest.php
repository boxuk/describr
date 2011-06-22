<?php

namespace BoxUK\Describr\Plugins\BoxUK;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2011. Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class GeneralPluginTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckDependenciesShouldAlwaysPass() {
        $plugin = new GeneralPlugin();
        $plugin->checkDependencies();
    }

    public function testGetAttributesFillsInExtensionTypeAndMimeType() {
        $plugin = new GeneralPlugin();
        $filePath = dirname(__FILE__) . '/../../../../../../resources/mostlyGreen.jpg';
        $plugin->setFile($filePath);

        $attributes = $plugin->getAttributes();
        $this->assertEquals('jpg', $attributes['extension']);
        $this->assertEquals('image', $attributes['type']);
        $this->assertStringStartsWith('image/jpeg', $attributes['mimeType']);
    }
}