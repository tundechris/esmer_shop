@echo off
echo ================================================
echo DIAGNOSTIC NGROK
echo ================================================
echo.

echo [1] Verification de ngrok.exe...
if exist "C:\laragon\bin\ngrok\ngrok.exe" (
    echo    [OK] Trouve dans: C:\laragon\bin\ngrok\ngrok.exe
) else (
    echo    [ERREUR] Introuvable dans C:\laragon\bin\ngrok\
)
echo.

echo [2] Version de ngrok...
C:\laragon\bin\ngrok\ngrok.exe version 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo    [ERREUR] Impossible d'executer ngrok
) else (
    echo    [OK] ngrok fonctionne
)
echo.

echo [3] Configuration authtoken...
set CONFIG_FOUND=0
if exist "%USERPROFILE%\.ngrok2\ngrok.yml" set CONFIG_FOUND=1
if exist "%LOCALAPPDATA%\ngrok\ngrok.yml" set CONFIG_FOUND=1
if %CONFIG_FOUND%==1 (
    echo    [OK] Fichier de configuration trouve
) else (
    echo    [ATTENTION] Configuration manquante
    echo    [ACTION] Executez setup_ngrok.bat pour configurer votre authtoken
)
echo.

echo [4] Test de connexion Laragon...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'http://localhost' -UseBasicParsing -TimeoutSec 2; Write-Host '   [OK] Laragon repond sur http://localhost' } catch { Write-Host '   [ERREUR] Laragon ne repond pas sur http://localhost' }"
echo.

echo ================================================
echo RESUME
echo ================================================
echo.
echo Si tout est OK, lancez: start_ngrok.bat
echo.
echo Si vous voyez des erreurs:
echo  - [ERREUR] ngrok introuvable : Reinstallez Laragon ou telecharger ngrok
echo  - [ATTENTION] Configuration manquante : Lancez setup_ngrok.bat
echo  - [ERREUR] Laragon ne repond pas : Demarrez Laragon d'abord
echo.
pause
