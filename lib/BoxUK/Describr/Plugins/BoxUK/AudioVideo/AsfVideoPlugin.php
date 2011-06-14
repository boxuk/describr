<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Plugin for automatically describing videos
 *
 * @package   BoxUK\Describr\Plugins\BoxUK\AudioVideo
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
class AsfVideoPlugin extends \BoxUK\Describr\Plugins\BoxUK\AudioVideo\AbstractAudioVideoPlugin {

    /**
     * Make sure that this plugin has everything that it needs - PHPReader
     * must be installed
     *
     * @throws UnmetDependencyException If a dependency is not met
     */
    public function checkDependencies() {
        if(!class_exists('\Zend_Media_Asf')) {
            throw new UnmetDependencyException('Class Zend_Media_Asf is not loaded - please ensure the php-reader library is loaded');
        }
    }

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'video/x-la-asf',
            'video/x-ms-asf',
            'video/x-ms-wm',
            'video/x-ms-wmv',
            'video/x-ms-wmx',
            'video/x-ms-wvx',
            'video/x-msvideo',
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'asf',
            'wma',
            'wmv',
        );
    }

    /**
     * @link      http://code.google.com/p/php-reader/wiki/ASF
     *
     * The Advanced Systems Format is a base format for media file formats such
     * as Microsoft Windows Audio (WMA) and Microsoft Windows Video (WMV).
     * The Zend_Media_Asf class is capable of parsing all the file information.
     */
    protected function setAttributes() {
        try {
            $asf = new \Zend_Media_Asf($this->fullPathToFileOnDisk);

            $this->attributes['playDuration'] = $asf->header->fileProperties->playDuration;
            $this->attributes['preRoll'] = $asf->header->fileProperties->preroll;
            $this->attributes['creationDate'] = $asf->header->fileProperties->creationDate;
            $this->attributes['maximumBitRate'] = $asf->header->fileProperties->creationDate;
            $this->attributes['dataPacketsCount'] = $asf->header->fileProperties->dataPacketsCount;

        } catch (\Zend_Media_Asf_Exception $e) {
            $this->attributes['errors'][] = 'Could not read file using ASF parser: ' . $e->getMessage();
        }
    }
}