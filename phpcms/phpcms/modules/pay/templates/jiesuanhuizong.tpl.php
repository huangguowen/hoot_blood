<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="payment" name="c">
<input type="hidden" value="summary_data" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">

	支付状态		
				<select name="info[status]">
					<option value='-1'>全部</option>
					<option value='1'>已支付</option>
					<option value='0'>未支付</option>
				</select>

会员名称 <input type="text" value="<?php echo $nickname?>" class="input-text" name="info[nickname]"> 
时间  <?php echo form::date('info[start_addtime]',$start_addtime)?><?php echo L('to')?>   <?php echo form::date('info[end_addtime]',$end_addtime)?> 
<?php echo form::select($trade_status,$status,'name="info[status]"', L('all_status'))?>  
<input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
</div>
</form>
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="10%">ID</th>
             <th width="10%">状态</th>
            <th width="10%">会员</th>
            <th width="10%">结算规则</th>
            <th width="10%">推广费用</th>
            <th width="10%">银行</th>
            <th width="10%">账号</th>
            <th width="10%">收款人</th>
             <th width="10%">说明</th>
              <th width="5%">确认</th>
               <th width="5%">时间</th>
            </tr>
        </thead>
    <tbody>
 <?php 
 $idss = 0;
 $amount = 0;
 $yizhifu = 0;
 $weizhifu = 0;
if(is_array($infos)){
	$sum_amount = $sum_amount_succ = $sum_point_succ = $sum_point = '0';
	foreach($infos as $info){
			$idss ++;
			$amount += $info['total'];
?>   
	<tr>
	<td width="10%" align="center"><?php echo $idss?></td>
		<td width="10%" align="center"><?php if($info['ispay'] == 0){echo '未支付';}else{echo '已支付';} ?></td>
	<td width="10%" align="center"><?php echo $info['wechat']?></td>
	<td  width="10%" align="center">周结</td>
	<td width="10%" align="center"><?php echo $info['total']?></td>
	<td width="10%" align="center"><?php echo $info['bank_name']?></td>
	<td width="10%" align="center"><?php echo $info['bank_code']?> </a>
	<td width="10%" align="center"><?php echo $info['realname']?></a>
	<?php if($info['conment'] == '' and $info['status'] == 0){ ?>
<td width="10%" align="center"><input type="text" id="conment_<?php echo $idss;?>"></td>
	<?php }else{ ?>
	<td width="10%" align="center"><?php echo $info['conment']?></a>
	<?php } ?>
	<?php if($info['status'] == 0){ ?>
	<?php $weizhifu += $info['total'];?>
	<td width="5%" align="center" onclick="change(<?php echo $info['play_id']?>,<?php echo $stime;?>,<?php echo $etime;?>,<?php echo $idss;?>)" style="cursor:pointer;">支付</td>
	<?php }else{ ?>
	<?php $yizhifu += $info['total'];?>
<td width="5%" align="center">已支付</a>
	<?php } ?>
	<?php if($info['status'] == 0){ ?>
	<td width="5%" align="center"></a>
<?php }else{ ?>
<td width="5%" align="center"><?php echo $info['paytime'] ?></a>
<?php } ?>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
<div class="btn text-r">
<?php echo L('thispage').L('totalize')?>  <span class="font-fixh green"><?php echo $idss?></span> <?php echo L('bi').L('trade')?>(<?php echo L('money')?>：<span class="font-fixh"><?php echo $amount?></span>元,已经支付：<span class="font-fixh green"><?php echo $yizhifu?></span><?php echo L('yuan')?>,未支付：<span class="font-fixh green"><?php echo $weizhifu?></span><?php echo L('yuan')?>)
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

function change(play_id,stime,etime,idss){
	if (confirm("您确定支付吗？")){
	text = $('#conment_'+idss).val();
         $.ajax({
             type: "GET",
             url: "?m=pay&c=payment&a=change_pay_s&pc_hash="+pc_hash+'&play_id='+play_id+'&stime='+stime+'&etime='+etime+'&conment='+text,
             success: function(data){
             	if(data == 1){
             		window.location.reload(); 
                     }else{
                     alert('error')
                     }
         }
         })
}
}
//-->
</script>