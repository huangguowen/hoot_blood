<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=announce&c=admin_announce&a=edit" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="80"><strong><?php echo L('announce_title')?></strong></th>
		<td><input name="announce[title]" id="title" class="input-text" type="text" size="50" value="<?php echo $data['title']; ?>"></td>
	</tr>
			<tr>
		<th><strong>公告类型（系统公告 || 活动公告）</strong></th>
		<td><select name="announce[type]"><option value="1" <?php if($data['status'] == 1){echo "selected='selected'";} ?>>活动公告</option><option value="0" <?php if($data['status'] == 0){echo "selected='selected'";} ?>>系统公告</option></select></td>
	</tr>
	<input type="hidden" name="announce[event_id]" value=<?php echo $announce['event_id'];?>>
	<tr>
		<th><strong><?php echo L('announce_content')?></strong></th>
		<td><textarea name="announce[recommended]" id="content"><?php echo $data['recommended']; ?></textarea><?php echo form::editor('content');?></td>
	</tr>
	<tr>
		<th><strong>活动位置</strong></th>
		<td><input name="announce[order]" id="title" class="input-text" type="text" size="50" value="<?php echo $data['order']; ?>"></td>
	</tr>
	<tr>
		<th><strong>活动url</strong></th>
		<td><input name="announce[event_url]" id="title" class="input-text" type="text" size="50" value="<?php echo $data['event_url']; ?>"></td>
	</tr>
		<tr>
		<th><strong>活动图片加载地址</strong></th>
		<td><input name="announce[img_id]" id="title" class="input-text" type="text" size="50" value="<?php echo $data['img_id']; ?>"></td>
	</tr>


		<tr>
		<th><strong>是否显示</strong></th>
		<td><select name="announce[status]"><option value="1" <?php if($data['status'] == 1){echo "selected='selected'";} ?>>是</option><option value="0" <?php if($data['status'] == 0){echo "selected='selected'";} ?>>否</option></select></td>
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