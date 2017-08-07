<?php 


defined('IN_PHPCMS') or exit('No permission resources.'); 
/**
 * 点击统计
 */
$db = '';
$db = pc_base::load_model('order_model');

    function apiinterface()
    {

      global $db;
       //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA']; 
        $xta  = xml_to_array($xml);  
        $order_id = $xta['out_trade_no'];

//写入到日志
writeLog('订单号：'.$order_id);

  $sql = "select * from t_dz_unfinished_consume where consume_number = '$orderid' and pay_status = 1";
  $res = $db->get_one_by_sql($sql);

  if(empty($res)){
   return_xml_success('failed');
}

       //修改订单状态
    $info = array('pay_status' => 2);
  $db->update($info,array('consume_number'=>$order_id));

//写入到日志
writeLog('订单号：'.$res['consume_number'].'-支付金额'.$res['pay'].'-别名'.$res['nickname']);

  //返回成功
  return_xml_success('ok');

    }

    //文件写入

    function writeLog($msg){

         $dir = ('apilogs');
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);
        }
 $logFile = 'apilogs'.'/'.date('Y-m-d').'.txt';
 $msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
 file_put_contents($logFile,$msg,FILE_APPEND );
}

function set_xml($args)
    {
        $xml = "<xml>";
        foreach ($args as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
}
function return_xml_success($msg){
        $arr = array(
            'return_code'   =>  'SUCCESS',
            'return_msg'    =>  $msg
        );
        echo set_xml($arr);
        exit();
 }



function xml_to_array($xml)                              
{                                                        
//禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;                                         
} 

apiinterface();

 ?>