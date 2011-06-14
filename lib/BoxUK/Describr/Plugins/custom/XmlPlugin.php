<?php

namespace BoxUK\Describr\Plugins\custom;

/**
 * Plugin for automatically describing XML files
 *
 * @package   BoxUK\Describr\Plugins\BoxUK
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
class XmlPlugin extends \BoxUK\Describr\Plugins\AbstractPlugin
{

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'text/xml'
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions($p_stuff) {
        return array(
            'xml',
            'xsl',
        );
    }
    
    /**
     * @return array With key 'tags' which is a count of tags in this document
     */
    protected function setAttributes() {
        $fileContents = file_get_contents($this->fullPathToFileOnDisk);
        $tagOpenCount = substr_count($fileContents, '<');
        $tagCloseCount = substr_count($fileContents, '</');
        $tagCount = $tagOpenCount - $tagCloseCount;
                
        $this->attributes['tags'] = $tagCount;
    }
}