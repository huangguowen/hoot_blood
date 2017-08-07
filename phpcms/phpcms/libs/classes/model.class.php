<?php 
/**
 *  model.class.php 数据模型基类
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-7
 */
defined('IN_PHPCMS') or exit('Access Denied');
pc_base::load_sys_class('db_factory', '', 0);
class model {
	
	//数据库配置
	protected $db_config = '';
	//数据库连接
	protected $db = '';
	//调用数据库的配置项
	protected $db_setting = 'default';
	//数据表名
	protected $table_name = '';
	//表前缀
	public  $db_tablepre = '';
	
	public function __construct() {
		if (!isset($this->db_config[$this->db_setting])) {
			$this->db_setting = 'default';
		}
		$this->table_name = $this->db_config[$this->db_setting]['tablepre'].$this->table_name;
		$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->db = db_factory::get_instance($this->db_config)->get_database($this->db_setting);
	}
		
	/**
	 * 执行sql查询
	 * @param $where 		查询条件[例`name`='$name']
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $limit 		返回结果范围[例：10或10,10 默认为空]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @param $key          返回数组按键名排序
	 * @return array		查询结果集数组
	 */
	final public function select($where = '', $data = '*', $limit = '', $order = '', $group = '', $key='') {
		// echo 1;die;
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->select($data, $this->table_name, $where, $limit, $order, $group, $key);
	}

	/**
	 * 查询多条数据并分页
	 * @param $where
	 * @param $order
	 * @param $page
	 * @param $pagesize
	 * @return unknown_type
	 */
	final public function listinfo($where = '', $order = '', $page = 1, $pagesize = 20, $key='', $setpages = 10,$urlrule = '',$array = array(), $data = '*') {
		$where = to_sqls($where);
		$this->number = $this->count($where);
		$page = max(intval($page), 1);
		$offset = $pagesize*($page-1);
		$this->pages = pages($this->number, $page, $pagesize, $urlrule, $array, $setpages);
		$array = array();
		if ($this->number > 0) {
			return $this->select($where, $data, "$offset, $pagesize", $order, '', $key);
		} else {
			return array();
		}
	}

	/**
	 * 获取单条记录查询
	 * @param $where 		查询条件
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @return array/null	数据查询结果集,如果不存在，则返回空
	 */
	final public function get_one($where = '', $data = '*', $order = '', $group = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->get_one($data, $this->table_name, $where, $order, $group);
	}
	
	/**
	 * 直接执行sql查询
	 * @param $sql							查询sql语句
	 * @return	boolean/query resource		如果为查询语句，返回资源句柄，否则返回true/false
	 */
	final public function query($sql) {
		$sql = str_replace('phpcms_', $this->db_tablepre, $sql);
		return $this->db->query($sql);
	}
	
	/**
	 * 执行添加记录操作
	 * @param $data 		要增加的数据，参数为数组。数组key为字段值，数组值为数据取值
	 * @param $return_insert_id 是否返回新建ID号
	 * @param $replace 是否采用 replace into的方式添加数据
	 * @return boolean
	 */
	final public function insert($data, $return_insert_id = false, $replace = false) {
		return $this->db->insert($data, $this->table_name, $return_insert_id, $replace);
	}
	
	/**
	 * 获取最后一次添加记录的主键号
	 * @return int 
	 */
	final public function insert_id() {
		return $this->db->insert_id();
	}
	
	/**
	 * 执行更新记录操作
	 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
	 * 						为数组时数组key为字段值，数组值为数据取值
	 * 						为字符串时[例：`name`='phpcms',`hits`=`hits`+1]。
	 *						为数组时[例: array('name'=>'phpcms','password'=>'123456')]
	 *						数组的另一种使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
	 * @param $where 		更新数据时的条件,可为数组或字符串
	 * @return boolean
	 */
	final public function update($data, $where = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->update($data, $this->table_name, $where);
	}
	
	/**
	 * 执行删除记录操作
	 * @param $where 		删除数据条件,不充许为空。
	 * @return boolean
	 */
	final public function delete($where) {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->delete($this->table_name, $where);
	}
	
