@echo off
echo ================================================
echo DEBUG - Recherche et demarrage de ngrok
echo ================================================
echo.

REM Recherche de ngrok dans differents emplacements
set NGROK_PATH=
if exist "C:\laragon\bin\ngrok\ngrok.exe" (
    set NGROK_PATH=C:\laragon\bin\ngrok\ngrok.exe
    echo [OK] ngrok trouve dans: C:\laragon\bin\ngrok\
)
if exist "C:\Program Files\ngrok\ngrok.exe" (
    set NGROK_PATH=C:\Program Files\ngrok\ngrok.exe
    echo [OK] ngrok trouve dans: C:\Program Files\ngrok\
)
if exist "%USERPROFILE%\ngrok.exe" (
    set NGROK_PATH=%USERPROFILE%\ngrok.exe
    echo [OK] ngrok trouve dans: %USERPROFILE%
)

if "%NGROK_PATH%"=="" (
    echo [ERREUR] ngrok.exe introuvable!
    echo.
    echo Veuillez telecharger ngrok depuis: https://ngrok.com/download
    echo.
    pause
    exit /b 1
)

echo.
echo ================================================
echo Test de ngrok...
echo ================================================
"%NGROK_PATH%" version
echo.

echo ================================================
echo Verification de la configuration...
echo ================================================
set CONFIG_FOUND=0
if exist "%USERPROFILE%\.ngrok2\ngrok.yml" (
    echo [OK] Config ngrok v2 trouvee: %USERPROFILE%\.ngrok2\ngrok.yml
    set CONFIG_FOUND=1
)
if exist "%LOCALAPPDATA%\ngrok\ngrok.yml" (
    echo [OK] Config ngrok v3 trouvee: %LOCALAPPDATA%\ngrok\ngrok.yml
    set CONFIG_FOUND=1
)
if %CONFIG_FOUND%==0 (
    echo [ATTENTION] Aucun fichier de configuration trouve
    echo Executez setup_ngrok.bat pour configurer votre authtoken
)
echo.

echo ================================================
echo Configuration du site a partager
echo ================================================
echo 1. esmer_shop.test:80 (Recommande - Laragon)
echo 2. localhost:80
echo 3. Port 8000 (Symfony server)
echo 4. Autre configuration
echo.
set /p CHOICE="Votre choix (1/2/3/4): "

set TARGET=esmer_shop.test:80
if "%CHOICE%"=="2" set TARGET=localhost:80
if "%CHOICE%"=="3" set TARGET=localhost:8000
if "%CHOICE%"=="4" (
    set /p TARGET="Entrez la configuration (ex: localhost:8000): "
)

echo.
echo ================================================
echo Demarrage du tunnel vers %TARGET%...
echo ================================================
echo.
echo IMPORTANT:
echo  - Gardez cette fenetre ouverte
echo  - L'URL a partager sera affichee ci-dessous
echo  - Pour arreter, appuyez sur Ctrl+C
echo.
echo ================================================
"%NGROK_PATH%" http %TARGET% --host-header=rewrite

echo.
echo ================================================
echo Le tunnel s'est arrete.
echo ================================================
pause
