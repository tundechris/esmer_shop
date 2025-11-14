@echo off
echo ================================================
echo Configuration de ngrok pour esmer_shop
echo ================================================
echo.

REM Verification que ngrok existe
if not exist "C:\laragon\bin\ngrok\ngrok.exe" (
    echo [ERREUR] ngrok.exe introuvable!
    echo.
    echo Telechargez ngrok depuis: https://ngrok.com/download
    echo Puis placez ngrok.exe dans: C:\laragon\bin\ngrok\
    echo.
    pause
    exit /b 1
)

echo [1] Ouvrez votre navigateur et allez sur:
echo     https://dashboard.ngrok.com/get-started/your-authtoken
echo.
echo [2] Connectez-vous ou creez un compte (gratuit)
echo.
echo [3] Copiez votre authtoken (ressemble a: 2abc...xyz)
echo.
echo ================================================
set /p TOKEN="Collez votre authtoken ici: "

if "%TOKEN%"=="" (
    echo.
    echo [ERREUR] Authtoken vide!
    echo.
    pause
    exit /b 1
)

echo.
echo [4] Configuration de l'authtoken...
C:\laragon\bin\ngrok\ngrok.exe authtoken %TOKEN%

echo.
REM Verification que le fichier de config existe (plus fiable que ERRORLEVEL)
set CONFIG_OK=0
if exist "%USERPROFILE%\.ngrok2\ngrok.yml" set CONFIG_OK=1
if exist "%LOCALAPPDATA%\ngrok\ngrok.yml" set CONFIG_OK=1

if %CONFIG_OK%==1 (
    echo ================================================
    echo [OK] Authtoken configure avec succes!
    echo ================================================
    echo.
    echo Prochaines etapes:
    echo  1. Demarrez Laragon (si pas deja fait)
    echo  2. Lancez: start_ngrok.bat
    echo  3. Partagez l'URL qui s'affichera
    echo.
) else (
    echo ================================================
    echo [ERREUR] Echec de la configuration!
    echo ================================================
    echo.
    echo Verifiez que votre authtoken est correct
    echo.
)

pause