	/**
	 * 计算记录数
	 * @param string/array $where 查询条件
	 */
	final public function count($where = '') {
		$r = $this->get_one($where, "COUNT(*) AS num");
		return $r['num'];
	}
	
	/**
	 * 将数组转换为SQL语句
	 * @param array $where 要生成的数组
	 * @param string $font 连接串。
	 */
	final public function sqls($where, $font = ' AND ') {
		if (is_array($where)) {
			$sql = '';
			foreach ($where as $key=>$val) {
				$sql .= $sql ? " $font `$key` = '$val' " : " `$key` = '$val'";
			}
			return $sql;
		} else {
			return $where;
		}
	}
	
	/**
	 * 获取最后数据库操作影响到的条数
	 * @return int
	 */
	final public function affected_rows() {
		return $this->db->affected_rows();
	}
	
	/**
	 * 获取数据表主键
	 * @return array
	 */
	final public function get_primary() {
		return $this->db->get_primary($this->table_name);
	}
	
	/**
	 * 获取表字段
	 * @param string $table_name    表名
	 * @return array
	 */
	final public function get_fields($table_name = '') {
		if (empty($table_name)) {
			$table_name = $this->table_name;
		} else {
			$table_name = $this->db_tablepre.$table_name;
		}
		return $this->db->get_fields($table_name);
	}
	
	/**
	 * 检查表是否存在
	 * @param $table 表名
	 * @return boolean
	 */
	final public function table_exists($table){
		return $this->db->table_exists($this->db_tablepre.$table);
	}
	
	/**
	 * 检查字段是否存在
	 * @param $field 字段名
	 * @return boolean
	 */
	public function field_exists($field) {
		$fields = $this->db->get_fields($this->table_name);
		return array_key_exists($field, $fields);
	}
	
	final public function list_tables() {
		return $this->db->list_tables();
	}
	/**
	 * 返回数据结果集
	 * @param $query （mysql_query返回值）
	 * @return array
	 */
	final public function fetch_array() {
		$data = array();
		while($r = $this->db->fetch_next()) {
			$data[] = $r;		
		}
		return $data;
	}
	
	/**
	 * 返回数据库版本号
	 */
	final public function version() {
		return $this->db->version();
	}


    public function sql_query($sql) {
        if (!empty($this->db_tablepre)) $sql = str_replace('phpcms_', $this->db_tablepre, $sql);
        return $this->query($sql);
    }
    
    public function fetch_next() {
        return $this->db->fetch_next();
    }


    //通过SQL语句查询一条结果
    public function get_one_by_sql($sql){
        $this->sql_query($sql);
        $res = $this->fetch_next();
        $this->free_result();
        return $res;
    }
        //通过sql语句查询数组
    public function get_array_by_sql($sql){
        $this->sql_query($sql);
        $res = $this->fetch_array();
        $this->free_result();
        return $res;
    }

            //通过sql语句查询数组
    public function get_array_by_sql_count($sql){
        $this->sql_query($sql);
        $res = $this->affected_rows();
        $this->free_result();
        return $res;
    }

    //释放数据库结果资源，调用底层完成
    public function free_result() {
        $this->db->free_result();
    }

		    //自定义分页查询{支持多表}
    public function mylistinfo($where = '', $page = 1, $pagesize = 10, $key='', $setpages = 10,$urlrule = '',$array = array()) {
        $sql = preg_replace('/select([^from].*)from/i', "SELECT COUNT(*) as count FROM ", $where);
        $this->sql_query($sql);
        $c = $this->fetch_next();
        $this->number = $c['count'];
        $page = max(intval($page), 1);
        $offset = $pagesize*($page-1);
        $this->pages = pages($this->number, $page, $pagesize, $urlrule, $array, $setpages);

        $r = $this->sql_query($where.' LIMIT '.$offset.','.$pagesize);

        while(($s = $this->fetch_next()) != false){

            $data[] = $s;

        }

        return $data;

    }


