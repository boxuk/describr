<?php

namespace BoxUK\Describr\Plugins\BoxUK;

/**
 * General plugin that can determine the mime type, file extension, size, name
 * of a file - stuff that is common to files of any type
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class GeneralPlugin extends \BoxUK\Describr\Plugins\AbstractPlugin
{

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            '*',
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            '*'
        );
    }
    
    /**
     * @return array with keys "extension", "type", "mimeType"
     */
    public function setAttributes() {
        $this->attributes['extension'] = $this->getFileExtension();
        $this->attributes['type'] = $this->getFileType();
        $this->attributes['mimeType'] = $this->mimeTypeOfCurrentFile;
        $this->attributes['fileSizeInBytes'] = $this->getFileSizeInBytes();

        $this->addAutoTagsByFileSize();
    }

    // Generic methods 

    /**
     * @return int The number of bytes in the file
     */
    private function getFileSizeInBytes() {
        return filesize($this->fullPathToFileOnDisk);
    }

    /**
     * Set of checks to run before attempting to auto tag
     *
     * @return boolean true if checks failed
     */
    private function guardConditionsForAutoTagByFileSize() {
        $fileExtension = $this->getFileExtension($this->fullPathToFileOnDisk);
        $fileTypeFromExt = $this->getFileType($fileExtension);

        return (!file_exists($this->fullPathToFileOnDisk)
            || !$fileExtension
            || !$fileTypeFromExt
        );
    }

    /**
     * Automatically tag this file by file size, relative to the type of
     * file - e.g. a 1mb jpg is large but a 1mb video is small
     */
    private function addAutoTagsByFileSize() {
        if ($this->guardConditionsForAutoTagByFileSize($this->fullPathToFileOnDisk)) {
            return;
        }

        $sizeInKb = filesize ($this->fullPathToFileOnDisk) / 1024;
        
        $function = 'getSizeOf' . ucfirst($this->getFileType());
        
        $fileSizeDescription = $this->$function($sizeInKb);
        

        $this->attributes['fileSize'] = $fileSizeDescription;
    }
    
    /**
     * How big is $sizeInKb, expressed in natural language?
     * @param type $sizeInKb How big a given file is, in kilobytes
     * @return string e.g. 'Extra Large' or 'Medium'
     */
    protected function getSizeOfImage($sizeInKb) {
        $fileSizeDescription = 'Extra Large';
        if ($sizeInKb < 16) {
            $fileSizeDescription = 'Extra Small';
        }else if ($sizeInKb < 32) {
            $fileSizeDescription = 'Small';
        } elseif ($sizeInKb < 64) {
            $fileSizeDescription = 'Medium';
        } else if ($sizeInKb < 128) {
            $fileSizeDescription = 'Large';
        }
        return $fileSizeDescription;
    }
    
    /**
     * How big is $sizeInKb, expressed in natural language?
     * @param type $sizeInKb How big a given file is, in kilobytes
     * @return string e.g. 'Extra Large' or 'Medium'
     */
    protected function getSizeOfAudio($sizeInKb) {
        return $this->getSizeOfDocument($sizeInKb);
    }
    
    /**
     * How big is $sizeInKb, expressed in natural language?
     * @param type $sizeInKb How big a given file is, in kilobytes
     * @return string e.g. 'Extra Large' or 'Medium'
     */
    protected function getSizeOfDocument($sizeInKb) {
        $fileSizeDescription = 'Extra Large';
        if ($sizeInKb < 32) {
            $fileSizeDescription = 'Extra Small';
        }else if ($sizeInKb < 256) {
            $fileSizeDescription = 'Small';
        } elseif ($sizeInKb < 1024) {
            $fileSizeDescription = 'Medium';
        } else if ($sizeInKb < 2048) {
            $fileSizeDescription = 'Large';
        }
        return $fileSizeDescription;
    }
    
    /**
     * How big is $sizeInKb, expressed in natural language?
     * @param type $sizeInKb How big a given file is, in kilobytes
     * @return string e.g. 'Extra Large' or 'Medium'
     */
    protected function getSizeOfMovie($sizeInKb) {
        $fileSizeDescription = 'Extra Large';
        if ($sizeInKb < 128) {
            $fileSizeDescription = 'Extra Small';
        }else if ($sizeInKb < 512) {
            $fileSizeDescription = 'Small';
        } elseif ($sizeInKb < 2048) {
            $fileSizeDescription = 'Medium';
        } else if ($sizeInKb < 8096) {
            $fileSizeDescription = 'Large';
        }
        return $fileSizeDescription;
    }
}