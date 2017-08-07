<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="payment" name="c">
<input type="hidden" value="pay_list" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
<?php echo L('order_sn')?>  <input type="text" value="<?php echo $sn?>" class="input-text" name="info[sn]"> 
<?php echo L('username')?>  <input type="text" value="<?php echo $nickname?>" class="input-text" name="info[nickname]"> 
<?php echo L('addtime')?>  <?php echo form::date('info[start_addtime]',$start_addtime)?><?php echo L('to')?>   <?php echo form::date('info[end_addtime]',$end_addtime)?> 
<?php echo form::select($trade_status,$status,'name="info[status]"', L('all_status'))?>  
<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
</div>
</form>
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="10%">ID</th>
            <th width="20%">昵称</th>
            <th width="15%">人民币金额</th>
            <th width="9%">购买砖石数</th>
            <th width="8%">商户订单号</th>
            <th width="8%">支付类型</th>
            <th width="10%">订单状态</th>
            <th width="20%">支付时间</th>
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
	<td width="10%" align="center"><?php echo $info['id']?></td>
	<td width="20%" align="center"><?php echo $info['trade_sn']?></td>
	<td  width="15%" align="center"><?php echo date('Y-m-d H:i:s',$info['addtime'])?></td>
	<td width="9%" align="center"><?php echo L($info['pay_type'])?></td>
	<td width="8%" align="center"><?php echo $info['payment']?></td>
	<td width="8%" align="center"><?php echo $info['money']?> <?php echo ($info['type']==1) ? L('yuan') : L('dian')?></td>
	<td width="10%" align="center"><?php echo L($info['status'])?> </a>
	<td width="10%" align="center"><?php echo $info['trade_sn']?></a>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
<div class="btn text-r">
<?php echo L('thispage').L('totalize')?>  <span class="font-fixh green"><?php echo $number?></span> <?php echo L('bi').L('trade')?>(<?php echo L('money')?>：<span class="font-fixh"><?php echo $num_amount?></span><?php echo L('bi')?>，<?php echo L('point')?>：<span class="font-fixh"><?php echo $num_point?></span><?php echo L('bi')?>)，<?php echo L('total').L('amount')?>：<span class="font-fixh green"><?php echo $sum_amount?></span><?php echo L('yuan')?> ,<?php echo L('trade_succ').L('trade')?>：<span class="font-fixh green"><?php echo $sum_amount_succ?></span><?php echo L('yuan')?> ，总点数：<span class="font-fixh green"><?php echo $sum_point?></span><?php echo L('dian')?> ,<?php echo L('trade_succ').L('trade')?>：<span class="font-fixh green"><?php echo $sum_point_succ?></span><?php echo L('dian')?> 
</div>
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