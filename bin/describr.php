<?php
/**
 * Quick script intended for command line usage to show how to use Describr and
 * to allow quick inspection of files
 * 
 * This is the standalone version that does not need to be installed via PEAR
 * and does not require the BoxUK Autoload library.
 * 
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */

// Reference the describr bootstrap
include __DIR__ . '/../lib/bootstrap.php';

$cli = new \BoxUK\Describr\Cli();
$cli->run($argv);