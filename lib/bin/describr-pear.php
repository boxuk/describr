<?php
/**
 * Quick script intended for command line usage to show how to use Describr and
 * to allow quick inspection of files
 *
 * This script is automatically installed when you install Describr through PEAR.
 * If you have not installed through PEAR, ignore this script and use
 * {describrroot}/bin/describr.php instead
 *
 * This script requires the Box UK Autoload library.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.2
 */

// Autoload describr. Requires Box UK autoloader
require_once('BoxUK/Autoload.php');
\BoxUK\Autoload::registerPear();

$cli = new \BoxUK\Describr\Cli();
$cli->run($argv);
