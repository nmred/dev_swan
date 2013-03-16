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
abstract class sw_create_makefile_base
{
	// {{{ consts

	/**
	 * 配置文件中段名前缀 
	 */
	const PREFIX_INI = 'target';
	/**
	 * 默认指定文件的属主  
	 */
	const DEFAULT_USER = 'swan';

	/**
	 * 默认指定文件的属组
	 */
	const DEFAULT_GROUP = 'swan';

	/**
	 * 默认指定文件的权限  
	 */
	const DEFAULT_PRAM = '755';

	/**
	 * 默认目标目录的属主  
	 */
	const TARGET_DIR_DEFAULT_USER = 'swan';

	/**
	 * 默认目标目录的属组
	 */
	const TARGET_DIR_DEFAULT_GROUP = 'swan';

	/**
	 * 默认目标目录的权限  
	 */
	const TARGET_DIR_DEFAULT_PRAM = '755';

	// }}}
	// {{{ members
	
	/**
	 * 生成工具的配置文件 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__config_file = './Makefile.ini';

	/**
	 * 生成Makefile文件名称 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__makefile_name = "Makefile";

	/**
	 * 安装的根目录 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__target_root_dir = "/usr/local/swan/";

	/**
	 * 生成工具运行的当前目录 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__pwd = '.';

	/**
	 * 设置参数
	 * 安装文件的目标目录 
	 * 如果第一个字符不是 / 说明是相对路劲
	 * 反之按相对路劲处理
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	/**
	 * 忽略文件 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ignore_files = array();

	/**
	 * 忽略目录
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ignore_dirs = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->__pwd = getcwd();
	}

	// }}}
	// {{{ public funciton run()

	/**
	 * 开始生成makefile文件 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$this->_parse_ini();
		$this->_create_make_file();
	}

	// }}}
	// {{{ public funciton set_root_dir()

	/**
	 * 设置根目录
	 * 
	 * @param string $dir_name 
	 * @access public
	 * @return void
	 */
	public function set_root_dir($dir_name)
	{
		$dir_name = rtrim($dir_name, '/') . '/';
		$this->__target_root_dir = $dir_name;
		return $this;
	}

	// }}}
	// {{{ protected function _parse_ini()
	
