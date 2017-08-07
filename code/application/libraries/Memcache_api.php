<?php
class Memcache_api{
	var $this_CI;  //ÔÚÀà¿âÖÐÎªÁËµ÷ÓÃCIÏµÍ³µÄ¶«Î÷£¬ÌØÒâ¼ÇÂ¼CIµÄÊµÀý
	var $mydb;	   //±¾Àà¿âÖÐÓÃµ½×î¶àµÄÊý¾Ý¿âÁ´½Ó
	var $time;
	var $base_url;
	var $dbname;
	var $tbname;
	var $tb_struts = array();
	var $cache;
	var $memcache;
// 	var $host = "192.168.0.134";
 	var $host = "127.0.0.1";
 //	var $host = "172.18.0.10";
// 	var $host = "192.168.0.151";
	//var $host = "172.18.0.120";
	var $allHosts = array();
	var $memcacheKey = "ITSERVER";
	//Í¼Æ¬ÑéÖ¤ÂëµÄ»º´æ
	var $captshowcacheKey = 'captshow';
	//¶ÌÐÅÑéÖ¤ÂëµÄ»º´æ
	var $duanxincacheKey = 'duanxin';
	
	var $net_pos = array(
		'IN'	=>1,
		'OUT'	=>2,
		'NOFIND'=>3,
	);
	
	var $ret_des = array(
		'1'		=>'Í¨¹ý',
		'2'		=>'¾Ü¾ø',
		'3'		=>'ÓÐÌõ¼þÍ¨¹ý',
		'4'		=>'--',
		'5'		=>'Íê³É'
	);
	var $ret_vaule = array(
		'YES'	=>1,
		'NO'	=>2,
		'MAYBE'	=>3, //ÓÐÌõ¼þµÄÍ¨¹ý
		'YN'	=>4, //³õÊ¼×´Ì¬·ÇYES£¬·ÇNO
		'OVER'	=>5,
	);
	
	
	var $cret_des = array(
		'1'		=>'Í¨¹ý',
		'2'		=>'¾Ü¾ø',
	);
	var $cret_vaule = array(
		'YES'	=>1,
		'NO'	=>2,
	);
  
  /*
 * ÓÉÓÚ¸Ã»ù´¡Àà¿â²»µ÷ÓÃÈÎºÎÊµ¼ÊÄ£¿é£¬ËùÒÔÔÚCI¼Ü¹¹ÖÐ¿ÉÒÔ±»ÈÎºÎÀà×÷Îª¼Ì³Ð
 * */
	function MY_Base()
	{
		$this->this_CI =& get_instance();		
		$this->time = $this->time();
		$this->base_url = base_url();
		//$this->allHosts[] = "172.18.0.120";
		//$this->allHosts[] = "172.18.0.10";
		//$this->allHosts[] = "192.168.0.151";
		$this->allHosts[] = "127.0.0.1";
	}
	
	function time()
	{
		return date("Y-m-d H:i:s");
	}
	
	function getIP()
	{
		return $this->this_CI->input->ip_address();
	}
	
	function getNetPos()
	{
		$ip = $this->getIP();
		list($a,$b,$c,$d)=explode('.',$ip);
		if($a=='192'&&$b=='168')
		{
			if($c=='120')	//soc
			{
				$net = 'IN';
				$net_space = 'SOFT';
			}
			else //¹«Ë¾µÄGUOXINÓò
			{
				$net = 'OUT';
				$net_space = 'GUOXIN';
			}
		}
		else if($a=='172'&&$b=='16') //RDC
		{
			$net = 'IN';
			$net_space = 'RDC';
		}
		else
		{
			$net = 'OUT';
			$net_space = 'GUOXIN';
		} 
		$my_pos = $this->net_pos[$net];
		return $my_pos;
	}
	
