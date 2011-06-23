<?php
namespace BoxUK\Describr\Plugins\BoxUK\AudioVideo;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Plugin for automatically describing audio
 *
 * Requires http://code.google.com/p/php-reader/wiki/ID3v1
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 * @link      http://code.google.com/p/php-reader/ Library used by this plugin
 * @link      http://framework.zend.com/ Library used by this plugin
 */
class AudioPlugin extends \BoxUK\Describr\Plugins\BoxUK\AudioVideo\AbstractAudioVideoPlugin {

    /**
     * Make sure that this plugin has everything that it needs - PHPReader
     * must be installed
     *
     * @throws UnmetDependencyException If a dependency is not met
     */
    public function checkDependencies() {
        if(!class_exists('\Zend_Media_Id3v1')) {
            throw new UnmetDependencyException('Class Zend_Media_Id3v1 is not loaded - please ensure the php-reader library is loaded');
        }
        if(!class_exists('\Zend_Media_Id3v2')) {
            throw new UnmetDependencyException('Class Zend_Media_Id3v2 is not loaded - please ensure the php-reader library is loaded');
        }
    }

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'audio/mpeg',
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'mp3',
        );
    }

    /**
     * Set up the internal attributes collection from everything this plugin
     * can work out about the file. Every plugin must define this.
     *
     * Collects ID3 tag info, and as much info as it can determine about the
     * MPEG itself
     */
    protected function setAttributes() {
        $this->addId3InformationToAttributes();

        $this->addMpegLengthInformationToAttributes();

        $this->addMpegAbsInformationToAttributes();
    }

    /**
     * Add the keys title, artist, album, comment, year, track and genre
     * to the attributes collection
     */
    protected function addId3InformationToAttributes() {
        try {
            $id3 = new \Zend_Media_Id3v1($this->fullPathToFileOnDisk);
            $this->attributes['title'] = $id3->getTitle();
            $this->attributes['artist'] = $id3->getArtist();
            $this->attributes['album'] = $id3->getAlbum();
            $this->attributes['comment'] = $id3->getComment();
            $this->attributes['year'] = $id3->getYear();
            $this->attributes['track'] = $id3->getTrack();
            $this->attributes['genre'] = $id3->getGenre();
        } catch(\Zend_Media_Id3_Exception $e) {
            $this->attributes['errors'][] = 'Could not read ID3v1 tags ' . $e->getMessage();
        }

        try {
            $id3 = new \Zend_Media_Id3v2($this->fullPathToFileOnDisk);
            $this->attributes['title'] = $id3->tit2->text;
        } catch(\Zend_Media_Id3_Exception $e) {
            $this->attributes['errors'][] = 'Could not read ID3v1 tags ' . $e->getMessage();
        }
    }

    /**
     * This function is common to audio and video as mpeg can be video/audioi
     */
    protected function addMpegAbsInformationToAttributes() {
        try {
            \set_error_handler(function() {});
            $ps = new \Zend_Media_Mpeg_Abs($this->fullPathToFileOnDisk);
            $this->attributes['estimatedBitrate'] = $ps->getBitrateEstimate();
            $this->attributes['duration'] = $ps->getFormattedLengthEstimate();
        } catch (\Zend_Media_Mpeg_Exception $e) {
            $this->attributes['errors'][] = 'Could not read MPEG: ' . $e->getMessage();
        } catch (\Zend_IO_Exception $e) {
            $this->attributes['errors'][] = 'Zend IO exception: ' . $e->getMessage();
        }
        \restore_error_handler();
    }
}