	/**
	 * 解析配置文件 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _parse_ini()
	{
		if (!file_exists($this->__config_file)) {
			throw new Exception("create makefile need .ini file not exists.");	
		}

		$params = parse_ini_file($this->__config_file, true);

		if (empty($params)) {
			throw new Exception("parse ini file failed.");	
		}
		
		if (!isset($params['global'])) {
			throw new Exception("parse ini file failed.");	
		}

		if (isset($params['global']['ignore_file'])) {
			$this->__ignore_files = explode(',', $params['global']['ignore_file']); 	
			foreach ($this->__ignore_files as $key => $value) {
				$this->__ignore_files[$key] = trim($value);
			}
		}

		if (isset($params['global']['ignore_dir'])) {
			$this->__ignore_dirs = explode(',', $params['global']['ignore_dir']); 	
			foreach ($this->__ignore_dirs as $key => $value) {
				$this->__ignore_dirs[$key] = trim($value);
			}
		}

		foreach ($params as $key => $value) {
			if ('global' !== $key) {
				$this->_parse_target_ini($key, $value);			
			}
		}
	}
	 
	// }}}
	// {{{ protected function _parse_target_ini()
	
	/**
	 * 解析目标配置文件 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _parse_target_ini($key, $value)
	{
		if (false === strpos($key, '#')) {
			throw new Exception("ini config file error.");	
		}
	
		list($prefix, $number) = explode('#', $key);
		if (!is_numeric($number)) {
			throw new Exception("ini config file error.");	
		}

		if (!isset($value['target'])) {
			throw new Exception("must given make target on `$key`.");	
		}	
		
		// 设置make目标目录
		if ('/' === substr($value['target'], 0, 1)) {
			$this->__params[$key]['target'] = $value['target'];	
		} else {
			$this->__params[$key]['target'] = rtrim($this->__target_root_dir, '/') . '/' . ltrim($value['target'], './');
		}

		$this->__params[$key]['target_param'] = isset($value['target_param']) ? $value['target_param'] : self::DEFAULT_PRAM; 	
		$this->__params[$key]['target_user'] = isset($value['target_user']) ? $value['target_user'] : self::DEFAULT_USER; 	
		$this->__params[$key]['target_group'] = isset($value['target_group']) ? $value['target_group'] : self::DEFAULT_GROUP; 	
		$this->__params[$key]['target_dir_param'] = isset($value['target_dir_param']) ? $value['target_dir_param'] : self::TARGET_DIR_DEFAULT_PRAM; 	
		$this->__params[$key]['target_dir_user'] = isset($value['target_dir_user']) ? $value['target_dir_user'] : self::TARGET_DIR_DEFAULT_USER; 	
		$this->__params[$key]['target_dir_group'] = isset($value['target_dir_group']) ? $value['target_dir_group'] : self::TARGET_DIR_DEFAULT_GROUP; 	
		if (!isset($value['src_file']) && $number != 0) {
			throw new Exception("not #0 is must given make target srouce files `$key`.");
		} elseif (isset($value['src_file'])) {
			$this->__params[$key]['src_file'] = explode(',', $value['src_file']);
			foreach ($this->__params[$key]['src_file'] as $vkey => $vvalue) {
				$this->__params[$key]['src_file'][$vkey] = trim($vvalue);
			}
		}
	}
	 
	// }}}
	// {{{ protected function _create_make_file()

	/**
	 * 创建Makefile文件 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _create_make_file()
	{
		$template = $this->_get_makefile_template();	

		//遍历当前目录
		$files = $this->_get_current_files();
		$sub_dirs = $files['dir'];
		
		//生成TARGET, INSTALL变量 和make_dir目标
		$target_src  = $this->_get_target_src();
		$install_src = $this->_get_install_src();
		$make_dir    = $this->_get_make_dir_src();

		//处理文件
		$file_list = $this->_get_make_files();

		//生成INC_SRC、RULE、ALL_TARGET
		$inc_src_str = '';
		$rule = '';
		$all_target = '';
		$install_make_dir = '';
		foreach ($file_list as $key => $value) {
			$inc_src_str .= 'INC_SRC' . $key . ' = ' . implode(' ', $value) . "\n";
			$all_target .= '$(INC_SRC' . $key . ') ';
			$install_make_dir .= 'make_dir' . $key . ' ';
			$rule .= $this->_get_rule_src($value, $key);
		}
		$install_make_dir .= $all_target;

		$rep_replace = array(
			$target_src,
			$install_src,
			implode(' ', $sub_dirs),
			$inc_src_str,
			$rule,
			$make_dir,
			$install_make_dir,
		);

		$rep_search = array(
			'__TARGET__',
			'__INSTALL__',
			'__SUBDIRS__',
			'__INS_SRC__',
			'__RULE__',
			'__MAKE_DIR__',
			'__ALL_TARGET__',
		);

		$out_put = str_replace($rep_search, $rep_replace, $template);
		if (false != file_put_contents($this->__makefile_name, $out_put)) {
			echo "\nCreate Makefile success in dir $this->__pwd ......\n";	
		}
	}

	// }}}
	// {{{ protected function _get_current_files()

	/**
	 * 返回当前目录下所有的文件个目录 
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_current_files()
	{
		//遍历当前目录
		$src_files = array();
		$sub_dirs = array();
		$dir_iterator = new RecursiveDirectoryIterator($this->__pwd);
		foreach ($dir_iterator as $file_path => $value) {
			$file_name = $value->getFileName();
			if (('.' == $file_name) || ('..' == $file_name)) {
				continue;	
			}

			if ($value->isFile() && !in_array($file_name, $this->__ignore_files)) {
				$src_files[] = $value->getFileName();	
			}

			if ($value->isDir() && !in_array($file_name, $this->__ignore_dirs)) {
				$sub_dirs[] = $value->getFileName();	
			}
		}	
		
		return array('file' => $src_files, 'dir' => $sub_dirs);
	}

	// }}}
	// {{{ protected function _get_makefile_template()

	/**
	 * 获取makefile模板 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_makefile_template()
	{
		return <<<MAKEFILE
__TARGET__
SUBDIRS = __SUBDIRS__
__INS_SRC__ 
__INSTALL__

all:
__RULE__

INS_DIRS = \
\tif test "$(SUBDIRS)"; then \
\t\techo "Install Dirs:$(SUBDIRS)"; \
\t\tfor i in `echo $(SUBDIRS)`; do \
\t\t\tmake install -C $\$i; \
\t\tdone; \
\tfi; 

__MAKE_DIR__

install: __ALL_TARGET__	
\t@$(INS_DIRS)
MAKEFILE;
	}
	
	// }}} 
	// {{{ protected function _get_make_files()

	/**
	 * 返回需要 make 的文件
	 * 
	 * @access protected
	 * @return array
	 */
	protected function _get_make_files()
	{
		//遍历当前目录
		$files = $this->_get_current_files();

		$inc_src = array();
		foreach ($files['file'] as $file_name) {
			$flag = false;
			foreach ($this->__params as $key => $value)	{
				list($prefix, $id) = explode('#', $key);
				if ((0 != $id) && isset($value['src_file']) && in_array($file_name, $value['src_file'])) {
					$flag = true;
					$inc_src[$id][] = $file_name . '.';
				}
			}

			if (false === $flag) {
				$inc_src[0][] =	$file_name . '.';
			}
		}	

		return $inc_src;
	}

