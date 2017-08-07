<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="agent_management" name="m">
<input type="hidden" value="agent_management" name="c">
<input type="hidden" value="billing_details" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">


<?php echo '日期';?>  <?php echo form::date('info[start_addtime]',$start_addtime)?><?php echo L('to')?>   <?php echo form::date('info[end_addtime]',$end_addtime)?> 
<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">


</div>
</form>
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
             <th width="5%">ID</th>
            <th width="5%">昵称</th>
            <th width="5%">代理级别</th>
            <th width="10%">是否支付</th>

            <th width="10%">备注</th>
            <th width="5%">提交时间</th>
            <th width="10%">支付时间</th>
           
            <th width="5%">关联ID</th>
            <th width="5%">关联昵称</th>

              <th width="5%">总额</th>
            <th width="10%">提成</th>
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
	<td width="10%" align="center"><?php echo $info['wechat']?></td>
	<td  width="5%" align="center"><?php if($info['roleid'] == 2){echo '总代理';}elseif($info['roleid'] == 3){echo "一级代理";}else{echo '三级代理';}?></td>
	<td width="10%" align="center"><?php if($info['status'] == 1){echo '<font color=red>已经支付</font>';}else{echo '还未支付';}?></td>
	<td width="10%" align="center"><?php echo $info['conment'] ?></td>
	<td width="5%" align="center"><?php echo $info['addtime']?></td>
	<td width="5%" align="center"><?php echo $info['paytime']?> </a>
	<td width="5%" align="center"><?php echo $info['extern_id']?> </a>
	<td width="5%" align="center"><?php echo $info['vip_nick']?></a>
	<td width="10%" align="center"><?php echo $info['pay']?> </a>
	<td width="10%" align="center"><?php echo $info['money']?></a>

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