<?php
/**
 * Package file for creating PEAR packages. This file defines how the PEAR
 * package should be constructed.
 *
 * Before a new tag is made, VERSION should be incremented to the new tag identifier.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
define( 'VERSION', '1.0.2' );

require_once( 'PEAR/PackageFileManager2.php' );

// parse the .gitignore to figure out what files to ignore

$aFilesToIgnore = array();
$file_handle = fopen('.gitignore', 'r');
while (!feof($file_handle) ) {
    
    $toIgnore = fgets($file_handle);
    $aFilesToIgnore[] = trim($toIgnore);
    
}
fclose($file_handle);


$aFilesToIgnore[] = 'tests/';
$aFilesToIgnore[] = 'nbproject/';
$aFilesToIgnore[] = 'logs/';
$aFilesToIgnore[] = 'reports/';
$aFilesToIgnore[] = 'README.markdown';
$aFilesToIgnore[] = 'build.xml';
$aFilesToIgnore[] = 'package.php';
$aFilesToIgnore[] = 'LICENCE-GPL';
$aFilesToIgnore[] = 'LICENCE-MIT';

$packagexml = new PEAR_PackageFileManager2();
$packagexml->setOptions(array(
    'dir_roles' => array(
        'bin' => 'script'
    ),
    'packagedirectory' => '.',
    'baseinstalldir' => '/',
    'ignore' => $aFilesToIgnore
));
        
$packagexml->setPackage( 'describr' );
$packagexml->setSummary( 'So, tell me about your file...' );
$packagexml->setDescription( 'Given any file, describr will describe it. Can be used as a command line script or within a PHP project' );
#$packagexml->setChannel( 'pear.boxuk.com' );
$packagexml->setChannel( 'pear.gavd-desktop' );
$packagexml->setAPIVersion( VERSION );
$packagexml->setReleaseVersion( VERSION );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );

$packagexml->addRelease(); // WINDOWS
$packagexml->setOSInstallCondition('windows');
$packagexml->addInstallAs('bin/describr-pear.php', 'describr.php');
$packagexml->addInstallAs('bin/describr.bat', 'describr.bat'); # TODO win vn
$packagexml->addIgnoreToRelease('bin/describr');

$packagexml->addRelease(); // NON-WINDOWS
$packagexml->addInstallAs('bin/describr-pear.php', 'describr.php');
$packagexml->addInstallAs('bin/describr-pear', 'describr');
$packagexml->addIgnoreToRelease('bin/describr.bat');

#print_r($GLOBALS['_PEAR_Config_instance']);die;

$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.3.0' );
$packagexml->addMaintainer( 'lead', 'Open', 'Source', 'opensource@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://opensource.org/licenses/mit-license.php' );

$packagexml->addReplacement('bin/describr-pear', 'pear-config',  '@PHP_BIN@', 'php_bin');
$packagexml->addReplacement('bin/describr-pear', 'pear-config',  '@BIN_DIR@', 'bin_dir');
$packagexml->addReplacement('bin/describr-pear.php', 'pear-config',  '@PHP_DIR@', 'php_dir');
$packagexml->addReplacement('lib/BoxUK/Describr/Plugins/BoxUK/ImageMainColourPicker.php', 'pear-config',  '@DATA_DIR@', 'data_dir');


$packagexml->generateContents();
$packagexml->writePackageFile();