	// }}}
	// {{{ protected function _get_target_src()

	/**
	 * 返回 make 的目标
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_target_src()
	{
		$target_src  = '';
		foreach ($this->__params as $key => $value) {
			list($prefix, $id) = explode('#', $key);
			$target_src  .= 'TARGET' . $id . ' = ' . $value['target'] . "\n"; 
		}

		return $target_src;
	}

	// }}}
	// {{{ protected function _get_install_src()

	/**
	 * 返回 make 的 INSTALL变量，在RULE中可能调用
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_install_src()
	{
		$install_src = '';
		foreach ($this->__params as $key => $value) {
			list($prefix, $id) = explode('#', $key);
			$install_src .= 'INSTALL' . $id . ' = ' . '/usr/bin/install -m ' . $value['target_param'] . ' -o ' . $value['target_user'] . ' -g ' . $value['target_group'] . ' $< $(' . 'TARGET' . $id . ")\n";
		}

		return $install_src;
	}

	// }}}
	// {{{ protected function _get_make_dir_src()

	/**
	 * 返回 make 的 Make dir 的规则
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _get_make_dir_src()
	{
		$make_dir    = '';
		foreach ($this->__params as $key => $value) {
			list($prefix, $id) = explode('#', $key);
			$make_dir .= 'make_dir' . $id . ":\n";
			$make_dir .= "\t" . '-@if test ! -d $(TARGET' . $id . ')' . '; then \\';
			$make_dir .= "\n\t" . 'echo "Make Dir:  $(TARGET' . $id . ')' . '"; \\';
			$make_dir .= "\n\t" . 'mkdir -m ' . $value['target_dir_param'] . ' $(TARGET' . $id . ')' . '; \\';
			$make_dir .= "\n\t" . 'chown ' . $value['target_dir_user'] . ':' . $value['target_dir_group'] . ' $(TARGET' . $id . ')' . '; \\';
			$make_dir .= "\n\tfi;\n";
		}

		return $make_dir;
	}

	// }}}
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
			$rule .= $file_name . ': ' . substr($file_name, 0, strlen($file_name) - 1) . "\n";
			$rule .= "\t" . '$(INSTALL' . $id . ')' . "\n";
		}

		return $rule;
	}

	// }}}
	// }}}
}
