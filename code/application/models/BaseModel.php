<?php 

//这个是操作Mysql的基类
class BaseModel extends CI_Model{  
  
    /** 
     * 该Model对应的表名 
     * @var string 
     */  
    var $table = '';  
  
    /** 
     * 该Model对应的主键名 
     * @var string 
     */  
    var $primaryKey = 'id';  
  
    public function __construct(){  
        parent::__construct();  
    }  
  
#region 通用操作  
    /** 
     * 执行sql @xwlyun 
     * @param $sql 
     * @param bool $affect_num 是否返回影响行数 
     * @return mixed 
     */  
    function query($sql,$affect_num=false){  
        $query = $this->db->query($sql);  
        if($affect_num){  
            $query = $this->db->affected_rows();  
        }  
        return $query;  
    }  
  
    /** 
     * 返回多行数据 @xwlyun 
     * @param $sql 
     * @return mixed 
     */  
    function getRows($sql){  
        $query = $this->db->query($sql);  
        return $query->result_array();  
    }  
  
    /** 
     * 返回单行数据 @xwlyun 
     * @param $sql 
     * @return mixed 
     */  
    function getRow($sql){  
        $data = $this->getRows($sql);  
        return @$data[0];  
    }  
  
    /** 
     * 返回单行首列数据 @xwlyun 
     * @param $sql 
     * @return mixed 
     */  
    function getOne($sql){  
        $data = $this->getRow($sql);  
        return @current($data);  
    }  
  
    /** 
     * 插入数据 @xwlyun 
     * @param $data 插入的数据array 
     * @param string $table 表名 
     * @param bool $return 是否需要返回插入成功的id 
     * @return bool 
     */  
    function insert($data, $table='', $return = false){  
        if(!$table){  
            if(!$this->table){  
                return false;  
            }  
            $table = $this->table;  
        }  
        $query = $this->db->insert($table, $data);  
        if($return){  
            $query = $this->db->insert_id();  
        }  
        return $query;  
    }  
  
    /** 
     * 删除数据 @xwlyun 
     * @param $where where (e.g. array('field' =>'value',...)) 
     * @param string $table 
     * @return bool 
     */  
    function delete($where, $table='',$limit=1){  
        if(!$table){  
            if(!$this->table){  
                return false;  
            }  
            $table = $this->table;  
        }  
        $this->db->where($where);  
        $this->db->limit($limit);  
        $this->db->delete($table);  
    }  
  
    /** 
     * 更新数据 @xwlyun 
     * @param $where where (e.g. array('field' =>'value',...)) 
     * @param $update update (e.g. array('field' =>'value',...)) 
     * @param string $table 
     * @param int $limit 
     * @return bool 
     */  
    function update($where,$update,$table='',$limit=1){  
        if(!$table){  
            if(!$this->table){  
                return false;  
            }  
            $table = $this->table;  
        }  
        $this->db->where($where);  
        $this->db->limit($limit);  
        $this->db->update($table, $update);  
        return $this->db->affected_rows();  
    }  
#endregion  
  
#region ci框架链式  
    /** 
     * where (e.g. array('field' =>'value',...)) @xwlyun 
     * @param array $where 
     * @return $this 
     */  
    function where($where=array()){  
        foreach($where as $k=>$v){  
            $this->db->where($k, $v);  
        }  
        return $this;  
    }  
  
    /** 
     * limit $offset,$limit @xwlyun 
     * @param int $limit 
     * @param int $offset 
     * @return $this 
     */  
    function limit($limit=1,$offset=0){  
        $this->db->limit($limit,$offset);  
        return $this;  
    }  
  
    /** 
     * order by (e.g. array('field1'=>'asc',...)) @xwlyun 
     * @param array $orderby 
     * @return $this 
     */  
    function orderby($orderby=array()){  
        if($orderby){  
            foreach($orderby as $k=>$v){  
                $this->db->order_by($k, $v);  
            }  
        }else{  
            $this->db->order_by($this->primaryKey, 'asc');  
        }  
        return $this;  
    }  
  
    /** 
     * where in (e.g. array('field1'=>array('value1','value2',...))) @xwlyun 
     * @param array $wherein 
     * @return $this 
     */  
    function wherein($wherein=array()){  
        if($wherein){  
            foreach($wherein as $k=>$v){  
                $this->db->where_in($k, $v);  
            }  
        }  
        return $this;  
    }  
  
