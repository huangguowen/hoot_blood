<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
<form name="searchform" action="" method="get" >
<input type="hidden" value="pay" name="m">
<input type="hidden" value="payment" name="c">
<input type="hidden" value="details_data" name="a">
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
             <th width="10%">日期</th>
            <th width="10%">会员</th>
            <th width="10%">游戏推广码</th>
            <th width="10%">推广费用</th>
            <th width="10%">状态</th>
        
            </tr>
        </thead>
    <tbody>
 <?php 
 $c == 0;
if(is_array($infos)){
	$sum_amount = $sum_amount_succ = $sum_point_succ = $sum_point = '0';
	foreach($infos as $info){
		$c ++;
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
	<td width="10%" align="center"><?php echo $c?></td>
		<td width="10%" align="center"><?php echo $info[addtime]?></td>
	<td width="10%" align="center"><?php echo $info['phone']?></td>
	<td  width="10%" align="center"><?php echo $info['extern_id']?></td>
	<td width="10%" align="center"><?php echo $info['money']?></td>
	<td width="10%" align="center"><?php if($info[status] == 0){echo '未支付';}else{echo '已支付';}?></td>

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