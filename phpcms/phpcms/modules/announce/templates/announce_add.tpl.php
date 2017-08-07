<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=announce&c=admin_announce&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('announce_title')?></strong></th>
		<td><input name="announce[title]" id="title" class="input-text" type="text" size="50" ></td>
	</tr>
		</tr>
		<tr>
		<th><strong>公告类型（系统公告 || 活动公告）</strong></th>
		<td><select name="announce[type]"><option value="1">活动公告</option><option value="0">系统公告</option></select></td>
	</tr>

	<tr>
		<th><strong><?php echo L('announce_content')?></strong></th>
		<td><textarea name="announce[recommended]" id="content"></textarea><?php echo form::editor('content');?></td>
	</tr>
	<tr>
		<th><strong>活动位置</strong></th>
		<td><input name="announce[order]" id="title" class="input-text" type="text" size="50" ></td>
	</tr>
	<tr>
		<th><strong>活动url</strong></th>
		<td><input name="announce[event_url]" id="title" class="input-text" type="text" size="50" ></td>
	</tr>
		<tr>
		<th><strong>活动图片加载地址</strong></th>
		<td><input name="announce[img_id]" id="title" class="input-text" type="text" size="50" ></td>

		<tr>
		<th><strong>是否显示</strong></th>
		<td><select name="announce[status]"><option value="1">是</option><option value="0">否</option></select></td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
function load_file_list(id) {
	if (id=='') return false;
	$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=announce&templates=show&name=announce&pc_hash='+pc_hash, function(data){$('#show_template').html(data.show_template);});
}


</script>