    /** 
     * where not in (e.g. array('field1'=>array('value1','value2',...))) @xwlyun 
     * @param array $wherenotin 
     * @return $this 
     */  
    function wherenotin($wherenotin=array()){  
        if($wherenotin){  
            foreach($wherenotin as $k=>$v){  
                $this->db->where_not_in($k, $v);  
            }  
        }  
        return $this;  
    }  
  
    /** 
     * 获取总数 @xwlyun 
     * @return mixed 
     */  
    function count($table){  
        $this->db->from($table);  
        return $this->db->count_all_results();  
    }  
  
    /** 
     * select (e.g. array('field1','field2',...) or 'filed1,filed2,...') @xwlyun 
     * @param string $select 
     * @return mixed 
     */  
    function select($select="*"){  
        // select  
        $this->db->select($select);  
        // get  
        $query = $this->db->get($this->table);  
        // return  
//      if($this->limit == 1){  
//          $data = $query->row_array();  
//      }else{  
        $data = $query->result_array();  
//      }  
        return $data;  
    }  

      //根据数据制作成分页
    function page_array($count,$page,$array,$order){
        global $countpage; #定全局变量
        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
        $start=($page-1)*$count; #计算每次分页的开始位置
        if($order==1){
            @$array=array_reverse($array);
        }
        $totals=count($array);
        $countpage=ceil($totals/$count); #计算总页面数
        $pagedata=array();
        @$pagedata=array_slice($array,$start,$count);
        return $pagedata; #返回查询数据
    }

   function getPageInfo($tcount, $page, $size)
    {
    	// print_r($tcount);die;
        $data = array();

        $data ['start'] = 1; //首页
        $pageCount = intval($tcount / $size);
        $pageOut = $tcount % $size;
        if ($pageOut > 0) {
            $pageCount = $pageCount + 1;
        }

        $data ['count'] = $pageCount; //总的页数

        //前一页
        if ($page > 1) {
            $data ['prep'] = $page - 1;
        } else {
            $data ['prep'] = $page;
        }

        //后一页
        if ($page < $pageCount) {
            $data ['next'] = $page + 1;
        } else {
            $data ['next'] = $page;
        }

        $data ['end'] = $pageCount;
        $data ['now'] = $page;
        return $data;
    }

     //获取页数列表
    function getPageList($maxPage, $spage)
    {
        $html = "";
        for ($i = 1; $i <= $maxPage; $i++) {
            if ($i == $spage) {
                $html = $html . "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>";
            } else {
                $html = $html . "<option value=\"" . $i . "\">" . $i . "</option>";
            }
        }
        return $html;
    }

    function get_id_byctro($ctro){
        $sql = "select id from t_bs_func where func_url = '$ctro'";
        $fun_id = $this->getOne($sql);
        return $fun_id;
    }
      //获取部门id
  function get_deptid(){
      $this->db->select('id,dept_name');
        $this->db->from('bs_dept');
        $result = $this->db->get();
       $data = $result->row_array();
         for ($i = 0; $i < $result->num_rows(); $i++) {
            $row = $result->row($i);
          if(($row->id) != 1){
            $users[$i]['id'] = ($row->id);
            $users[$i]['dept'] = ($row->dept_name);
        }
        }
        return $users;
  }

  //获取部门id
  function get_deptid_byid($userid){
      $sql = "select dept_id from t_bs_user where id = '$userid'";
      $dept_id = $this->getOne($sql);
      return $dept_id;
  }

//权限验证
    function permission_valid($controller,$user_id){
        if($user_id != 1){
        //功能的id
        $fun_id = $this->get_id_byctro($controller);

        $dept_id = $this->get_deptid_byid($user_id);
        //用户id
        $sql = "select id from t_bs_funcnum where (dept_id = '$dept_id' or user_id = '$user_id') and func_id = '$fun_id'";
        @$id = $this->getOne($sql);
        if(!isset($id)){
             echo "<div align='center' style='color:#F00 ; font-size:40px ; height=70px' >抱歉~您没有这个权限<div>";die;
        }
        }
       
    }
 
  
}  
 ?>