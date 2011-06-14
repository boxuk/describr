<?php

namespace BoxUK\Describr\Plugins;

/**
 * Interface for a plugin. Defines the interfaces that have to be filled in by
 * each plugin.
 *
 * The overhead of instantiation should be VERY low for a plugin, so please do
 * not do anything significant in the constructor. This means the code remains
 * free of static methods, is testable, and is performant.
 *
 * @package   BoxUK\Describr\Plugins
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
interface Plugin
{

    /**
     * Make sure that this plugin has everything that it needs
     *
     * @throws UnmetDependencyException If a dependency is not met
     *
     * @return void
     */
    public function checkDependencies();

    /**
     * For each plugin, you must define an array of matching mime types
     *
     * Mime types are NOT mutually exclusive between plugins
     *
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes();
    
    /**
     * For each plugin, you must define an array of matching file extensions
     *
     * File extensions are NOT mutually exclusive between plugins
     *
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions();

    /**
     * @param string $mimeType e.g. image/jpeg
     * @return boolean True if this plugin can operate on files of mime type
     * $mimeType
     */
    public function supportsMimeType($mimeType);
    
    /**
     * Look in the configuration for the value $valueName
     *
     * @param string $valueName The key in the ini file you're interested in
     * @param mixed $defaultValue The default value if $valueName is not defined
     * in the ini file
     * @return mixed The value from the ini file, or the $defaultValue
     */
    public function getConfigurationValue($valueName, $defaultValue);

    /**
     * Set the file that this plugin is concerned with
     *
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     *
     * @throws \BoxUK\Describr\FileNotFoundException If file is not found
     *
     */
    public function setFile($fullPathToFileOnDisk);

    /**
     * @return array The attributes of the file at $fullPathToFileOnDisk
     */
    public function getAttributes();
}