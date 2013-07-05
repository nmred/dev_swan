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
 
require_once 'dev_core.php';
require_once PATH_DSWAN_LIB . 'sw_xml.class.php';
/**
+------------------------------------------------------------------------------
* 创建数据库建表语句
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_create_database
{
	// {{{ const

	/**
	 * SQL语句的注释字符串  
	 */
	const SQL_COMMENT = '-- ';

	/**
	 * SQL语句的分隔符 
	 */
	 const SQL_SIGN = '`';

	/**
	 * 生成的SQL文件前缀 
	 */
	 const PREFIX = 'db_desc_';

	/**
	 * VIM折叠规则左边的标记 
	 */
	const FOLDING_SIGN_LEFT = '{{{ ';

	/**
	 * VIM折叠规则左边的标记 
	 */
	const FOLDING_SIGN_RIGHT = '}}} ';

	/**
	 * 空格 
	 */
	const SPACE_KEY = ' ';

	/**
	 * VIM描述 
	 */
	const VIM_HEADER = 'vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker:';

	// }}}	
	// {{{ members

	/**
	 * 数据库XML文件 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__xml_filename = '';

	/**
	 * 输出的目录 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__out_put_dir = '';

	// }}}
	// {{{ functions
	// {{{ public function run() 

	/**
	 * run 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		if (!file_exists($this->__xml_filename)
			|| !file_exists($this->__out_put_dir)) {
			require PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('database xml desc file or output directory not exists. ');	
		}

		$xml2array = sw_xml::factory('xml2array');

		$xml2array->set_filename($this->__xml_filename);
		$array = $xml2array->xml2array();
		
		$tmp = isset($array['databases']['database'][0]) ? $array['databases']['database'] : $array['databases'];

		foreach ($tmp as $key => $value) {
			$output = $this->__out_put_dir . self::PREFIX . $value['@name'] . '.sql';
			$output_str = self::SQL_COMMENT . self::SPACE_KEY . self::VIM_HEADER . PHP_EOL;
			$output_str .= self::SQL_COMMENT . PHP_EOL;
			$output_str .= self::SQL_COMMENT . 'Current Database: `' . $value['@name'] . '`'; 
			$output_str .= self::SQL_COMMENT . PHP_EOL . PHP_EOL;
			$output_str .= 'CREATE DATABASE /*!32312 IF NOT EXISTS*/ `' . $value['@name'] . '` /*!40100 DEFAULT CHARACTER SET utf8 */;' . PHP_EOL . PHP_EOL;
			$output_str .= 'USE `' . $value['@name'] . '`;' . PHP_EOL;

			$tmp_table = isset($value['tables']['table'][0]) ? $value['tables']['table'] : $value['tables'];
			foreach ($tmp_table as $table) {
				$output_str .= $this->_parse_table($table);
			}	

			file_put_contents($output, $output_str);
		}
	}

	// }}}
	// {{{ public function set_filename()

	/**
	 * 设置文件名 
	 * 
	 * @param string $filename 
	 * @access public
	 * @return sw_create_database
	 */
	public function set_filename($filename)
	{
		if (!file_exists($filename)) {
			require PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('database xml desc file not exists. ');	
		}

		if (!is_readable($filename)) {
			require PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('database xml desc file not readable. ');	
		}

		$this->__xml_filename = $filename;

		return $this;
	}

	// }}}
	// {{{ public function set_dirname()

	/**
	 * 设置输出目录 
	 * 
	 * @param string $dirname
	 * @access public
	 * @return sw_create_database
	 */
	public function set_dirname($dirname)
	{
		if (!file_exists($dirname)) {
			require PATH_DSWAN_LIB . 'sw_exception.class.php';
			throw new sw_exception('database output directory not exists. ');	
		}

		$dirname = rtrim($dirname, '/') . '/';
		$this->__out_put_dir = $dirname;

		return $this;
	}

	// }}}
	// {{{ protected function _parse_column()

	/**
	 * 解析每个表的字段
	 * 
	 * @param array $column 
	 *	  array (
	 *			 '@name' => 'gprint_id',
	 *			 'desc' => 'GPRINT id',
	 *			 'type' => 'int',
	 *			 'nullable' => 'false',
	 *			 'precision' => '11',
	 *			 'auto' => 'true',
	 *			 'unsigned' => 'true',
	 *			 'default' => '',
	 *		 ),
	 *
	 * @access protected
	 * @return array
	 */
	protected function _parse_column(array $column)
	{
		//连接描述信息
		$desc_str = self::SQL_COMMENT . $column['@name'] . PHP_EOL;
		$desc_str .= self::SQL_COMMENT . "\t" . $column['desc'] . PHP_EOL;
		// {{{ 建表语句

		$sql_str = "\t" . self::SQL_SIGN . $column['@name'] . self::SQL_SIGN . self::SPACE_KEY;

		// `column_name` type (precision) 
		$sql_str .= $column['type'] . '(' . $column['precision'] . ')' . self::SPACE_KEY;

		// charset
		if (isset($column['charset'])) {
			$sql_str .= 'CHARACTER SET' . self::SPACE_KEY . $column['charset'] . self::SPACE_KEY;
		}

		// unsigned
		if (isset($column['unsigned']) && 'true' === $column['unsigned']) {
			$sql_str .= 'UNSIGNED' . self::SPACE_KEY;
		}

		// not null
		if (isset($column['nullable']) && 'false' === $column['nullable']) {
			$sql_str .= 'NOT NULL' . self::SPACE_KEY;
		}

		// auto
		if (isset($column['auto']) && 'true' === $column['auto']) {
			$sql_str .= 'AUTO_INCREMENT' . self::SPACE_KEY;
		}

		// default
		if (isset($column['default']) && '' !== $column['default']) {
			$sql_str .= 'DEFAULT' . self::SPACE_KEY . '\'' . $column['default'] . '\'';
		}

		$sql_str .= ',' . PHP_EOL;

		// }}}
		return array('sql' => $sql_str, 'desc' => $desc_str);
	}

	// }}}
	// {{{ protected function _parse_key()

	/**
	 * 解析每个表的主键和索引
	 * 
	 * @param array $column 
	 *
	 *	 array (
	 *		 '@name' => '',
	 *		 'desc' => '',
	 *		 'type' => 'primary',
	 *		 'fields' =>
	 *			 array (
	 *				 'field' =>
	 *				 array (
	 *					'@name' => 'gprint_id',
	 *				 ),
	 *		 ),
	 *	 ),
	 *
	 * @access protected
	 * @return array
	 */
	protected function _parse_key(array $key)
	{
		if ('primary' === $key['type']) {
			$sql_str = "\t" . 'PRIMARY KEY' . self::SPACE_KEY . '(';
		} else {
			$sql_str = "\t" . 'KEY' . self::SQL_SIGN . $key['@name'] . self::SQL_SIGN . self::SPACE_KEY . '(';	
		}

		$tmp_field = isset($key['fields']['field'][0]) ? $key['fields']['field'] : $key['fields'];
		$tmp_sql_arr = array();
		foreach ($tmp_field as $value) {
			$tmp_sql_arr[] = self::SQL_SIGN . $value['@name'] . self::SQL_SIGN;
		}
		$sql_str .= implode(',', $tmp_sql_arr) . '),' . PHP_EOL;

		return $sql_str;
	}

	// }}}
	// {{{ protected function _parse_table()

	/**
	 * 解析每个表的字段
	 *
	 * @access protected
	 * @return string
	 */
	protected function _parse_table(array $table)
	{
		//连接描述信息
		$desc_str = PHP_EOL . self::SQL_COMMENT . self::FOLDING_SIGN_LEFT . self::SPACE_KEY;
		$desc_str .= 'table' . self::SPACE_KEY . $table['@name'] . PHP_EOL;
		$desc_str .= PHP_EOL . self::SQL_COMMENT . PHP_EOL . self::SQL_COMMENT . $table['desc'] . PHP_EOL;
		$desc_str .= self::SQL_COMMENT . PHP_EOL;
		
		//建表语句
		$sql_str = PHP_EOL . 'DROP TABLE IF EXISTS ' . self::SQL_SIGN . $table['@name'] . self::SQL_SIGN . ';';
		$sql_str .= PHP_EOL . 'CREATE TABLE' . self::SPACE_KEY . self::SQL_SIGN . $table['@name'] . self::SQL_SIGN;
		$sql_str .=  self::SPACE_KEY . '(' . PHP_EOL;

		//生成字段语句
		$tmp_column = isset($table['columns']['column'][0]) ? $table['columns']['column'] : $table['columns'];
		foreach ($tmp_column as $key => $column) {
			$return_arr = $this->_parse_column($column);	
			$sql_str .= $return_arr['sql'];
			$desc_str .= $return_arr['desc'];
		}

		//生成主键和索引
		$tmp_key = isset($table['keys']['key'][0]) ? $table['keys']['key'] : $table['keys'];
		foreach ($tmp_key as $key) {
			$sql_str .= $this->_parse_key($key);	
		}

		//去掉最后一个逗号
		$sql_str = rtrim($sql_str, PHP_EOL);
		$sql_str = rtrim($sql_str, ',');

		//生成建表语句的结尾
		$sql_str .= PHP_EOL . ')' . self::SPACE_KEY . 'ENGINE=' . $table['engine'] . self::SPACE_KEY;
		$sql_str .= 'DEFAULT CHARSET=' . $table['charset'] . ';' . PHP_EOL . PHP_EOL;
		$sql_str .= self::SQL_COMMENT . self::SPACE_KEY . self::FOLDING_SIGN_RIGHT;

		return ($desc_str . $sql_str);
	}

	// }}}
	// }}}
}
