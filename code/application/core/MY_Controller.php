<?php 
class MY_Controller extends CI_Controller
{
	var $log_user_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->out_line();
    }

    function out_line()
    {
        if (isset($_SESSION['user_id'])) {
            $this->log_user_id = $_SESSION['user_id'];
        }else {
        //     // $this->jump(3,base_url(),'长时间未操作，已经安全登出');
        }
    }

    function jump($time, $url, $text)
    {
        header("refresh:$time;url=$url");
        $html = "<body style=' background: url(" . base_url() . "static/images/bg.jpg) repeat-x scroll 0 0 #67ACE4;'>
                    <div id='container' style='margin: 0 auto;padding-top: 50px;text-align: center;width: 560px'>
                        <img style='border: medium none;margin-bottom: 50px' class='png' src='" . base_url() . "static/images/tx1.jpg' />
                          <div align='center' style='margin-top:100px;background-color:##FFFEE6;color:red;font-size:18px;font-weight:bold;'>" . $text . "&nbsp&nbsp请稍等...该页将在" . $time . "秒后自动跳转!</div>
                          
                    </div>
                    <div style='background: url(" . base_url() . "images/error_cloud.png) repeat-x scroll 0 0 transparent;bottom:0;height:170px;position:absolute;width:100%' class='png'></div>
                </body>";
        echo $html;

    }

     function maintenance($data)
    {
       
        $html = "<body style=' background: url(" . base_url() . "static/images/bg.jpg) repeat-x scroll 0 0 #67ACE4;'>
                    <div id='container' style='margin: 0 auto;padding-top: 50px;text-align: center;width: 560px'>
                        <img style='border: medium none;margin-bottom: 50px' class='png' src='" . base_url() . "static/images/tx1.jpg' />
                          <div align='center' style='margin-top:100px;background-color:##FFFEE6;color:red;font-size:18px;font-weight:bold;'>" . "&nbsp&nbsp...$data</div>
                          
                    </div>
                    <div style='background: url(" . base_url() . "images/error_cloud.png) repeat-x scroll 0 0 transparent;bottom:0;height:170px;position:absolute;width:100%' class='png'></div>
                </body>";
        echo $html;

    }
       /*
     * 权限验证
     * */
    function permission_valid($permission, $uid)
    {
        if (!$this->consolelib->canview($uid, $permission)) {
            $this->jump_message(3, base_url() . "users/site.html", "您没有权限访问，如有问题请联系管理员!<br><br>");
            die();
        }
    }

        function canview($user_id,$fun)
    {
        return $this->getUserCanView($user_id, $fun);
    }
    
    function getUserCanView($user_id,$fun)
    {
        if($user_id<1)
        {
            return false;
        }
        $cfg = array('fun'=>$fun,'user_id'=>$user_id);
        $this->conn();
        $this->mydb->select('id,ac');
        $this->mydb->from('menucns');
        $this->mydb->where('user_id', $user_id);
        $this->mydb->like('fun', $fun);
        $result = $this->mydb->get();
        $data = array();
        for ($i = 0; $i < $result->num_rows(); $i ++) {
            $row = $result->row($i);
            $data[$i]['id'] = $row->id;
            $data[$i]['ac'] = $row->ac;
        }
        if(count($data)>0)
        {
        $ac = 0;
        for ($i=0;$i<count($data);$i++)
        {
            $ac = $ac + $data[$i]['ac'];
        }

        if ($ac>0)
        {
            return true;
        }
    }
        else
        {
            return false;
        }
        
        return false;
    }
    

     
 
    
}
 
 ?>