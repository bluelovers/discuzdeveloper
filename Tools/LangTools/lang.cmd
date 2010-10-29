REM 语言包自动提取打包脚本
REM ConvertZ 已提供，WinRAR、php.exe 的路径请自行更改
@echo off
e:\web\php-5.2.6-Win32\php.exe disad_lang.php
echo --- BIG5 ---
e:\convertz\ConvertZ /i:GBK /o:BIG5 ..\template_pub\templates.lang.php ..\other\templates.lang.php
e:\convertz\ConvertZ /i:GBK /o:BIG5 ..\template_pub\message.lang.php ..\other\message.lang.php
e:\web\php-5.2.6-Win32\php.exe -l ..\other\templates.lang.php
if not errorlevel 0 pause
e:\web\php-5.2.6-Win32\php.exe -l ..\other\message.lang.php
if not errorlevel 0 pause
echo --- RAR ---
"e:\Program Files (x86)\WinRAR\RAR.exe" a -ep ..\other\tc_big5.lang.rar ..\other\*.php
echo --- UTF8 BIG5 ---
e:\convertz\ConvertZ /i:BIG5 /o:UTF8 ..\other\templates.lang.php ..\other\templates.lang.php
e:\convertz\ConvertZ /i:BIG5 /o:UTF8 ..\other\message.lang.php ..\other\message.lang.php
e:\web\php-5.2.6-Win32\php.exe -l ..\other\templates.lang.php
if not errorlevel 0 pause
e:\web\php-5.2.6-Win32\php.exe -l ..\other\message.lang.php
if not errorlevel 0 pause
echo --- RAR ---
"e:\Program Files (x86)\WinRAR\RAR.exe" a -ep ..\other\tc_utf8.lang.rar ..\other\*.php
echo --- UTF8 GBK ---
e:\convertz\ConvertZ /i:GBK /o:UTF8 ..\template_pub\templates.lang.php ..\other\templates.lang.php
e:\convertz\ConvertZ /i:GBK /o:UTF8 ..\template_pub\message.lang.php ..\other\message.lang.php
e:\web\php-5.2.6-Win32\php.exe -l ..\other\templates.lang.php
if not errorlevel 0 pause
e:\web\php-5.2.6-Win32\php.exe -l ..\other\message.lang.php
if not errorlevel 0 pause
echo --- RAR ---
"e:\Program Files (x86)\WinRAR\RAR.exe" a -ep ..\other\sc_utf8.lang.rar ..\other\*.php
echo --- END ---
pause