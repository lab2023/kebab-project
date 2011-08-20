@echo off

REM Current project directory
SET CUR_DIR=%~dp0
"C:\path\to\php.exe" "%CUR_DIR%\doctrine.php" %*
