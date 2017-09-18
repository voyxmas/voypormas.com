<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// URLS
	// defino los dominios del entorno
define('LOCAL_DOMAIN','voypormas.dev');
define('DEMO_DOMAIN','demo.zigna.com.ar');
define('DEMO_DIRECOTRY','voypormas');
define('LIVE_DOMAIN','voypormas.com');
	// defino el directorio en el que sta la instalacion de CI
define('LOCAL_DOMAIN_PATH',LOCAL_DOMAIN);
define('DEMO_DOMAIN_PATH',DEMO_DOMAIN.'/'.DEMO_DIRECOTRY);
define('LIVE_DOMAIN_PATH',LIVE_DOMAIN);

// constantes propias
define('APP_SECRET_TOKEN','9bd6b5ef63e6d92d7e22099a9333bce');
define('APP_RESULTS_PER_PAGE',20);
define('APP_SESSION_EXPIRE',60*30); // duracion de la session en segundos

// constantes modelos defaults
define('MODEL_DEFAULT_ORWHERE', FALSE); // FALSE = AND - TRUE = OR
define('MODEL_DEFAULT_RESULTS_PER_PAGE',20);
define('MODEL_DEFAULT_OUTPUT', 'result_array');
	// entradas estandarizadas de inserts en la base de datos
define('DB_INPUT_OBRA_SOCIAL_PARTICULAR', 54);

// folders
define('APP_ASSETS_FOLDER','assets');
define('APP_AJAX_FOLDER','ajax');
define('APP_MODALS_FOLDER','modals');

// define sections names
define('APP_HOME','app');
define('APP_MAIN',APP_HOME);
define('ADMIN_MAIN',APP_HOME);
	// print on screen
define('APP_DATETIMEFULL_FORMAT','d/m/Y H:i:s');
define('APP_DATETIME_FORMAT','d/m/Y H:i');
define('APP_DATE_FORMAT','d/m/Y');
define('APP_TIME_FORMAT','H:i');
define('APP_TIME_FORMATFULL','H:i:s');
	// system
define('SYS_DATETIMEFULL_FORMAT','Y-m-d H:i:s');
define('SYS_DATETIME_FORMAT','Y-m-d H:i');
define('SYS_DATE_FORMAT','Y-m-d');
define('SYS_TIME_FORMAT','H:i');
define('SYS_TIME_FORMATFULL','H:i:s');


/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
