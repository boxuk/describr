<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Plugin for automatically describing videos
 *
 * Apple QuickTime, Apple iTunes AAC, and MPEG-4 Video
 * (m4a|mp4|..)
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 * @link      http://code.google.com/p/php-reader/ Library used by this plugin
 * @link      http://framework.zend.com/ Library used by this plugin
 */
class Iso14496VideoPlugin extends \BoxUK\Describr\Plugins\BoxUK\AudioVideo\AbstractAudioVideoPlugin {

    /**
     * Make sure that this plugin has everything that it needs - PHPReader
     * must be installed
     *
     * @throws UnmetDependencyException If a dependency is not met
     */
    public function checkDependencies() {
        if(!class_exists('\Zend_Media_Iso14496')) {
            throw new UnmetDependencyException('Class Zend_Media_Iso14496 is not loaded - please ensure the php-reader library is loaded');
        }
    }

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'video/quicktime',
            'video/mp4',
        );
    }        
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'mp4',
            'm4a',
        );
    }
    
    /**
     * Use \Zend_Media_Iso14496 to gather attributes for the file
     */
    protected function setAttributes() {
        $this->addIso14496InformationToAttributes();
    }

    /**
     * Add the Info from \Zend_Media_Iso14496 to the attributes collection
     */
    protected function addIso14496InformationToAttributes() {
        try {
            $isom = new \Zend_Media_Iso14496($this->fullPathToFileOnDisk);
            $this->attributes['duration'] = $isom->moov->mvhd->duration;
            $this->attributes['timescale'] = $isom->moov->mvhd->timescale;

        } catch (\Zend_Media_Iso14496_Exception $e) {
            $this->attributes['errors'][] = 'Could not read file using Iso14496 parser: ' . $e->getMessage();
        }
    }
}