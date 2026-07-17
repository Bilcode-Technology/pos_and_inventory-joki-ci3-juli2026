<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| Loading database, session, and form_validation globally for convenience.
*/
$autoload['libraries'] = array('database', 'session', 'form_validation');

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Loading url (for site_url, base_url), form (for form_open with CSRF),
| security (for xss_clean), and number (for currency helpers).
*/
$autoload['helper'] = array('url', 'form', 'security', 'number');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
*/
$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Models will be loaded per-controller or autoloader once Step 3 is reached.
*/
$autoload['model'] = array();