	function arrayToList($data,$selected_id)
	{
		$html = "";
		foreach ($data as $key=>$value)
		{
			if($selected_id==$key)
			{
				$html = $html."<option value=\"".$key."\" selected=\"selected\">".$value."</option>";
			}
			else
			{
				$html = $html."<option value=\"".$key."\">".$value."</option>";
			}
		}
		return $html;
	}
	
	function init($dbname,$tbname)
	{
		if($dbname!=$this->dbname||$tbname!=$this->tbname)
		{
			$this->dbname = $dbname;
			$this->tbname = $tbname;
			
			$this->this_CI->load->library('misc');
			$misclib 	= new Misc;
			$this->tb_struts = $misclib->getTbFieldList($this->dbname,$this->tbname);
		}
	}
	
	function conndb()
	{
		$this->mydb = $this->this_CI->load->database($this->dbname,true);
	}
			
	//Ìí¼Ó
	function add($data)
	{	
		$this->conndb();
		$this->mydb->insert($this->tbname, $data);
		$id = $this->mydb->insert_id();
		return $id;
	}
	
	//É¾³ý
	function del($id)
	{	
		$this->conndb();
		$this->mydb->where('id', $id);
		$this->mydb->delete($this->tbname); 
		return $id;
	}
	
	//É¾³ýÒ»Àà
	function delAll($config)
	{	
		$this->conndb();
		foreach($config as $key => $value)
		{
			$this->mydb->where($key, $value);
		}
		$this->mydb->delete($this->tbname); 
	}
	
	//¸üÐÂÒ»¸öÊý¾Ý¿âÖÐµÄÊý¾Ý
	function update($data,$id)
	{	
		$this->conndb();
		$this->mydb->where('id',$id);
		$this->mydb->update($this->tbname, $data);
		return $id;
	}
	
