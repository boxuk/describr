<?php

namespace BoxUK\Describr\Plugins;

use BoxUK\Describr\FileNotFoundException;

/**
 * Base class for a plugin. Defines the interfaces that have to be filled in by
 * each plugin.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * Default implementation - assumes no dependencies. Override
     * if necessary
     */
    public function checkDependencies() {}

    /**
     * @var string Full path to the file on the disk
     */
    protected $fullPathToFileOnDisk;

    /**
     * @var string e.g. image/jpg
     */
    protected $mimeTypeOfCurrentFile = null;

    /**
     * Look in the configuration for the value $valueName
     *
     * @param string $valueName The key in the ini file you're interested in
     * @param mixed $defaultValue The default value if $valueName is not defined
     * in the ini file
     *
     * @return mixed The value from the configuration of this plugin, or the $defaultValue
     *
     * @throws ConfigurationNotLoadedException If configuration was not loaded
     */
    //@codingStandardsIgnoreStart
    public function getConfigurationValue($valueName, $defaultValue) {
        return $defaultValue;
    }
    //@codingStandardsIgnoreEnd

    /**
     * Set the file that this plugin is working on. This automatically extracts
     * the mime type. It will automatically load configuration if configuration
     * has not been loaded already
     *
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     *
     * @throws FileNotFoundException If file $fullPathToFileOnDisk not found
     */
    public function setFile($fullPathToFileOnDisk) {
        if (!file_exists($fullPathToFileOnDisk)) {
            throw new FileNotFoundException("$fullPathToFileOnDisk not found or not accessible");
        }
        $this->fullPathToFileOnDisk = $fullPathToFileOnDisk;
        $this->mimeTypeOfCurrentFile = \BoxUK\Describr\Helper\FileHelper::getMimeType($this->fullPathToFileOnDisk);
    }

    /**
     * Returns a media file's file extension from its filename
     *
     * @return string e.g. "png"
     */
    protected function getFileExtension() {
        return \BoxUK\Describr\Helper\FileHelper::getFileExtension($this->fullPathToFileOnDisk);
    }

    /**
     * @return string|null A rough categorisation of the file, such as
     * "document", "image" or "audio"
     */
    protected function getFileType() {
        return \BoxUK\Describr\Helper\FileHelper::getFileTypeFromExtension($this->fullPathToFileOnDisk);
    }
    
    /**
     * Does this plugin support the file? Two checks are made, if either check
     * passes then true is returned.
     * 
     * @param type $mimeType e.g. "text/plain"
     * @param type $extension Not including the ".", e.g. "wmf" is OK but ".wmf" is not
     * 
     * @return bool TRUE if this plugin supports $mimeType and/or files with extension $extension,
     *     FALSE if it supports neither
     */
    public function supportsFile($mimeType, $extension) {
        if($this->supportsMimeType($mimeType)) {
            return true;
        }
        if($this->supportsFileExtension($extension)) {
            return true;
        }
        
        return false;
    }

    /**
     * @param string $mimeType e.g. 'image/jpeg'
     * @return boolean True if this plugin can operate on files of mime type
     * $mimeType. If the plugin matches "*" as a mime type, all files can be
     * run through it.
     */
    public function supportsMimeType($mimeType) {
        $matchingMimeTypes = $this->getMatchingMimeTypes();
        if (\in_array('*', $matchingMimeTypes)) {
            return true;
        }
        if (\in_array($mimeType, $matchingMimeTypes)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param type $extension Not including the ".", e.g. "wmf" is OK but ".wmf" is not
     * @return boolean True if this plugin can operate on files of extension
     * $extension.
     */
    public function supportsFileExtension($extension) {
        $matchingFileExtensions = $this->getMatchingFileExtensions();
        if (\in_array($extension, $matchingFileExtensions)) {
            return true;
        }
        
        return false;
    }

    /**
     * Reset the attributes to an empty collection
     */
    protected function resetAttributes() {
        $this->attributes = array(
            'errors' => array()
        );
    }
    
    /**
     * Add an error that occurred on this plugin
     * @param string $errorMsg 
     */
    protected function addError($errorMsg) {
        $this->attributes['errors'][] = $errorMsg;
    }
    
    /**
     * @return array Array of error messages
     */
    public function getErrors() {
        return $this->attributes['errors'];
    }
    

    /**
     * Set up the internal attributes collection from everything this plugin
     * can work out about the file. Every plugin must define this.
     */
    protected abstract function setAttributes();

    /**
     * Reset the attributes, set them, then return them. If dependencies are not
     * met, we may have some exception information
     *
     * @return array The attributes this plugin is able to collect on the loaded
     * file
     */
    public function getAttributes() {
        $this->resetAttributes();
        
        try {
            $this->checkDependencies();
            $this->setAttributes();
        } catch(UnmetDependencyException $e) {
            $this->addError("This plugin matched the file " . $this->fullPathToFileOnDisk 
                    . ", but the dependencies could not be matched. Details:\n"
                    . $e->__toString());
        }
         catch(Exception $e) {
            $this->addError($e->__toString());
        }
        
        return $this->attributes;
    }
}