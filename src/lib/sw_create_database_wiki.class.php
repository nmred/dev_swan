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
* 创建数据库字典
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_create_database_wiki
{
	// {{{ const

	/**
	 * SQL语句的分隔符 
	 */
	 const SQL_SIGN = '`';

	/**
	 * 生成的SQL 数据字典文件名称 
	 */
	 const WIKI_NAME = 'mysql_desc.txt';

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

		$output_str = '====== SwanSoft 产品数据字典 ======' . PHP_EOL . PHP_EOL;
		foreach ($tmp as $key => $value) {
			$output = $this->__out_put_dir . self::WIKI_NAME ;
			$output_str .= '===== ' . $value['@name'] . ' 数据库 =====' . PHP_EOL . PHP_EOL;

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

		$desc_str = '| ' . $column['@name'];
		$desc_str .= ' | ';
		$desc_str .= $column['type'];
		$desc_str .= ' | ';
		$desc_str .=  isset($column['nullable']) ? $column['nullable'] : ' ';
		$desc_str .= ' | ';
		$desc_str .=  $column['precision'];
		$desc_str .= ' | ';
		$desc_str .=  isset($column['default']) ? $column['default'] : ' ';
		$desc_str .= ' | ';
		$desc_str .=  isset($column['auto']) ? $column['auto'] : ' '; 
		$desc_str .= ' | ';
		$desc_str .=  isset($column['unsigned']) ? $column['unsigned'] : ' ';
		$desc_str .= ' |' . PHP_EOL; 

		$desc_str .= '| ::: | ' . $column['desc'] . ' ||||||' . PHP_EOL;

		return $desc_str;
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
			$sql_str = '^ 主键 | ';
		} else {
			$sql_str = '^ 索引 | ';
		}

		$tmp_field = isset($key['fields']['field'][0]) ? $key['fields']['field'] : $key['fields'];
		$tmp_sql_arr = array();
		foreach ($tmp_field as $value) {
			$tmp_sql_arr[] = self::SQL_SIGN . $value['@name'] . self::SQL_SIGN;
		}
		$sql_str .= implode(',', $tmp_sql_arr) . '|' . PHP_EOL;

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
		$desc_str = '====' . $table['@name'] . '====' . PHP_EOL;
		$desc_str .= '^ 表名 | ' . $table['@name'] . ' |' . PHP_EOL;
		$desc_str .= '^ 描述 | ' . $table['desc'] . ' |' . PHP_EOL;
		$desc_str .= '^ Engine | ' . $table['engine'] . ' |' . PHP_EOL;
		$desc_str .= '^ 编码 | ' . $table['charset'] . ' |' . PHP_EOL;
		//生成主键和索引
		$tmp_key = isset($table['keys']['key'][0]) ? $table['keys']['key'] : $table['keys'];
		foreach ($tmp_key as $key) {
			$desc_str .= $this->_parse_key($key);	
		}

		$desc_str .= PHP_EOL;

		$desc_str .= '^ 字段名 ^ 类型 ^ nullable ^ 宽度 ^ 默认值  ^ auto ^ unsigned ^' . PHP_EOL;

		//生成字段语句
		$tmp_column = isset($table['columns']['column'][0]) ? $table['columns']['column'] : $table['columns'];
		foreach ($tmp_column as $key => $column) {
			$desc_str .= $this->_parse_column($column);
		}


		return $desc_str;
	}

	// }}}
	// }}}
}
