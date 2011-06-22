<?php

namespace BoxUK\Describr\Plugins\BoxUK;

/**
 * Plugin for automatically describing plain text files
 *
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class PlainTextPlugin extends \BoxUK\Describr\Plugins\AbstractPlugin
{

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'text/plain',
            'text/css',
            'text/html',
            'text/tab-separated-values',
            'text/x-vcard',
            'text/csv',
            'text/comma-separated-values',
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'txt',
            'xml',
            'xsl',
            'php',
            'php3',
            'c',
            'cpp',
            'java',
            'cs',
            'py',
            'js',
        );
    }
    
    /**
     * @return array With keys 'tooLarge' if over maximum threshold size,
     * or keys 'lines', 'characters', 'words' if the file is within the
     * threshold size
     */
    protected function setAttributes() {
        $maxSizeInBytes = $this->getConfigurationValue('maxSizeInBytes', 1048576);
        if (filesize($this->fullPathToFileOnDisk) > $maxSizeInBytes) {
            $this->attributes['errors'] = "This file is more than $maxSizeInBytes bytes so the system did not process it.";
            return;
        }

        $fileContents = file_get_contents($this->fullPathToFileOnDisk);
        $this->attributes['lines'] = count(file($this->fullPathToFileOnDisk));
        $this->attributes['characters'] = strlen($fileContents);
        $this->attributes['words'] = str_word_count($fileContents);
    }
}