<?php

namespace BoxUK\Describr;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Facade over the application for ease of use. Can automatically load all
 * available plugins.
 *
 * @package   BoxUK\Describr
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2010, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */
class Facade
{
    
    /**
     * @var array All the plugins that are available under the plugins directory
     */
    private $availablePlugins = array();

    /**
     * Create the facade. This automatically goes through all *Plugin.php
     * files within the plugins directory and records that they are available.
     * Therefore, when a filename is subsequently passed in to describeFile,
     * the system can go through all the plugins and check the MIME types that
     * those plugins support to see whether to include them in the plugin chain
     * for that particular request
     */
    public function __construct() {
        $pluginDir = $this->getPluginDir();
        $it = new \RecursiveDirectoryIterator($pluginDir);
        $ds = '[\\\\\/]';
        
        foreach(new \RecursiveIteratorIterator($it) as $file => $fileInfo) {
            if (preg_match('/^(.*)' . $ds . '(\w+Plugin)\.php$/', $file, $aMatches)) {
                list( $filePathToPlugin, $IGNORE, $model) = $aMatches;

                if (strpos($model, 'Abstract') !== false) {
                    continue;
                }

                $packageAndClassOfPlugin = $this->convertFilepathToPackageAndClass($filePathToPlugin);
                $this->availablePlugins[] = $packageAndClassOfPlugin;
            }
        }
    }

    /**
     * @return string Full path to where the plugins are
     */
    private function getPluginDir() {
        return dirname(__FILE__) . '/Plugins';
    }

    /**
     * Convert something like /opt/BoxUK/describr/lib/BoxUK/Describr/plugins/BoxUK/ImagePlugin/ImagePlugin.php
     * to something the classloader will understand like \BoxUK\Describr\Plugins\BoxUK\ImagePlugin\ImagePlugin
     *
     * @param string $filePathToPlugin e.g. /opt/BoxUK/describr/lib/BoxUK/Describr/plugins/BoxUK/ImagePlugin/ImagePlugin.php
     *
     * @return string  e.g. \BoxUK\Describr\Plugins\BoxUK\ImagePlugin\ImagePlugin
     */
    private function convertFilepathToPackageAndClass( $filePathToPlugin) {
        $packageAndClass = \str_replace($this->getPluginDir(), '', $filePathToPlugin);
        $packageAndClass = \str_replace('.php', '', $packageAndClass);
        $packageAndClass = '\BoxUK\Describr\Plugins' . \str_replace('/', '\\', $packageAndClass);
        return $packageAndClass;
    }

    /**
     * @return array The class names of all available plugins in a flat array
     * of strings like '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin'
     */
    public function listAvailablePlugins() {
        return $this->availablePlugins;
    }

    /**
     * Describe a file using all the relevant plugins that report they match
     * the mime type of the file at $fullPathToFileOnDisk
     *
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     *
     * @return MediaFileAttributes The information found out about the file at $fullPathToFileOnDisk
     *
     * @throws FileNotFoundException If the file at $fullPathToFileOnDisk is not
     * readable
     */
    public function describeFile($fullPathToFileOnDisk) {
        if (!file_exists($fullPathToFileOnDisk)) {
            throw new FileNotFoundException("$fullPathToFileOnDisk not found or not accessible");
        }
        
        // ascertain MIME type so we can check what plugins to use
        $mimeType = \BoxUK\Describr\Helper\FileHelper::getMimeType($fullPathToFileOnDisk);
        $extension = \BoxUK\Describr\Helper\FileHelper::getFileExtension($fullPathToFileOnDisk);

        $mediaFileAttributes = new MediaFileAttributes();

        foreach($this->availablePlugins as $pluginName) {
            /* @var $plugin plugins\Plugin */
            $plugin = new $pluginName();
            if ( $plugin->supportsFile($mimeType, $extension)) {
                try {
                    $plugin->checkDependencies();
                } catch(UnmetDependencyException $e) {
                    $mediaFileAttributes->addError($pluginName, $e);
                    continue;
                }
                $plugin->setFile($fullPathToFileOnDisk);
                $mediaFileAttributes->setPluginResults($pluginName, $plugin->getAttributes());
            }
        }

        return $mediaFileAttributes;
    }

    /**
     * Describe a file using all the relevant plugins that report they match
     * the mime type of the file at $fullPathToFileOnDisk
     *
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     *
     * @return array The information found out about the file at $fullPathToFileOnDisk
     *
     * @throws FileNotFoundException If the file at $fullPathToFileOnDisk is not
     * readable
     */
    public function describeFileAsArray($fullPathToFileOnDisk) {
        return $this->describeFile($fullPathToFileOnDisk)->toArray();
    }
}