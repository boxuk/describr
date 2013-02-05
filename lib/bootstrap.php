<?php
namespace BoxUK\Describr;

// If you wish to use describr's audio/video plugins, add the path to the PHP Reader library. This can be done by
// either:
// 1. (preferred solution) Add them to the PHP include path in your php.ini
// 2. creating bootstrap.custom.php - see bootstrap.custom.php-sample for an example
//
// You must also have Zend framework on your path.
$describr_pathToPHPReaderLibrary = '';

if (file_exists(dirname(__FILE__) . '/bootstrap.custom.php')) {
    include_once('bootstrap.custom.php');
}

/**
 * Include this file to bootstrap the library. Registers an SPL autoloader to
 * automatically detect and load library class files at runtime.
 *
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      https://github.com/boxuk/describr
 * @since     1.0.0
 */

/**
 * @param string $rootDir e.g. /opt/BoxUK/describr/lib
 * @param string $pathToPHPReaderLibrary e.g. /opt/vendor/php-reader/1.8.1/src
 */
function autoload( $rootDir, $pathToPHPReaderLibrary )
{
    spl_autoload_register(function( $className ) use ( $rootDir, $pathToPHPReaderLibrary ) {
        $file = sprintf(
            '%s/%s.php',
            $rootDir,
            str_replace( '\\', '/', $className )
        );
        if ( file_exists($file) ) {
            require $file;
        } else  {
            $file = sprintf(
                '%s/%s.php',
                $pathToPHPReaderLibrary,
                str_replace( '_', '/', $className )
            );
            if ( file_exists($file) ) {
                require $file;
            }
        }
    });
}

autoload( __DIR__, $describr_pathToPHPReaderLibrary );
