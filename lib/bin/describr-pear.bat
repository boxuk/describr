@echo off
REM Script to run describr from the command line. This script is automatically
REM installed when you install Describr through PEAR. If you have not installed
REM through PEAR, ignore this script and use the script "describr" instead, which
REM will be in the /bin directory in the root of the checkout, rather than /lib/bin
@PHP_BIN@ @BIN_DIR@\describr-pear.php %*