@echo OFF
:: in case DelayedExpansion is on and a path contains !
setlocal DISABLEDELAYEDEXPANSION
php "%~dp0wpl2zip.phar" %*
