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

require_once D_PATH_SWAN_LIB . 'create_makefile/sw_create_makefile_base.class.php';
 
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
class sw_create_makefile_wiki extends sw_create_makefile_base
{
	// {{{ functions
	// {{{ protected function _get_rule_src()

	/**
	 * 返回 make 的规则
	 * 
	 * @param array $file_names
	 * @param int $id  make类型id 对应配置文件中的 # 
	 * @access protected
	 * @return string
	 */
	protected function _get_rule_src($file_names, $id)
	{
		$rule = '';
		foreach ($file_names as $file_name) {
			$real_file_name = substr($file_name, 0, strlen($file_name) - 1);
			$rule .= $file_name . ': ' . $real_file_name . "\n";
			$rule .= "\t" . '/usr/bin/install -m 644 -o swan -g swan $< $(TARGET' . $id . ')/' . urlencode($real_file_name) . "\n";
		}

		return $rule;
	}

	// }}}
	// }}}
}
