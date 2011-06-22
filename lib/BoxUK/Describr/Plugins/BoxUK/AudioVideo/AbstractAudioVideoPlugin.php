<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

/**
 * Base plugin for automatically describing audio/video
 *
 * Requires http://code.google.com/p/php-reader/wiki/ID3v1
 *
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 * @link      http://code.google.com/p/php-reader/ Library used by this plugin
 * @link      http://framework.zend.com/ Library used by this plugin
 */
abstract class AbstractAudioVideoPlugin extends \BoxUK\Describr\Plugins\AbstractPlugin {

    /**
     * This function is common to audio and video as mpeg can be video/audio
     */
    protected function addMpegLengthInformationToAttributes() {
        try {
            $ps = new \Zend_Media_Mpeg_Ps($this->fullPathToFileOnDisk);
            $this->attributes['length'] = $ps->getLength();
            $this->attributes['length_formatted'] = $ps->getFormattedLength();
        } catch (\Zend_Media_Mpeg_Exception $e) {
            $this->attributes['errors'][] = 'Could not read MPEG: ' . $e->getMessage();
        }
    }
}