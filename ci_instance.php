<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 *
 * Based on http://codeinphp.github.io/post/codeigniter-tip-accessing-codeigniter-instance-outside/
 * Thanks!
 */

date_default_timezone_set('America/Sao_Paulo');

$cwd = getcwd();
chdir(__DIR__);

define('ENVIRONMENT', getenv('ENVIRONMENT', 'production'));

$system_path        = __DIR__.'/system';
$application_folder = __DIR__.'/application';
$doc_root           = __DIR__.'';

switch (ENVIRONMENT)
{
	case 'development':
		// error_reporting(-1);
		ini_set('display_errors', 0);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
        // error_reporting(0);
       
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;
}

if (realpath($system_path) !== false) {
    $system_path = realpath($system_path) . '/';
}
$system_path = rtrim($system_path, '/') . '/';

define('BASEPATH', str_replace("\\", "/", $system_path));
define('FCPATH', $doc_root . '/');
define('APPPATH', $application_folder . '/');
define('VIEWPATH', $application_folder . '/views/');

require(BASEPATH . 'core/Common.php');

if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
    require(APPPATH . 'config/' . ENVIRONMENT . '/constants.php');
} else {
    require(APPPATH . 'config/constants.php');
}

$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);

if (extension_loaded('mbstring')) {
    define('MB_ENABLED', TRUE);
    // mbstring.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('mbstring.internal_encoding', $charset);
    // This is required for mb_convert_encoding() to strip invalid characters.
    // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
    mb_substitute_character('none');
} else {
    define('MB_ENABLED', FALSE);
}

// There's an ICONV_IMPL constant, but the PHP manual says that using
// iconv's predefined constants is "strongly discouraged".
if (extension_loaded('iconv')) {
    define('ICONV_ENABLED', TRUE);
    // iconv.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('iconv.internal_encoding', $charset);
} else {
    define('ICONV_ENABLED', FALSE);
}

// $GLOBALS['CFG'] = & load_class('Config', 'core');
// $GLOBALS['UNI'] = & load_class('Utf8', 'core');
// $GLOBALS['SEC'] = & load_class('Security', 'core');

$CFG  =& load_class('Config', 'core');
$UNI  =& load_class('Utf8', 'core');
$SEC  =& load_class('Security', 'core');
$IN   =& load_class('Input', 'core');
$LANG =& load_class('Lang', 'core');


load_class('Loader', 'core');
load_class('Router', 'core');
load_class('Input', 'core');
load_class('Lang', 'core');

require(BASEPATH . 'core/Controller.php');

function get_instance()
{
    return CI_Controller::get_instance();
}

chdir($cwd);

return new CI_Controller();
