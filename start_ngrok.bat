@echo off
echo ================================================
echo Demarrage du tunnel ngrok pour esmer_shop
echo ================================================
echo.
echo IMPORTANT: Avant de lancer ce script
echo  1. Lancez start_symfony_server.bat dans une autre fenetre
echo  2. OU assurez-vous que Laragon est demarre
echo.
pause
echo.
echo Verification de ngrok...
if not exist "C:\laragon\bin\ngrok\ngrok.exe" (
    echo ERREUR: ngrok.exe non trouve dans C:\laragon\bin\ngrok\
    echo.
    pause
    exit /b 1
)
echo ngrok trouve!
echo.
echo Demarrage du tunnel vers le serveur local...
echo.
echo IMPORTANT: Gardez cette fenetre ouverte!
echo.
echo Si vous voyez une erreur "ERR_NGROK_108", executez d'abord setup_ngrok.bat
echo.
echo ================================================
C:\laragon\bin\ngrok\ngrok.exe http 8000
echo.
echo ================================================
echo Le tunnel s'est arrete.
echo ================================================
pause
