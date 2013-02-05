<?php

namespace BoxUK\Describr;

/**
 * Class that describes a media file. An instance of this is returned to the
 * user when they submit a media file to the Facade.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class MediaFileAttributes
{

    /**
     * @var array key=>value array
     */
    private $pluginResults = array();

    /**
     * Plugins are named by their entire namespaced path, such as
     * '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin'. We allow users to retrieve
     * by e.g. 'BoxUK\General' for convenience by automatically extracting a
     * shorter version of the name in the setPluginResults function
     *
     * @var array
     */
    private $lookupFromFullToShortPluginName = array();

    /**
     * Set the results for a given plugin. Overwrites anything that already
     * exists for that plugin.
     *
     * @param string $pluginName The name of the plugin, e.g. \BoxUK\Describr\Plugins\BoxUK\GeneralPlugin
     * @param array  $results    The results of the plugin identified by $pluginName
     */
    public function setPluginResults($pluginName, array $results)
    {
        $shortName = $this->shortenPluginName($pluginName);
        $this->pluginResults[$shortName] = $results;
    }

    /**
     * Create a "short name" lookup by first stripping the namespace and then
     * stripping the trailing "Plugin"
     *
     * @param string $pluginName e.g. '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin'
     * @return string Shortened name, e.g. 'BoxUK\General'
     */
    private function shortenPluginName($pluginName)
    {
        $shortName = \str_replace('\\BoxUK\\Describr\\Plugins\\', '', $pluginName);
        $shortName = \str_replace('Plugin', '', $shortName);
        $this->lookupFromFullToShortPluginName[$pluginName] = $shortName;
        return $shortName;
    }

    /**
     * @param string   $pluginName Name of the plugin to check for, e.g.
     * '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin' or its short version
     * 'BoxUK\General'
     * @return boolean Whether this collection contains the named plugin
     */
    public function hasPlugin($pluginName)
    {
        $pluginName = $this->getShortPluginNameIfFullNameIsUsed($pluginName);
        return \array_key_exists($pluginName, $this->pluginResults);
    }

    /**
     * Converts long plugin name to its short version, e.g. 'BoxUK\General'
     *
     * @param String $pluginName e.g. e.g. '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin'
     *
     * @return  e.g. 'BoxUK\General'
     */
    private function getShortPluginNameIfFullNameIsUsed($pluginName)
    {
        if (\array_key_exists($pluginName, $this->lookupFromFullToShortPluginName)) {
            $pluginName = $this->lookupFromFullToShortPluginName[$pluginName];
        }
        return $pluginName;
    }

    /**
     * @param string $pluginName Name of the plugin to check for
     * @return array|null The results if they could be found, or null if
     * no plugin was found of name $pluginName
     */
    public function getPluginResults($pluginName)
    {
        if (!$this->hasPlugin($pluginName)) {
            return null;
        }
        $pluginName = $this->getShortPluginNameIfFullNameIsUsed($pluginName);
        return $this->pluginResults[$pluginName];
    }

    /**
     * @return array All the plugins that have been used, e.g.
     * ['BoxUK\Image\Image', 'BoxUK\General', 'Custom\Foo']
     */
    public function listPlugins()
    {
        return \array_keys($this->pluginResults);
    }

    /**
     * @return array All the plugins that have been used, e.g.
     * ['\BoxUK\Describr\Plugins\BoxUK\Image\ImagePlugin', '\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin', '\BoxUK\Describr\Plugins\Custom\FooPlugin']
     */
    public function listFullPluginNames()
    {
        return \array_keys($this->lookupFromFullToShortPluginName);
    }

    /**
     * @return array The plugin results, rendered as array of the format:
     * <code>
     * Array
     * (
     *     [\BoxUK\Describr\Plugins\BoxUK\Image\ImagePlugin] => Array
     *         (
     *             [errors] => Array
     *                 (
     *                 )
     *             [orientation] => Landscape
     *             [dimensions] => Small
     *             [mainColour] => SeaGreen
     *             [fileSize] => Extra Small
     *         )
     *
     *     [\BoxUK\Describr\Plugins\BoxUK\GeneralPlugin] => Array
     *         (
     *             [errors] => Array
     *                 (
     *                 )
     *
     *             [extension] => jpg
     *             [type] => image
     *             [mimeType] => image/jpeg
     *             [fileSizeInBytes] => 11645
     *         )
     *
     * )
     * </code>
     */
    public function toArray()
    {
        return $this->pluginResults;
    }
}
