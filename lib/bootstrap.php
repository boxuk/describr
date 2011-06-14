<?php
namespace BoxUK\Describr;

// You must set a path to the PHP Reader library in bootstrap.custom.php
// You must also have Zend framework on your path
$describr_pathToPHPReaderLibrary = '';

if (file_exists(dirname(__FILE__) . '/bootstrap.custom.php')) {
    include_once('bootstrap.custom.php');
}

// make sure we can load PHPReader
if(strlen ($describr_pathToPHPReaderLibrary) < 1) {
    throw new \Exception('You must define $describr_pathToPHPReaderLibrary in {describr}/lib/bootstrap.custom.php-sample so Describr knows where to find PHP-Reader');
}
if(!file_exists($describr_pathToPHPReaderLibrary)) {
    throw new \Exception('$describr_pathToPHPReaderLibrary points to a non existent location ('
            . $describr_pathToPHPReaderLibrary
            . '). It should point to the \'library\' folder within the PHP Reader structure - this is the folder that contains the folder \'Zend\'');
}

// make sure we have the correct delimiter for the OS
$delimiter = (strpos(PHP_OS, 'WIN') !== false) ? ';' : ':';

set_include_path(get_include_path () . $delimiter . $describr_pathToPHPReaderLibrary);

/**
 * Include this file to bootstrap the library. Registers an SPL autoloader to
 * automatically detect and load library class files at runtime.
 *
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      https://github.com/boxuk/describr
 * @since     1.0
 */
function autoload( $rootDir, $pathToPHPReaderLibrary ) {
    spl_autoload_register(function( $className ) use ( $rootDir, $pathToPHPReaderLibrary ) {
        $file = sprintf(
            '%s/%s.php',
            $rootDir,
            str_replace( '\\', '/', $className )
        );
        if ( file_exists($file) ) {
            require $file;
        }
        else  {
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