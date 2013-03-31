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
* 遍历目录的迭代器
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_iterator_fetch_dir extends RecursiveIteratorIterator
{
	// {{{ members

	/**
	 * 忽略的子目录 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ignore_dir = array();

	/**
	 * 忽略的子目录,附加自己也忽略 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__self_ignore_dir = array();

	/**
	 * 忽略的文件
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__ignore_files = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($path)
	{
		$dir_iterator = new RecursiveDirectoryIterator($path);
		parent::__construct($dir_iterator);
	}

	// }}}
	// {{{ public function callHasChildren()

	/**
	 * 重写父类中的方法,决定是否有子节点 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function callHasChildren()
	{
		$sub_path = $this->getSubPathname();
		if (isset($this->__ignore_dir[$sub_path])) {
			return false;
		}
		return parent::callHasChildren();
	}

	// }}}
	// {{{ public function current()

	/**
	 * current 
	 * 
	 * @access public
	 * @return void
	 */
	public function current()
	{
		$sub_path = $this->getSubPathname();
		while (parent::valid() 
			&& (isset($this->__ignore_files[$sub_path]) 
				|| isset($this->__self_ignore_dir[$sub_path . '/.']))) {
			parent::next();
			$sub_path = $this->getSubPathname();
		}
		return parent::current();
	}

	// }}}
	// {{{ public function key()

	/**
	 * key 
	 * 
	 * @access public
	 * @return void
	 */
	public function key()
	{
		return $this->getSubPathname();
	}

	// }}}
	// {{{ public function set_ignore_dir()

	/**
	 * 设置忽略的目录 
	 * 
	 * @access public
	 * @return sw_iterator_fetch_dir
	 */
	public function set_ignore_dir(array $ignore_dirs)
	{
		foreach ($ignore_dirs as $key => $value) {
			if ('/.' == substr($value, -2)) {
				$this->__self_ignore_dir[$value] = true;
			} else {
				$this->__ignore_dir[$value] = true;		
			}	
		} 
		return $this;
	}

	// }}}
	// {{{ public function set_ignore_file()

	/**
	 * 设置忽略的文件 
	 * 
	 * @access public
	 * @return sw_iterator_fetch_dir
	 */
	public function set_ignore_file(array $ignore_file)
	{
		foreach ($ignore_file as $value) {
			$this->__ignore_files[$value] = true;
		}
		return $this;
	}

	// }}}
	// }}}
}
