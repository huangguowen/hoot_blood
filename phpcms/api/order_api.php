<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
/**
 * 点击统计
 */
$db = '';
$db = pc_base::load_model('rebate_model');
$vip_db = pc_base::load_model('vip_model');
//代理汇率
 function huilv($data){
   global $db;
    global $vip_db;
              $extern_id = $data['extern_id'];
                  $money = $data['money'];
                  $pay = $data['money'];
                  $consume_id = $data['consume_id'];
                  $vip_id = $data['vip_id']; 
                  //type 为1：第一次成为 xx 的会员  type 为2：后续的充值
                  $type = $data['type'];
   //根据extern_id 查询其属于哪一级代理
         $sql = "select roleid from phpcmsv9.v9_admin where code = '$extern_id'";
         $r = $db->get_one_by_sql($sql);
         $roleid = $r['roleid'];
        if($roleid == ''){
         echo 'no agent!!';die;
        }

    

         //总上级id
          $pp_id = $db->get_ppid_by_playid($extern_id);
          //父id
          $parent_id = $db->get_parent_id_by_code($extern_id);

         //根据extern_id 查询玩家id  邀请码即是extern_id 
         $sql = "select id,nickname from t_dz_player where extern_id = '$extern_id'";
         $play = $db->get_one_by_sql($sql);
         $play_id = $play['id'];


         $sql = "select nickname from t_dz_player where id = '$vip_id'";
         $nicks = $db->get_one_by_sql($sql);
          $vip_nickname = $nicks['nickname'];

  
             //t_bs_vip table add MY VIP
             if($type == 1){
                  $info = array('vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'addtime'=>date('Y-m-d H:i:s',time()),'parent_id'=>$play_id);
                   $res = $vip_db->insert($info);
             }

         //一级代理 则直接 取55% 
         if($roleid == 2){

          //如果没有ppid 如果有ppid 说明它是三级代理升级来的 它需要给一部分钱给他直属代理也就是parent_id
          if($pp_id == 0){
               $money = $money * 0.55;
      //根据extern_id 查询玩家id  邀请码即是extern_id 
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];



   $info = array('extern_id'=>$data['extern_id'],'money'=>$money,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
   $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }
}else{

   //上级是二级代理 则一级的上级能得到5%提成 自己得到50%
      //一级代理得到的钱
      $money_1 = 0.5 * $money;
       //根据extern_id 查询玩家id  邀请码即是extern_id 
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];

      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_1,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
   $res = $db->insert($info);
   //一级代理的上级代理(二级)得到的钱
      $money_2 = 0.05 * $money;
       //根据extern_id 查询玩家id  邀请码即是extern_id 
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$parent_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];

      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_2,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$parent_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
   $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }


}
//二级代理
}elseif ($roleid == 3) {
           //该二级代理并没有上家代理
           if($pp_id == 0){
            $money_2 = $money * 0.45;
      //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_2,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);
            //一级代理获取的钱
            $money_1 = $money * 0.1;

      //根据extern_id 查询玩家id  邀请码即是extern_id 
         $sql = "select parent_id from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $parent_id = $res['parent_id'];

            //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$parent_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];

         //一级代理获取的钱
         $info = array('extern_id'=>$data['extern_id'],'money'=>$money_1,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$parent_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }
}else{

      //二级代理有上家代理了，1：上家代理为 二级 2：上家代理为一级(生成三个订单)
   //1这个是自己得到的
   $money_1 = $money * 0.45;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_1,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

      //2这个是上级得到的
   $money_2 = $money * 0.05;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$parent_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];
   
      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_2,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$parent_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

            //这个是总上级pp_id 得到的
              $money_3 = $money * 0.05;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$pp_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];
   
      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_3,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$pp_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }
}
//三级代理
}else{
   //如果 该三级代理上级不是一级
   if($pp_id != 0){
       $money_3 = $money * 0.35;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_3,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

  $money_2 = $money * 0.1;

         //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$parent_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_2,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$parent_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

$money_1 = $money * 0.1;
         //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$pp_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_1,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$pp_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }
//该三级 上级是一级
   }else{

   //三级拿
       $money_3 = $money * 0.35;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$play_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_3,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$play_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

         //三级拿
       $money_1 = $money * 0.2;
   //查询手机号
         $sql = "select wechat from phpcmsv9.v9_admin where play_id = '$parent_id'";
         $res = $db->get_one_by_sql($sql);
         $phone = $res['wechat'];


      $info = array('extern_id'=>$data['extern_id'],'money'=>$money_1,'status'=>0,'addtime'=>date('Y-m-d H:i:s',time()),'consume_id'=>$consume_id,'play_id'=>$parent_id,'phone'=>$phone,'vip_id'=>$vip_id,'vip_nick'=>$vip_nickname,'pay'=>$pay);
      $res = $db->insert($info);

   if($res == 1){
         echo json_encode('success');
   }
   }
}

}

function apiss() {
      // $data = $_POST; play_id VIP会员的id
      $data = array('extern_id'=>27601,'money'=>100,'consume_id'=>10146,'vip_id'=>36,'type'=>1);
      if(count($data) != 0){
   huilv($data);  
}
}
apiss();
?>