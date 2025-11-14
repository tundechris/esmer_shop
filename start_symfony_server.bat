@echo off
echo ================================================
echo Demarrage du serveur Symfony
echo ================================================
echo.
echo ATTENTION: Gardez cette fenetre ouverte!
echo.
echo Le serveur sera accessible sur: http://127.0.0.1:8000
echo.
echo ================================================

C:\laragon\bin\php\php-8.3.17-Win32-vs16-x64\php.exe -S 127.0.0.1:8000 -t public

echo.
echo ================================================
echo Le serveur s'est arrete.
echo ================================================
pause
