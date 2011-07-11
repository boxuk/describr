<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Plugin for automatically describing videos
 *
 * (mpg|mpeg|vob|evo|..)
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 * @link      http://code.google.com/p/php-reader/ Library used by this plugin
 * @link      http://framework.zend.com/ Library used by this plugin
 */
class MpegVideoPlugin extends \BoxUK\Describr\Plugins\BoxUK\AudioVideo\AbstractAudioVideoPlugin {

    /**
     * Make sure that this plugin has everything that it needs - PHPReader
     * must be installed
     *
     * @throws UnmetDependencyException If a dependency is not met
     */
    public function checkDependencies() {
        if(!class_exists('\Zend_Media_Mpeg_Abs') || !class_exists('\Zend_Media_Mpeg_Ps')) {
            throw new UnmetDependencyException('Classes Zend_Media_Mpeg_Abs and Zend_Media_Mpeg_Ps must both be loaded - please ensure the php-reader library is on the include path if you wish to use this plugin');
        }
    }

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'video/mpeg',
        );
    }
        
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'abs',
            'mp1',
            'mp2',
            'mp3',
            'mpg',
            'mpeg',
            'vob',
            'evo',
        );
    }

    /**
     * Gather the Mpeg length information
     */
    protected function setAttributes() {
        $this->addMpegLengthInformationToAttributes();
    }
}