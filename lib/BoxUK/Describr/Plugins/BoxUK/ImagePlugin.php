<?php

namespace BoxUK\Describr\Plugins\BoxUK;

use BoxUK\Describr\Plugins\UnmetDependencyException;

/**
 * Plugin for automatically describing an Image file.
 * 
 * Requires GD to be installed.
 *
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class ImagePlugin extends \BoxUK\Describr\Plugins\AbstractPlugin
{
    /**
     * @var ImageMainColourPicker
     */
    protected $picker;

    /**
     * Make sure that this plugin has everything that it needs - i.e. GD
     *
     * @throws UnmetDependencyException If a dependency is not met
     */
    public function checkDependencies() {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new UnmetDependencyException('GD is not installed');
        }
    }

    /**
     * @return array Types of file this plugin can determine information about
     */
    public function getMatchingMimeTypes() {
        return array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
        );
    }
    
    /**
     * @return array File extensions this plugin can determine information about.
     * The "." is not included, so "wmf" is OK, ".wmf" is not
     */
    public function getMatchingFileExtensions() {
        return array(
            'jpg',
            'png',
            'gif',
            'bmp',
        );
    }

    /**
     * @return array With keys "fileSize", "orientation", "dimensions",
     * "mainColour" - some keys may be absent
     */
    protected function setAttributes() {
        $this->attributes = array_merge(
            $this->getAutoTagsByOrientationAndDimensions(),
            $this->getAutoTagsByFileColour()
        );
    }

    // Implementation methods

    /**
     * Automatically tag this file by file colour, adding the array key 'mainColour
     *
     * @return array Single element array containing a colour name. Empty if the
     * colour name could not be determined.
     */
    protected function getAutoTagsByFileColour() {
        $aAutoTag = array();

        $mainColour = $this->calculateMainColourInImage();
        if ($mainColour) {
            $aAutoTag['mainColour'] = $mainColour;
        }

        return $aAutoTag;
    }

    /**
     * Attempt to add information to $aAutoTag on what the main colour on an
     * image is by resizing the image to 1px in memory and picking the colour
     * from that pixel
     */
    private function calculateMainColourInImage() {
        if(!$this->picker) {
            $this->picker = new ImageMainColourPicker();
        }
        return $this->picker->calculateMainColourInImage($this->fullPathToFileOnDisk);
    }

    /**
     * Automatically tag this object to a category based on the orientation:
     *  portrait
     *  landscape
     *  square
     *
     * Create the category if it does not exist.
     *
     * Does nothing if height or width are not set on this object.
     *
     * @return array With keys 'orientation' and 'dimensions'
     */
    private function getAutoTagsByOrientationAndDimensions() {

        // determine width of image
        list($widthInPx, $heightInPx) = getimagesize($this->fullPathToFileOnDisk);

        // Guard conditions
        if (   is_null  ($heightInPx) || !is_numeric($heightInPx)
           || is_null  ($widthInPx)  || !is_numeric($widthInPx))
        {
            return array();
        }

        return array(
            'orientation' => $this->getAutoTagsByOrientation($widthInPx, $heightInPx),
            'dimensions' => $this->getAutoTagsByDimensions($widthInPx, $heightInPx)
        );
    }

    /**
     * Using the height and width, calculate the orientation (square, portrain, landscape)
     *
     * @param int $widthInPx Width of image in pixels
     * @param int $heightInPx Height of image in pixels
     * @return string 'Square', 'Portrait' or 'Landscape'
     */
    private function getAutoTagsByOrientation($widthInPx, $heightInPx) {

        if ($heightInPx === $widthInPx) {
            return 'Square';
        } elseif ($heightInPx > $widthInPx) {
            return 'Portrait';
        } elseif ($heightInPx < $widthInPx) {
            return 'Landscape';
        }
    }

    /**
     * Given dimensions of an image, calculate its size (e.g. Small)
     *
     * @param int $widthInPx Width of image in pixels
     * @param int $heightInPx Height of image in pixels
     * @return string e.g. 'Extra Large', 'Large', 'Medium', 'Small', 'Extra Small'
     */
    private function getAutoTagsByDimensions($widthInPx, $heightInPx) {
        $dim = $heightInPx * $widthInPx;
        $dimensions = 'Extra Large';

        if ($dim < $this->getConfigurationValue('extraSmallMaxDimensions', (320 * 240))) {
            $dimensions = 'Extra Small';
        } else if ($dim < $this->getConfigurationValue('smallMaxDimensions', (640 * 480))) {
            $dimensions = 'Small';
        } else if ($dim < $this->getConfigurationValue('mediumMaxDimensions', (1024 * 768))) {
            $dimensions = 'Medium';
        } else if ($dim < $this->getConfigurationValue('largeMaxDimensions', (1280 * 1024))) {
            $dimensions = 'Large';
        }

        return $dimensions;
    }
}