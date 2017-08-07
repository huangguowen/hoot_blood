<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<?php include $this->admin_tpl('header', 'admin');?>
<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="payment" name="c">
<input type="hidden" value="agent_recharge" name="a">
<input type="hidden" value="879" name="menuid">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
		<div class="explain-col">
				
				<?php echo L('regtime')?>：
				<?php echo form::date('info[start_time]', $start_time)?>-
				<?php echo form::date('info[end_time]', $end_time)?>
					状态		
				<select name="info[status]">
					<option value='0'>全部</option>
					<option value='1'>启用</option>
					<option value='2'>禁用</option>
				</select>

				
				关键字
				<input name="info[keyword]" type="text" placeholder="请输入会员ID、昵称" class="input-text" />

				<input type="submit" name="search" class="button" value="<?php echo L('search')?>" />
	</div>
	共有玩家<?php echo $all_players;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 当前在线<?php echo $online_players;?>
		</td>
		</tr>
    </tbody>
</table>
</form>

<form name="myform" action="?m=member&c=member&a=delete" method="post" onsubmit="checkuid();return false;">
<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th  align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('userid[]');"></th>
			<th align="left"></th>
			<th align="left">ID</th>
			<th align="left">昵称</th>
			<th align="left">人民币金额</th>
			<th align="left">购买的砖石数</th>
			<th align="left">商户订单号</th>
			<th align="left">支付类型</th>
			<th align="left">支付状态</th>
			<th align="left">支付时间</th>
		</tr>
	</thead>
<tbody>
<?php
$i = 0;
	if(is_array($list)){
	foreach($list as $k=>$v) {
		$i++;
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['userid']?>" name="userid[]"></td>
		<td align="left"><?php if($v['islock']) {?><img title="<?php echo L('lock')?>" src="<?php echo IMG_PATH?>icon/icon_padlock.gif"><?php }?></td>
		<td align="left"><?php echo $i ?></td>
			<td align="left"><?php echo $v['nickname'] ?></td>
		<td align="left"><?php echo $v['product_price']?></td>
	
		<td align="left"><?php echo $v['product_price']*10;?></td>
		<td align="left"><?php echo $v['consume_number']?></td>
		<td align="left"><?php echo $v['pay']?></td>
		<td align="left"><?php if($v['pay_status'] == 2){echo '支付成功';}else{echo '支付失败';}?></td>
		<td align="left"><?php echo $v['deliver_time']?></td>

    </tr>
<?php
	}
}
?>
</tbody>
</table>


<div id="pages"><?php echo $pages?></div>
</div>
</form>
</div>
<script type="text/javascript">
<!--
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit').L('site')?>《'+name+'》',id:'edit',iframe:'?m=admin&c=site&a=edit&userid='+id,width:'700',height:'500'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function move() {
	var ids='';
	$("input[name='userid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:'<?php echo L('plsease_select').L('member')?>',lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	}
	window.top.art.dialog({id:'move'}).close();
	window.top.art.dialog({title:'<?php echo L('move').L('member')?>',id:'move',iframe:'?m=member&c=member&a=move&ids='+ids,width:'700',height:'500'}, function(){var d = window.top.art.dialog({id:'move'}).data.iframe;d.$('#dosubmit').click();return false;}, function(){window.top.art.dialog({id:'move'}).close()});
}

function checkuid() {
	var ids='';
	$("input[name='userid[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:'<?php echo L('plsease_select').L('member')?>',lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	} else {
		myform.submit();
	}
}

function member_infomation(userid, modelid, name) {
	window.top.art.dialog({id:'modelinfo'}).close();
	window.top.art.dialog({title:'<?php echo L('memberinfo')?>',id:'modelinfo',iframe:'?m=member&c=member&a=memberinfo&userid='+userid+'&modelid='+modelid,width:'700',height:'500'}, function(){var d = window.top.art.dialog({id:'modelinfo'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'modelinfo'}).close()});
}

//-->
</script>
</body>
</html>