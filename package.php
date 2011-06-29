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
define( 'VERSION', '1.0.3' );

require_once( 'PEAR/PackageFileManager2.php' );

$aFilesToIgnore = array();
$aFilesToIgnore[] = 'bootstrap.php';
$aFilesToIgnore[] = 'bootstrap.custom.php';
$aFilesToIgnore[] = 'bootstrap.custom.php-sample';

$packagexml = new PEAR_PackageFileManager2();
$packagexml->setOptions(array(
    'dir_roles' => array(
        'bin' => 'script'
    ),
    'packagedirectory' => 'lib',
    'baseinstalldir' => '/',
    'ignore' => $aFilesToIgnore
));
        
$packagexml->setPackage( 'describr' );
$packagexml->setSummary( 'So, tell me about your file...' );
$packagexml->setDescription( 'Given any file, describr will describe it. Can be used as a command line script or within a PHP project' );
#$packagexml->setChannel( 'pear.boxuk.net' );
$packagexml->setChannel( 'pear.gavd-desktop' );
$packagexml->setAPIVersion( VERSION );
$packagexml->setReleaseVersion( VERSION );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );

$packagexml->addRelease(); // WINDOWS
$packagexml->setOSInstallCondition('windows');
//$packagexml->addInstallAs('bin/describr-pear.bat', 'describr.bat'); # TODO Windows support

$packagexml->addRelease(); // NON-WINDOWS
$packagexml->addInstallAs('bin/describr-pear', 'describr');
$packagexml->addInstallAs('bin/describr-pear.php', 'describr-pear.php');

$packagexml->addReplacement('bin/describr-pear', 'pear-config',  '@PHP_BIN@', 'php_bin');
$packagexml->addReplacement('bin/describr-pear', 'pear-config',  '@BIN_DIR@', 'bin_dir');
$packagexml->addReplacement('bin/describr-pear.php', 'pear-config',  '@PHP_DIR@', 'php_dir');
$packagexml->addReplacement('BoxUK/Describr/Plugins/BoxUK/ImageMainColourPicker.php', 'pear-config',  '@DATA_DIR@', 'data_dir');

$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.3.0' );
$packagexml->addMaintainer( 'lead', 'Open', 'Source', 'opensource@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://opensource.org/licenses/mit-license.php' );


// require_once( 'PEAR/Dependency2.php' ); TODO add dependencies...

$packagexml->generateContents();
$packagexml->writePackageFile();