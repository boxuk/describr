<?php

namespace BoxUK\Describr\Plugins;

require_once 'tests/php/bootstrap.php';

/**
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 * @package   BoxUK\Describr\Plugins
 */
class AbstractPluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array The attributes this plugin has determined
     */
    protected $attributes = array();
    
    /**
     * @expectedException BoxUK\Describr\FileNotFoundException
     */
    public function testSetFileThrowsExceptionIfFileNotFound()  {
        $plugin = $this->getMockForAbstractClass('\BoxUK\Describr\Plugins\AbstractPlugin');
        $plugin->setFile('foo/bar');
    }

    public function testSetFileSetsFullPathToFileThePluginIsInterestedIn()  {
        $plugin = $this->getMockForAbstractClass('\BoxUK\Describr\Plugins\AbstractPlugin');
        $plugin->setFile(dirname(__FILE__) . '/../../../../../resources/mostlyGreen.jpg');
    }
}