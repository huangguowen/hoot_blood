<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="agent" name="m">
<input type="hidden" value="agent" name="c">
<input type="hidden" value="agent_list_1" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
关键字：  <input type="text" value="" class="input-text" name="info[keyword]" placeholder="输入姓名,游戏ID,代理ID">  
<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
</div>
</form>
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="5%">ID</th>
            <th width="10%">姓名</th>
            <th width="10%">手机号</th>

            <th width="10%">邀请码</th>
            <th width="10%">游戏ID</th>
            <th width="10%">地址</th>
            <th width="5%">会员数</th>
            <th width="5%">下级代理数</th>
             <th width="10%">可用金额</th>
            <th width="10%">累积金额</th>
              <th width="10%">最后登录时间</th>
            <th width="5%">代理状态</th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($infos)){
	$id = 0;
	foreach($infos as $info){
	$id ++;
?>   
	<tr>
	<td width="5%" align="center"><?php echo $id?></td>
	<td width="10%" align="center"><?php echo $info['realname']?></td>
	<td  width="10%" align="center"><?php echo $info['wechat']?></td>
	<td width="10%" align="center"><?php echo $info['extern_id']?></td>
	<td width="10%" align="center"><?php echo $info['play_id'] ?></td>
	<td width="10%" align="center"><?php echo $info['bank_place'].'-'.$info['bank_name']?></td>
<?php //根据play——id查询count 会员 ?>
<?php 
$where = "select * from t_bs_vip where parent_id = '$info[play_id]'";
$infoss = $this->vip_db->mylistinfo($where, $page, $pagesize = 20);
$number = count($infoss);
 ?>
 <?php 
$where = "select count(1) as count from (select count(1) from phpcmsv9.v9_admin a,t_dz_player b where pp_id = '$info[play_id]' or parent_id = '$info[play_id]' and (a.userid = b.id) group by a.userid) abc";
$cc = $this->account_db->get_one_by_sql($where);
  ?>
	<td width="5%" align="center"><?php echo $number?> </a>

	<td width="5%" align="center"><?php echo $cc['count']?></a>
		<td width="10%" align="center"><?php echo $info['keyong']?> </a>
	<td width="10%" align="center"><?php echo $info['leiji']?></a>
		<td width="10%" align="center"><?php echo date('Y-m-d H:i:s',$info['lastlogintime'])?> </a>
	<td width="5%" align="center"><?php if($info['status']==1){echo '已经开启';}else{echo '禁用';}?></a>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
 <div id="pages"> <?php echo $pages?></div>
</div>
</div>
</form>
</body>
</html>
<script type="text/javascript">
<!--
	function discount(id, name) {
	window.top.art.dialog({title:'<?php echo L('discount')?>--'+name, id:'discount', iframe:'?m=pay&c=payment&a=discount&id='+id ,width:'500px',height:'200px'}, 	function(){var d = window.top.art.dialog({id:'discount'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'discount'}).close()});
}
function detail(id, name) {
	window.top.art.dialog({title:'<?php echo L('discount')?>--'+name, id:'discount', iframe:'?m=pay&c=payment&a=public_pay_detail&id='+id ,width:'500px',height:'550px'});
}
//-->
</script>