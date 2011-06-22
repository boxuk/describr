<?php

namespace BoxUK\Describr\Plugins\BoxUK;

use BoxUK\Describr\FileNotFoundException;

/**
 * Class that takes an image filename and picks the dominant colour from that
 * image. It works by resizing it to 1px square then picking the colour that
 * is left. It's therefore not bulletproof but generally gives a good idea.
 *
 * This class uses a Unix RGB file to convert from RGB values to colour names
 *
 * Uses {project root}/resources/rgb.txt, which is a Unix style RBG file
 *
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
class ImageMainColourPicker
{

    /**
     * Resize an image to 1px x 1px then pick the colour of that pixel
     * @param string $fullPathToFileOnDisk e.g. /tmp/foo.png
     * @return string e.g. "SeaGreen"
     */
    public function calculateMainColourInImage($fullPathToFileOnDisk) {

        $colourName = null;

        $fileExtension = \BoxUK\Describr\Helper\FileHelper::getFileExtension($fullPathToFileOnDisk);

        $extForFn = str_ireplace('jpg', 'jpeg', $fileExtension);
        $fn = "imagecreatefrom$extForFn";

        if (function_exists($fn)) {
            $im = $fn($fullPathToFileOnDisk);
            // resize the image to 1px
            $onePixelVersionOfImage = imagecreatetruecolor(1,1);
            imagecopyresized($onePixelVersionOfImage, $im, 0, 0, 0, 0, 1, 1, imagesx($im), imagesy($im));

            // Extract the colour information from the 1pixel version of this file
            $colour = imagecolorat($onePixelVersionOfImage, 0, 0);
            $colours = imagecolorsforindex($onePixelVersionOfImage, $colour);
            $colourName = $this->rgbColourToString($colours['red'], $colours['green'], $colours['blue']);
            if ($colourName) {
                // Switch "DarkOrange2" to "DarkOrange" etc
                $colourName = str_replace(
                    array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0'),
                    '', $colourName
                );
            }

            // clean up resources
            unset($im);
            unset($onePixelVersionOfImage);
        }

        return $colourName;
    }

    /**
     * Convert an RGB colour to a string representation like "SeaGreen"
     *
     * @param int $r Red, 0-255
     * @param int $g Green, 0-255
     * @param int $b Blue, 0-255
     *
     * @return string Description of the colour
     * 
     * @throws FileNotFoundException If cannot open RGB lookup file
     */
    protected function rgbColourToString($r, $g, $b) {

        $rgbLookupFile = dirname(__FILE__) . '/../../../../../resources/rgb.txt';
        if (!file_exists($rgbLookupFile)) {
            throw new FileNotFoundException("Cannot open RGB to colour name lookup file $rgbLookupFile");
        }
        // Open the file, or return silently
        if (!$fp = fopen($rgbLookupFile, 'r')) {
            return false;
        }

        $match = '';

        // How far each pixel is from our desired $rgb colour
        $distanceFromColor = sqrt(3.0) * 255;

        // Iterate through the rgb loopup file, checking for precise or close
        // matches
        while (!feof($fp)) {
            $this->processLine(fgets($fp), $distanceFromColor, $match, $r, $g, $b);
            
        }
        fclose($fp);

        return ucfirst($match);
    }
    
    protected function processLine($line, &$distanceFromColor, &$match, $r, $g, $b) {
        if ($line[0] === '!') {
            return;
        }
        list($r1, $g1, $b1, $colorName, $colorExtra1, $colorExtra2, $colorExtra3) = sscanf($line, "%d %d %d %s %s %s %s");
        // Because sscanf uses spaces as delimiters, rebuild the search terms
        if ($colorExtra1) {
            $colorName = $colorName . ucfirst($colorExtra1);
        }
        if ($colorExtra2) {
            $colorName = $colorName . ucfirst($colorExtra2);
        }
        if ($colorExtra3) {
            $colorName = $colorName . ucfirst($colorExtra3);
        }

        if (stripos($colorName, 'gray') > -1) {
            return;
        }

        // Check for exact match
        if ($r1 === $r && $g1 === $g && $b1 === $b) {
            $match = $colorName;
            return;
        }

        // Check for close match
        $newdist = sqrt(pow($r-$r1, 2) + pow($g-$g1, 2) + pow($b-$b1, 2));
        // It seems to prefer to tag to greys! So, lets make those less taggable
        if (stripos($colorName, 'grey') > -1) {
            $newdist += 100;
        }
        if ($newdist < $distanceFromColor) {
            // Closer than any other match so far so update our response
            // value
            $match = $colorName;
            $distanceFromColor = $newdist;
        }
    }
        
}