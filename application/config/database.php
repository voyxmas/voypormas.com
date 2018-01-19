<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$query_builder = TRUE;

// definir las rutas de coneccion segun el dominio
switch ($_SERVER['SERVER_NAME']) 
{
	case LOCAL_DOMAIN:
		$active_group = 'dev';
		break;
	case LIVE_DOMAIN:
		$active_group = 'live';
		break;
	case DEMO_DOMAIN:
		$active_group = 'devonline';
		break;
}

// lista de conecciones a las bases de datos

$db['live'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'voypormas_db',
	'password' => 'evvdXcbsuGcdI4op',
	'database' => 'voypormas_db',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'development'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['dev'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'zibeigfrgna',
	'database' => 'voypormas_db',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'development'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => TRUE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['devonline'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'zignacom_zigna',
	'password' => 'zibeigfrgna',
	'database' => 'zignacom_voypormas_db',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'development'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
