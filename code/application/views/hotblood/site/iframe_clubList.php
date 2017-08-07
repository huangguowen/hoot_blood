<?php 
	$this->load->model('User_model','usermodel');
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="热血德州">
    <meta name="keywords" content="热血德州">
	<meta name="applicable-device" content="pc">
	<meta http-equiv="Cache-Control" content="no-transform" />
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<meta name="format-detection" content="telephone=no">
    <title>热血德州</title>
	<link href="<?php echo base_url().'/static';?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url().'/static';?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo base_url().'/static';?>/css/uploadify.css">
	<link rel="stylesheet" href="<?php echo base_url().'/static';?>/css/main.css">
	</head>
	<body>
		<div class="f_userList">
			<div class="section">
				<div class="tt">俱乐部</div>
				<div class="item clearfix">
					<label>关键字：</label>
					<form action="<?php echo base_url('club/searchclub');?>" method='post'>
					<input class="keyw" type="text" placeholder="请输入会员ID、用户名或者昵称" name="keywords"/>
					<!-- <a class="search_btn" href="javascript:;" type="submit">搜索</a> -->
					<input type="submit" class="search_btn" value="搜索">
					</form>
				</div>
			</div>
			<div class="listBox">
				<div class="datatable">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th class="tool"><input type="checkbox" class="checkboxAll"/></th>
							<th>俱乐部ID</th>
							<th>俱乐部名称</th>
							<th>持有人</th>
							<th>头像</th>
							<th>人数情况</th>
							<th>桌数情况</th>
							<th>推荐设置</th>
						</tr>
					<!-- 	<tr>
							<td class="tool"><input type="checkbox" class="checkboxItem"/></td>
							<td>000001</td>
							<td>qq_1497408324888</td>
							<td>手机用户9947</td>
							<td><img src="images/tx.jpg" alt="" /></td>
							<td>10/60</td>
							<td>1/3</td>
						</tr> -->
						<?php foreach($data as $k=>$v){ ?>
						<tr>
							<td class="tool"><input type="checkbox" class="checkboxItem"/></td>
							<td><?php echo $v['mingpian']; ?></td>
							<td><?php echo $v['nickname']; ?></td>
							<td><?php echo $v['account']; ?></td>
							<td><img src="<?php echo $v['image']; ?>" alt="" /></td>
							<td><?php echo $v['player_curr']; ?>/<?php echo $v['player_num']; ?></td>
							<td><?php echo $this->usermodel->get_table_count_byid($v['id']); ?></td>
							<td class="operation">
								<a href="javascript:showLayerIframe('编辑','<?php echo $v['id']; ?>','600px','480px');">编辑</a></span>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>	
			</div>
									<?php
if ($pageInfo['count'] > 1) {
	if(!isset($_SESSION['clubwhere'])){
    $page_action = base_url() . "club/" . 'getclublistpage' . "/";
}else{
	   $page_action = base_url() . "club/" . 'searchpage' . "/";
}
    ?>
    <table class="table table-bordered">
        <tr height="40">

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['prep']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['start']); ?>">首页</a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['prep']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['prep']); ?>"><img
                            src="<?php echo base_url() . 'static/images/pre.gif'; ?>"/></a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['next']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['next']); ?>"><img
                            src="<?php echo base_url() . 'static/images/next.gif'; ?>"/></a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['end']) { ?>
                    <div class="tdinfo"><a href="<?php echo($page_action . $pageInfo['end']); ?>">尾页</a></div>
                <?php } ?>
            </td>

            <td>
                跳<select class="input-mini" name="<?php echo "ShipmentPageSelect"; ?>"
                         onchange="onGoPage(this.value);"><?php echo $pageList; ?></select>页
            </td>

        </tr>
    </table>
<?php } else {
    $page_action = "#"; ?>
    <table class="table table-bordered" width="800">
        <tr height="40">
            <td>
            </td>
            <td></td>
            <td></td>
        </tr>
    </table>
<?php } ?>
		</div>
		<script src="<?php echo base_url().'/static';?>/js/jquery.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/layer/layer.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/bootstrap-datetimepicker.min.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/datetimepicker.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/cropbox.js"></script>
		<script src="<?php echo base_url().'/static';?>/js/main.js"></script>		
	</body>
</html>
<script>

 function onGoPage(page) {
        var url = "<?php echo $page_action;?>" + page;
        location.href = url;
    }
    //layer弹出iframe
function showLayerIframe(_title, id, _areaX, _areaY) {
	_url = "http://localhost/code/club/recommend?id="+id
    window.top.layer.open({
        type: 2,
        title: _title,
        area: [_areaX, _areaY],
        maxmin: true,
        content: _url
    });
}
</script>
