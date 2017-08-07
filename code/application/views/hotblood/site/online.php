<html>

	<table>
		<tr>
			<td>姓名</td>
			<td>得分</td>
		</tr>
			<?php 
				foreach($data as $v){
			 ?>
<tr>
			 <td><?php echo $v['nickname']; ?></td>
			  <td><?php echo $v['sum_score']; ?></td>
			  </tr>
			  <?php 
			}
			   ?>
		

		<?php
if ($pageInfo['count'] > 1) {
    $page_action = base_url() . "user/" . 'onlinelist' . "/";
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
                            src="<?php echo base_url() . 'static/image/pre.gif'; ?>"/></a>
                <?php } ?>
            </td>

            <td>
                <?php if ($pageInfo['now'] != $pageInfo['next']) { ?>
                    <a href="<?php echo($page_action . $pageInfo['next']); ?>"><img
                            src="<?php echo base_url() . 'static/image/next.gif'; ?>"/></a>
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

	</table>
</html>

<script>

 function onGoPage(page) {
        var url = "<?php echo $page_action;?>" + page;
        location.href = url;
    }
</script>