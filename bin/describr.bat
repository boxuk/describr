@ECHO OFF

rem Script to run describr from the command line
rem You must have set up ../lib/bootstrap.custom.php before running this

set BASE_DIR=%~dp0
set FILE_NAME=%~f1

php %BASE_DIR%describr.php %FILE_NAME%