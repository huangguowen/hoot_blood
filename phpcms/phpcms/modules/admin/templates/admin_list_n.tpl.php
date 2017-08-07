<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<div class="pad_10">
<div class="table-list">
<form name="myform" action="?m=admin&c=role&a=listorder" method="post">
    <table width="100%" cellspacing="0">
        <thead>
		<tr>
		<th width="10%"><?php echo L('userid')?></th>
		<th width="5%" align="left" >玩家ID</th>
		<th width="15%" >申请时间</th>
		<th></th>
		</tr>
        </thead>
        <tbody>
<?php $admin_founders = explode(',',pc_base::load_config('system','admin_founders'));?>
<?php 
$i = 0;
if(is_array($infos)){
	foreach($infos as $info){
		$i++;
?>
<tr>
<td width="10%" align="center"><?php echo $i;?></td>
<td width="5%" ><?php echo $info['player_extern_id']?></td>
<td width="10%"  align="center"><?php echo $info['spread_time']?></td>
<td width="15%"  align="center">
<a href="javascript:add(1)"><?php echo L('edit')?></a> 
</td>
</tr>
<?php 
	}
}
?>
</tbody>
</table>
 <div id="pages"> <?php echo $pages?></div>
</form>
</div>
</div>
</body>
</html>
<script type="text/javascript">
<!--
	function add(id) {
		window.top.art.dialog({title:'<?php echo L('add')?>--', id:'add', iframe:'?m=admin&c=admin_manage&a=add&userid='+id ,width:'500px',height:'400px'}, 	function(){var d = window.top.art.dialog({id:'add'}).data.iframe;
		var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'add'}).close()});
	}


//-->
</script>