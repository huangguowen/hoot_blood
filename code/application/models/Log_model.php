<?php 
	/**
	* logs 通用
	*/
	class Log_model extends BaseModel
	{
	 var $this_CI; // 在类库中为了调用CI系统的东西，特意记录CI的实例
   var $mydb;
	function __construct()
  {
             parent::__construct();
             $this->this_CI = & get_instance();
             // $this->mydb = $this->this_CI->load->database('online', true);
  }
  //t_dz_change_money table(无条件)
function get_gift_log($size,$page){
        $data = array();       
        $this->db->select('*');
        $this->db->from('t_dz_change_money');
         $this->db->order_by('id', 'desc');
           $start = ($page - 1) * $size;
          $this->db->limit($size, $start);
           $result = $this->db->get();
           if ($result->num_rows() > 0) {
         for ($i = 0; $i < $result->num_rows(); $i++) {
            $data[] = $result->row_array($i);
        }
        }
        return $data;
}
}
 ?>