<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=announce&c=admin_announce&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
         <th width="1">ID</th>
			<th width="1">标题</th>
			<th width="68" align="center">类型</th>
			<th width='68' align="center">发布时间</th>

			<th width="120" align="center">是否显示</th>
			<th width="69" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
 $id = 0;
if(is_array($data)){
	foreach($data as $announce){
		$id ++;
?>   
	<tr>
	<td align="center"><?php echo $id?></td>
	<td align="center"><?php echo $announce['title']?></td>
	<td align="center"><?php if($announce['type'] == 1){echo '活动公告';}else{echo '系统公告';}?></td>
	<td align="center"><?php echo $announce['addtime']?></td>
	<td align="center"><?php if($announce['status'] == 1){echo '是';}else{echo '否';}?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $announce['event_id']?>', '<?php echo safe_replace($announce['title'])?>');void(0);"><?php echo L('edit')?></a>
		<a href="javascript:deletes('<?php echo $announce['event_id']?>');void(0);"><?php echo L('delete')?></a>
	</td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
 </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_announce')?>--'+title, id:'edit', iframe:'?m=announce&c=admin_announce&a=edit&aid='+id ,width:'700px',height:'500px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function deletes(id, title) {

         $.ajax({
             type: "GET",
             url: "?m=announce&c=admin_announce&a=delete&pc_hash=<?php echo $_SESSION['pc_hash']; ?>"+'&id='+id,
             success: function(data){
             	if(data == 1){
             		alert('删除成功!')
             			window.location.reload(); 
                     }else{
                     	alert('删除失败!')
                     }
         }
         })
}
</script>