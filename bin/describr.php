<?php
/**
 * Quick script intended for command line usage to show how to use Describr and
 * to allow quick inspection of files
 * 
 * @package   BoxUK\Describr
 * @author    Box UK <info@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0
 */

// Reference the describr bootstrap
include __DIR__ . '/../lib/bootstrap.php';

// Create an instance of the describr facade - this is the class that wraps all
// the functionality of describr and should be the only class you need most
// of the time
$describr = new \BoxUK\Describr\Facade();

// analyse a file. This example assumes you've created a file foo.txt in the
// same directory as your php file
$file = $argv[1];
$fullPathToFile = $argv[1]; // __DIR__ . '/../' . $file;

$response = $describr->describeFileAsArray($fullPathToFile);

// Show what describr was able to work out about this file
var_dump($response);