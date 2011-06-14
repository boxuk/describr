<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

/**
 * Base plugin for automatically describing audio/video
 *
 * Requires http://code.google.com/p/php-reader/wiki/ID3v1
 *
 * @package   BoxUK\Describr\Plugins\BoxUK\AudioVideo
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
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