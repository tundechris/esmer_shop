@echo off
echo ================================================
echo Mise a jour de ngrok vers la derniere version
echo ================================================
echo.

REM Sauvegarde de l'ancienne version
if exist "C:\laragon\bin\ngrok\ngrok.exe" (
    echo [1] Sauvegarde de l'ancienne version...
    if not exist "C:\laragon\bin\ngrok\backup" mkdir "C:\laragon\bin\ngrok\backup"
    copy "C:\laragon\bin\ngrok\ngrok.exe" "C:\laragon\bin\ngrok\backup\ngrok_old.exe" >nul
    echo    [OK] Ancienne version sauvegardee
    echo.
)

echo [2] Telechargement de ngrok v3 pour Windows (64-bit)...
echo    Cela peut prendre quelques secondes...
powershell -Command "& {Invoke-WebRequest -Uri 'https://bin.equinox.io/c/bNyj1mQVY4c/ngrok-v3-stable-windows-amd64.zip' -OutFile '%TEMP%\ngrok.zip'}"

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERREUR] Echec du telechargement!
    echo.
    echo Telechargez manuellement depuis: https://ngrok.com/download
    echo Puis placez ngrok.exe dans: C:\laragon\bin\ngrok\
    echo.
    pause
    exit /b 1
)

echo    [OK] Telechargement termine
echo.

echo [3] Extraction de l'archive...
powershell -Command "Expand-Archive -Path '%TEMP%\ngrok.zip' -DestinationPath '%TEMP%\ngrok_extract' -Force"

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERREUR] Echec de l'extraction!
    echo.
    pause
    exit /b 1
)

echo    [OK] Extraction reussie
echo.

echo [4] Installation de la nouvelle version...
if not exist "C:\laragon\bin\ngrok" mkdir "C:\laragon\bin\ngrok"
copy "%TEMP%\ngrok_extract\ngrok.exe" "C:\laragon\bin\ngrok\ngrok.exe" >nul

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERREUR] Echec de l'installation!
    echo.
    pause
    exit /b 1
)

echo    [OK] Installation reussie
echo.

echo [5] Nettoyage des fichiers temporaires...
del "%TEMP%\ngrok.zip" >nul 2>&1
rmdir /s /q "%TEMP%\ngrok_extract" >nul 2>&1
echo    [OK] Nettoyage termine
echo.

echo [6] Verification de la nouvelle version...
C:\laragon\bin\ngrok\ngrok.exe version
echo.

echo ================================================
echo [SUCCESS] ngrok a ete mis a jour avec succes!
echo ================================================
echo.
echo Prochaines etapes:
echo  1. Lancez: setup_ngrok.bat (pour configurer l'authtoken)
echo  2. Puis: start_ngrok.bat (pour demarrer le tunnel)
echo.
pause