    		    //自定义分页查询{支持多表}
    public function mylistinfo_qiantao($qiantao = '',$where = '', $page = 1, $pagesize = 10, $key='', $setpages = 10,$urlrule = '',$array = array()) {
        $this->sql_query($qiantao);
        $c = $this->fetch_next();
        $this->number = $c['count'];
        $page = max(intval($page), 1);
        $offset = $pagesize*($page-1);
        $this->pages = pages($this->number, $page, $pagesize, $urlrule, $array, $setpages);

        $r = $this->sql_query($where.' LIMIT '.$offset.','.$pagesize);

        while(($s = $this->fetch_next()) != false){

            $data[] = $s;

        }

        return $data;

    }

    // 查询其上级 代理的信息
    public function get_pat_agent($id){
    	$sql = "select parent_id from phpcmsv9.v9_admin where userid = '$id'";
    	$result = $this->get_one_by_sql($sql);
    	return $result['parent_id'];
    }

  //查询下级所有的会员数
  public function get_agent_count(){

  }
//如果查询到了该play_id则将 agent_acount + 1
  public function update_agent_acount($play_id){
  		$sql = "select userid from phpcmsv9.v9_admin where play_id = '$play_id'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		$sql = "update phpcmsv9.v9_admin set agent_count = agent_count +'1' where play_id = '$play_id'";
    		$this->sql_query($sql);
    	}
  }

  public function get_parent_id($join_code){
  	$sql = "select play_id from phpcmsv9.v9_admin where code = '$join_code'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['userid'];
    	}else{
    		return 0;
    	}
  }

    public function get_play_id_for_userid($userid){
  	$sql = "select play_id from phpcmsv9.v9_admin where userid = '$userid'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['play_id'];
    	}else{
    		return 0;
    	}
  }


    public function get_code_foruserid($userid){
  	$sql = "select code from phpcmsv9.v9_admin where userid = '$userid'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['play_id'];
    	}else{
    		return 0;
    	}
  }

  public function check_agent_by_id($play_id){
$sql = "select userid from phpcmsv9.v9_admin where play_id = '$play_id'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['userid'];
    	}else{
    		return 0;
    	}
  }



  public function get_parent_id_by_id($id){
$sql = "select parent_id from phpcmsv9.v9_admin where userid = '$id'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['parent_id'];
    	}else{
    		return 0;
    	}
  }



  public function get_parent_id_by_code($code){
$sql = "select parent_id from phpcmsv9.v9_admin where code = '$code'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['parent_id'];
    	}else{
    		return 0;
    	}
  }


//查询是否存在ppid
  public function get_ppid_by_playid($code){
$sql = "select pp_id from phpcmsv9.v9_admin where code = '$code'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['pp_id'];
    	}else{
    		return 0;
    	}
  }


  //查询是否存在ppid
  public function get_ppid_by_play_id($code){
$sql = "select pp_id from phpcmsv9.v9_admin where play_id = '$code'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['pp_id'];
    	}else{
    		return 0;
    	}
  }


  //查询是否存在parentid
  public function get_parentid_by_play_id($code){
$sql = "select parent_id from phpcmsv9.v9_admin where play_id = '$code'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['parent_id'];
    	}else{
    		return 0;
    	}
  }

  //ppid查询 roleid
  public function get_roleid_by_ppid($ppid){
$sql = "select roleid from phpcmsv9.v9_admin where ppid = '$ppid'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		return $result['roleid'];
    	}else{
    		return 0;
    	}
  }

  //ppid查询 roleid
  public function get_roleid_by_playid($play_id){
$sql = "select parent_id from phpcmsv9.v9_admin where play_id = '$play_id'";
    	$result = $this->get_one_by_sql($sql);
    	if($result){
    		$parent_id = $result['roleid'];
    		$sql = "select roleid from phpcmsv9.v9_admin where play_id = '$parent_id'";
    		$result = $this->get_one_by_sql($sql);
    		if($result){
    			return $result['roleid'];
    		}else{
    			return 0;
    		}
    	}else{
    		return 0;
    	}
  }
}