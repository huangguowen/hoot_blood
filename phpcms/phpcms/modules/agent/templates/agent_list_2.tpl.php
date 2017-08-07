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
            <th width="10%">ID</th>
            <th width="15%">代理属性</th>
            <th width="9%">邀请码</th>
            <th width="8%">真实姓名</th>
            <th width="8%">会员数</th>
            <th width="10%">最后登录时间</th>
            <?php //做成一个可点击状态改变的 ?>
            <th width="20%">状态</th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($infos)){
	$sum_amount = $sum_amount_succ = $sum_point_succ = $sum_point = '0';
	foreach($infos as $info){
		if($info['type'] == 1) {
			$num_amount++;
			$sum_amount += $info['money']; 
			if($info['status'] =='succ') $sum_amount_succ += $info['money'];
		}  elseif ($info['type'] == 2) {
			$num_point++;
			$sum_point += $info['money']; 
			if($info['status'] =='succ') $sum_point_succ += $info['money'];
		}
		
?>   
	<tr>
	<td width="10%" align="center"><?php echo $info['userid']?></td>
	<td  width="15%" align="center"><?php echo $roles[$info['roleid']] ?></td>
	<td width="9%" align="center"><?php echo L($info['code'])?></td>
	<td width="8%" align="center"><?php echo $info['realname']?></td>
	 <?php 
	 $this->account_db = pc_base::load_model('account_model');
$where = "select count(1) as count from t_bs_vip where parent_id = '$info[play_id]'";
$cc = $this->account_db->get_one_by_sql($where);
  ?>
	<td width="8%" align="center"><?php echo $cc['count']?></td>
	<td width="10%" align="center"><?php echo date('Y-m-d h:i:s',$info['lastlogintime']);?> </a>
	<td width="10%" align="center" onclick="change(<?php echo $info['userid'] ?>,<?php echo $info['status'] ?>)" style="cursor:pointer;"><font color="red"><?php if($info['status'] == 1){echo '已经开启';}else{echo '已经关闭';}?></font>
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

function change(id,status){
         $.ajax({
             type: "GET",
             url: "?m=agent&c=agent&a=change&pc_hash=<?php echo $_SESSION['pc_hash']; ?>"+'&id='+id+'&status='+status,
             success: function(data){
             	if(data == 1){
             		window.location.reload(); 
                     }else{
                     alert('你没有权限操作！！')
                     }
         }
         })
}
//-->
</script>