	//»ñÈ¡±íÖÐµÄÒ»ÌõÊý¾Ý
	function getInfoById($id)
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		$this->mydb->where('id', $id);
		$result = $this->mydb->get();
		$data = array();
		if($result->num_rows()>0)
		{
			$row = $result->row();
			$data = $this->sf_table($row);
		}
		return $data;
	}
	
	//»ñÈ¡±íÖÐµÄÒ»ÌõÊý¾Ý´ÓCACHEÖÐ»ñÈ¡
	function getInfoFromCacheById($id)
	{
		$data = array();
		if(count($this->cache)>0)
		{	
		}
		else
		{
			$this->getAllToCache();
		}
		if(isset($this->cache[$id]))
		{
			$data = $this->cache[$id];
		}	
		return $data;
	}
	
	//»ñÈ¡ËùÓÐÊý¾Ý
	function getAll()
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		$result = $this->mydb->get();
		$data = array();
		for($i=0;$i<$result->num_rows();$i++)
		{
   			$row = $result->row($i);
   			$data[$i] = $this->sf_table($row);
		}
		return $data;
	}
	
	//°ÑÕû¸ö±íµÄÊý¾Ý´æÈëÄÚ´æÖÐ£¬Ö÷ÒªÓÃÔÚÊý¾ÝÁ¿Ð¡µÄ±í£¬±íµÄÊý¾ÝÌ«´ó²»ÒªÓÃ
	function getAllToCache()
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		$result = $this->mydb->get();
		$this->cache = array();
		for($i=0;$i<$result->num_rows();$i++)
		{
   			$row = $result->row($i);
   			$this->cache[$row->id] = $this->sf_table($row);
		}
		return true;
	}
	
	//»ñÈ¡Êý¾Ý±íÖÐÄ³Ò»²¿·ÖÊý¾Ý£¬¸ù¾ÝÌõ¼þ ½öÏÞÓÚÊý×ÖÐÍµÄ×Ö¶ÎËÑË÷
	function getDataByInt($config)
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		foreach($config as $key => $value)
		{
			$this->mydb->where($key, $value);
		}
		$result = $this->mydb->get();
		$data = array();
		for($i=0;$i<$result->num_rows();$i++)
		{
   			$row = $result->row($i);
   			$data[$i] = $this->sf_table($row);
		}
		return $data;
	}
	
	//»ñÈ¡Êý¾Ý±íÖÐÄ³Ò»²¿·ÖÊý¾Ý£¬¸ù¾ÝÌõ¼þ ½öÏÞÓÚ×Ö·û´®µÄ×Ö¶ÎËÑË÷
	function getDataByStr($config)
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		foreach($config as $key => $value)
		{
			$this->mydb->or_like($key, $value);
		}
		$result = $this->mydb->get();
		$data = array();
		for($i=0;$i<$result->num_rows();$i++)
		{
   			$row = $result->row($i);
   			$data[$i] = $this->sf_table($row);
		}
		return $data;
	}
	
	//»ñÈ¡Êý¾Ý±íÖÐÄ³Ò»²¿·ÖÊý¾Ý£¬¸ù¾ÝÌõ¼þ ½öÏÞÓÚÊý×ÖÐÍµÄ×Ö¶ÎËÑË÷
	function getDataByIntLimit($config,$pagecfg,$sortcfg)
	{
		$this->conndb();
		$this->mydb->select('*');
		$this->mydb->from($this->tbname);
		foreach($config as $key => $value)
		{
			$this->mydb->where($key, $value);
		}
		foreach($sortcfg as $key => $value)
		{
			$this->mydb->order_by($key, $value);
		}
		$result = $this->mydb->get();
		$data = array();
		$page = $pagecfg['page'];
		$size = $pagecfg['size'];
		if($page<1)
		{
			$page = 1;
		}
		$start = ($page-1)*$size;
		$index = 0;
		
		for($i=$start;($i<$result->num_rows()&&$i<$start+$size);$i++)
		{
   			$row = $result->row($i);
   			$data[$index] = $this->sf_table($row);
   			$index++;
		}
		return $data;
	}
	
	function sf_table($row)
	{
		$data = array();
		$this->this_CI->load->library('user');
		$userlib 	= new User;
		for($i=0;$i<count($this->tb_struts);$i++)
		{
			$field = $this->tb_struts[$i];
			$data[$field]	= $row->$field;
			if($field=='user_id')
			{
				$data['user_name'] = $userlib->getName($row->$field);
			}
		}
		return $data;
	}
	
	//¶ÔÒ»¸öÊý×é×ª»¯ÎªÑ¡Ïî
	function getSelectOption($sarry,$sed)
	{
	    $html = "";
		foreach ($sarry as $key=>$value)
		{
		    if ($key==$sed)
		    {
		    	$html = $html . "<option value=\"" . $key . "\" selected=\"selected\">" . $value . "</option>";
		    } else {
		    	$html = $html . "<option value=\"" . $key . "\">" . $value . "</option>";
		    }
		}
		return $html;
	}
	
	function sendMail($to,$subject,$message,$attfile,$encode="gb2312")
	{
	    $this->this_CI->load->library('maillib');
	    $gmail = new Maillib;
	    $gmail->sendTextAndAFile($to, '', $subject, $message, $attfile, $encode);
	}
	
	
	function getSelectOptionEx($sarry,$key,$val,$sid)
	{
		$html = "";
		for($i=0;$i<count($sarry);$i++)
		{
			if ($sarry[$i][$key]==$sid)
			{
				$html = $html . "<option value=\"" . $sarry[$i][$key] . "\" selected=\"selected\">" . $sarry[$i][$val] . "</option>";
			} 
			else 
			{
				$html = $html . "<option value=\"" . $sarry[$i][$key] . "\">" . $sarry[$i][$val] . "</option>";
			}
		}
		return $html;
	}
	
	
	function connectmemcache($host="127.0.0.1")
	{
		$this->memcache = new Memcache;
		$ret = $this->memcache->connect($host, 11211);
		if($ret==false)
		{
			for($i=0;$i<count($this->allHosts);$i++)
			{
			    $ret = $this->memcache->connect($this->allHosts[$i], 11211);
			    if($ret==true)
			    {
			        $this->host = $this->allHosts[$i];
			    	return $ret;
			    }
			}
		}
		    
		if($ret==false)
		{    
			echo ("Could not connect Memcached server!!!");
		}	
		return $ret;
	}
	
	function showStatus()
	{
	    if($this->memcache==NULL)
	    {
	    	$ret = $this->connectmemcache($this->host);
	    }
	   	$this->memcache->addServer($this->host, 11211);
    
    	$stats = $this->memcache->getExtendedStats();
    	return $stats;
	}
	//图片验证码缓存
	function set_captshow($key,$value,$timeout=300)
	{
	    $key = $this->captshowcacheKey.$key;
	    $ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
		
		if ($ret==true)
		{    
			$wret = $this->memcache->set($key, $value, false, $timeout);
			if($wret==false)
			{    
				echo ("Failed to save data at the server"); // one day guoqi
				return false;
			}	
			return $wret;
		}
	}

		//短信验证码缓存
	function set_duanxin($key,$value,$timeout=300)
	{
	    $key = $this->duanxincacheKey.$key;
	    $ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
		
		if ($ret==true)
		{    
			$wret = $this->memcache->set($key, $value, false, $timeout);
			if($wret==false)
			{    
				echo ("Failed to save data at the server"); // one day guoqi
				return false;
			}	
			return $wret;
		}
	}

	
	function get_captshow($key)
	{
	    $key = $this->captshowcacheKey.$key;
	    $ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
		
		if($ret==false)
		{
			return false;
		}   
		else
		{ 
			$get_result = $this->memcache->get($key);
		}	
		return $get_result;
	}


	function get_duanxin($key)
	{
	    $key = $this->duanxincacheKey.$key;
	    $ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
		
		if($ret==false)
		{
			return false;
		}   
		else
		{ 
			$get_result = $this->memcache->get($key);
		}	
		return $get_result;
	}
	
	
	function delete($key)
	{
	    $key = $this->memcacheKey.$key;
		$ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
	
		if($ret==false)
		{
			return false;
		}
		else
		{
			$get_result = $this->memcache->delete($key, 10);
		}
		return true;
	}
	
	
	function resetMemcache()
	{
		$ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
	
		if($ret==false)
		{
			return false;
		}
		else
		{
			$this->memcache->flush();
		}
		return true;
	}
	
	
	function replace($key,$value)
	{
	    $key = $this->memcacheKey.$key;
		$ret = false;
		if($this->memcache==NULL)
		{
			$ret = $this->connectmemcache($this->host);
		}
		else
		{
			$ret = true;
		}
	
		if ($ret==true)
		{
			$wret = $this->memcache->replace($key, $value, FALSE, 92000);
			if($wret==false)
			{
				echo ("Failed to replace data at the server"); // one day guoqi
				return false;
			}
			return $wret;
		}
	}
	
	function test()
	{
		echo "<h2>Memcache Test111</h2>\n";
		$this->connectmemcache($this->host);
		// 		$version = $this->memcache->getVersion();
		// 		echo "Server's version: ".$version."<br/>\n";
	
		$tmp_object = new stdClass;
		$tmp_object->name = 'wanglinhui';
		$tmp_object->value = 'jjjjjjjjjjjjjjjjj';
	
				// 		$this->memcache->set('key', $tmp_object, false, 10) or die ("Failed to save data at the server");
		$this->set('key', $tmp_object);
		echo "Data set cache <br/>\n";
	
		$get_result = $this->get('key');
		echo "Data from the cache:<br/>\n";
		echo "Name:".$get_result->name."  Value:".$get_result->value;
										// 		print_r($get_result);
										// 		var_dump($get_result);
	}
	
	
}
