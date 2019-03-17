@echo off
rem START or STOP Services
rem ----------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop


"C:\LAMPP\mysql\bin\mysqld" --defaults-file="C:\LAMPP\mysql\bin\my.ini" --standalone --console
if errorlevel 1 goto error
goto finish

:stop
cmd.exe /C start "" /MIN call "C:\LAMPP\killprocess.bat" "mysqld.exe"

if not exist "C:\LAMPP\mysql\data\%computername%.pid" goto finish
echo Delete %computername%.pid ...
del "C:\LAMPP\mysql\data\%computername%.pid"
goto finish


:error
echo MySQL could not be started

:finish
exit
