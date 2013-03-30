<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
/**
+------------------------------------------------------------------------------
* 自动生成Makefile文件LIB库 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_create_makefile
{
	// {{{ functions
	// {{{ public static function factory()
	
	/**
	 * factory 
	 * 
	 * @param mixed $type 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function factory($type = 'common')
	{
		$class_name = 'sw_create_makefile_' .  $type;

		if (!class_exists($class_name)) {
			require_once PATH_DSWAN_LIB . 'create_makefile/' . $class_name . '.class.php';	
		}

		if (!class_exists($class_name)) {
			throw new Exception("can not load $class_name");	
		}

		return new $class_name();
	}
	 
	// }}}
	// }}}
}
