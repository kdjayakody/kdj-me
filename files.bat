@echo off
setlocal enabledelayedexpansion

REM Define the root project directory name


echo Creating main directories...
mkdir public
mkdir includes
mkdir vendor

echo Creating public subdirectories...
mkdir public\assets
mkdir public\assets\css
mkdir public\assets\js
mkdir public\assets\images
mkdir public\assets\fonts

echo Creating includes subdirectories...
mkdir includes\templates

echo Creating placeholder PHP files in public...
type nul > public\index.php
type nul > public\login.php
type nul > public\register.php
type nul > public\logout.php
type nul > public\dashboard.php
type nul > public\profile.php
type nul > public\change_password.php
type nul > public\forgot_password.php
type nul > public\reset_password.php
type nul > public\verify_email.php
type nul > public\setup_mfa.php
type nul > public\verify_mfa.php
type nul > public\manage_mfa.php

echo Creating placeholder asset files...
type nul > public\assets\css\style.css
type nul > public\assets\js\script.js

echo Creating placeholder web server/SEO files...
REM Create empty .htaccess, leave content definition to user
type nul > public\.htaccess
REM Create empty web.config, leave content definition to user
type nul > public\web.config
echo User-agent: * > public\robots.txt
echo Disallow: /includes/ >> public\robots.txt
echo Disallow: /vendor/ >> public\robots.txt
echo Disallow: /.env >> public\robots.txt
type nul > public\sitemap.xml

echo Creating placeholder includes files...
type nul > includes\config.php
type nul > includes\functions.php
type nul > includes\api_client.php
type nul > includes\session_manager.php
type nul > includes\security_helpers.php

echo Creating placeholder template files...
type nul > includes\templates\header.php
type nul > includes\templates\footer.php
type nul > includes\templates\login_form.php
type nul > includes\templates\register_form.php
type nul > includes\templates\profile_form.php
type nul > includes\templates\password_change_form.php
type nul > includes\templates\mfa_setup_display.php
type nul > includes\templates\message.php

echo Creating project root files...
type nul > composer.json
type nul > .env
echo vendor/ > .gitignore
echo .env >> .gitignore
type nul > README.md

echo.
echo Project structure created successfully in %PROJECT_DIR%
echo Remember to:
echo  - Edit composer.json and run 'composer install'
echo  - Configure your .env file
echo  - Configure public/.htaccess or public/web.config for your server
echo  - Set your web server's document root to the 'public' directory inside %PROJECT_DIR%

cd ..
endlocal
pause
