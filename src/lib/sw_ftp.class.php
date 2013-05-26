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
* FTP类
+------------------------------------------------------------------------------
* 
* @package sw_ftp
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_ftp
{
	// {{{ const

	const CONNECT_TIME = 10;

	const FILE_TYPE = 1;
	const DIR_TYPE  = 2;

	// }}}
	// {{{ members

	/**
	 * FTP 句柄 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__ftp;

	/**
	 * FTP 的地址 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__host = '';

	/**
	 * FTP端口 
	 * 
	 * @var int
	 * @access protected
	 */
	protected $__port = 21;

	/**
	 * 用户名 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__username = '';

	/**
	 * 密码 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__password = '';

	// }}}
    // {{{ functions
	// {{{ public function __construct()
	
	/**
	 * 构造函数 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function __construct($options = array())
	{
		if (isset($options['host']) && $options['host'] !== '') {
			$this->set_host($options['host']);	
		}

		if (isset($options['port']) && $options['port'] !== '') {
			$this->set_port($options['port']);	
		}

		if (isset($options['username']) && $options['username'] !== '') {
			$this->set_username($options['username']);	
		}

		if (isset($options['password']) && $options['password'] !== '') {
			$this->set_password($options['password']);	
		}

		if (!empty($options)) {
			$this->connect();	
		}
	}

	// }}}
	// {{{ public function connect()
	
	/**
	 * 链接FTP服务器 
	 * 
	 * @access public
	 * @return void
	 */
	public function connect()
	{
		$this->__ftp = ftp_connect($this->__host, $this->__port, self::CONNECT_TIME);
		if (!$this->__ftp) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('connect to' . $this->__host . ' fail.');	
		}

		$is_login = ftp_login($this->__ftp, $this->__username, $this->__password);
		if (!$is_login) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('Auth ' . $this->__host . ' fail.');	
		}
	}

	// }}}
	// {{{ public function set_host()
	
	/**
	 * 设置主机名 
	 * 
	 * @param mixed $host 
	 * @access public
	 * @return void
	 */
	public function set_host($host)
	{
		$this->__host = $host;
		return $this;
	}

	// }}}
	// {{{ public function set_port()
	
	/**
	 * 设置主机端口
	 * 
	 * @param mixed $port 
	 * @access public
	 * @return void
	 */
	public function set_port($port)
	{
		$this->__port = $port;
		return $this;
	}

	// }}}
	// {{{ public function set_username()
	
	/**
	 * 设置用户名
	 * 
	 * @param mixed $username 
	 * @access public
	 * @return void
	 */
	public function set_username($username)
	{
		$this->__username = $username;
		return $this;
	}

	// }}}
	// {{{ public function set_password()
	
	/**
	 * 设置密码
	 * 
	 * @param mixed $password 
	 * @access public
	 * @return void
	 */
	public function set_password($password)
	{
		$this->__password = $password;

		return $this;
	}

	// }}}
	// {{{ public function set_pasv()
	
	/**
	 * 设置被动模式
	 * 
	 * @param bool $pasv 
	 * @access public
	 * @return this
	 */
	public function set_pasv($pasv)
	{
		if (!ftp_pasv($this->__ftp, (bool) $pasv)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('set ftp pasv fail.');	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_list()
	
	/**
	 * 获取当前目录的文件列表
	 * 
	 * @param string $path 
	 * @access public
	 * @return this
	 */
	public function get_list($path, $recursive = false)
	{
		$this->set_pasv(true);

		$contents = ftp_rawlist ($this->__ftp, $path, true);
		$files = array();

		foreach ($contents as $key => $value) {
			preg_match("#([drwx\-]+)([\s]+)([0-9]+)([\s]+)([0-9]+)([\s]+)([a-zA-Z0-9\.]+)([\s]+)([0-9]+)([\s]+)([a-zA-Z]+)([\s ]+)([0-9]+)([\s]+)([0-9]+):([0-9]+)([\s]+)([a-zA-Z0-9\.\-\_ ]+)#si", $value, $matches);	

			if(count($matches) < 18 || ($matches[3] != 1 && ($matches[18] == "." || $matches[18] == ".."))){
				continue;
			}
			
			$files[$key]['rights']   = $matches[1];
			$files[$key]['type']     = ($matches[3] == 1) ? self::FILE_TYPE : self::DIR_TYPE;
			$files[$key]['owner_id'] = $matches[5];
			$files[$key]['owner']    = $matches[7];
			$files[$key]['name']     = $matches[18];
			$files[$key]['date_modified'] = $matches[11] . " " . $matches[13] 
											. " " . $matches[13] . ":" . $matches[16] . "";
		}

		return $files;
	}

	// }}}
	// {{{ public function put_file()
	
	/**
	 * 上传一个文件到服务器
	 * 
	 * @param string $local 
	 * @param string $remote 
	 * @param int $mode 
	 * @access public
	 * @return this
	 */
	public function put_file($local, $remote, $mode = FTP_BINARY)
	{
		$this->set_pasv(true);

		if (!file_exists($local)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$local file is not exists");	
		}

		if (!ftp_put($this->__ftp, $remote, $local, $mode)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$local file put to ftp server is fail");	
		}
	}

	// }}}
	// {{{ public function get_file()
	
	/**
	 * 查看一个文件到服务器
	 * 
	 * @param string $remote 
	 * @param int $mode 
	 * @access public
	 * @return fp
	 */
	public function get_file($local, $remote, $mode = FTP_ASCII)
	{
		$this->set_pasv(true);

		if (!ftp_get($this->__ftp, $local, $remote, $mode)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$remote file get to ftp server is fail");	
		}
	}

	// }}}
	// {{{ public function rm_dir()
	
	/**
	 * 删除一个目录
	 * 
	 * @param string $remote 
	 * @access public
	 * @return fp
	 */
	public function rm_dir($remote)
	{
		$this->set_pasv(true);

		if (!ftp_rmdir($this->__ftp, $remote)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$remote dir remove fail");	
		}
	}

	// }}}
	// {{{ public function mk_dir()
	
	/**
	 * 创建一个目录
	 * 
	 * @param string $remote 
	 * @access public
	 * @return fp
	 */
	public function mk_dir($remote)
	{
		$this->set_pasv(true);

		if (!ftp_mkdir($this->__ftp, $remote)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$remote  mkdir fail");	
		}
	}

	// }}}
	// {{{ public function delete_file()
	
	/**
	 * 删除一个文件
	 * 
	 * @param string $remote 
	 * @access public
	 * @return fp
	 */
	public function delete_file($remote)
	{
		$this->set_pasv(true);

		if (!ftp_delete($this->__ftp, $remote)) {
			require_once PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception("$remote  delete file fail");	
		}
	}

	// }}}
    // }}}
}
