@REM Install Windows Subsystem for Linux Version 2 using Ubuntu, Docker,
@REM Visual Studio Code, and NodeJS on Windows.
@REM You may need to run this file in a terminal with admin permissions.
@REM
@REM Usage:
@REM   install-windows.bat

@ECHO OFF

SET NODE_VERSION=LTS

ECHO Installing Windows Subsystem for Linux Version 2 using Ubuntu.
call wsl --install -d Ubuntu
call wsl --set-version Ubuntu 2
call wsl --set-default-version 2
call wsl --set-default Ubuntu

ECHO Installing Docker
call winget install -e --id Docker.DockerDesktop

ECHO Installing Visual Studio Code
call winget install -e --id Microsoft.VisualStudioCode

ECHO Installing NodeJS LTS for Windows
call winget install -e --id OpenJS.NodeJS.LTS
