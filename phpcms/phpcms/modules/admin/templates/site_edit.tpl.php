<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"<?php echo L("input").L('site_name')?>",onfocus:"<?php echo L("input").L('site_name')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('site_name')?>"}).ajaxValidator({type : "get",url : "",data :"m=admin&c=site&a=public_name&siteid=<?php echo $data['siteid']?>",datatype : "html",async:'true',success : function(data){	if( data == "1" ){return true;}else{return false;}},buttons: $("#dosubmit"),onerror : "<?php echo L('site_name').L('exists')?>",onwait : "<?php echo L('connecting')?>"}).defaultPassed();
		$("#dirname").formValidator({onshow:"<?php echo L("input").L('site_dirname')?>",onfocus:"<?php echo L("input").L('site_dirname')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('site_dirname')?>"}).regexValidator({regexp:"username",datatype:"enum",param:'i',onerror:"<?php echo L('site_dirname_err_msg')?>"}).ajaxValidator({type : "get",url : "",data :"m=admin&c=site&a=public_dirname&siteid=<?php echo $data['siteid']?>",datatype : "html",async:'false',success : function(data){	if( data == "1" ){return true;}else{return false;}},buttons: $("#dosubmit"),onerror : "<?php echo L('site_dirname').L('exists')?>",onwait : "<?php echo L('connecting')?>"}).defaultPassed();
		$("#domain").formValidator({onshow:"<?php echo L('site_domain_ex')?>",onfocus:"<?php echo L('site_domain_ex')?>",tipcss:{width:'300px'},empty:false}).inputValidator({onerror:"<?php echo L('site_domain_ex')?>"}).regexValidator({regexp:"http:\/\/(.+)\/$",onerror:"<?php echo L('site_domain_ex2')?>"});
		$("#template").formValidator({onshow:"<?php echo L('style_name_point')?>",onfocus:"<?php echo L('select_at_least_1')?>"}).inputValidator({min:1,onerror:"<?php echo L('select_at_least_1')?>"});
		$('#release_point').formValidator({onshow:"<?php echo L('publishing_sites_to_other_servers')?>",onfocus:"<?php echo L('choose_release_point')?>"}).inputValidator({max:4,onerror:"<?php echo L('most_choose_four')?>"});
		$('#default_style_input').formValidator({tipid:"default_style_msg",onshow:"<?php echo L('please_select_a_style_and_select_the_template')?>",onfocus:"<?php echo L('please_select_a_style_and_select_the_template')?>"}).inputValidator({min:1,onerror:"<?php echo L('please_choose_the_default_style')?>"});
	})
//-->
</script>
<style type="text/css">
.radio-label{ border-top:1px solid #e4e2e2; border-left:1px solid #e4e2e2}
.radio-label td{ border-right:1px solid #e4e2e2; border-bottom:1px solid #e4e2e2;background:#f6f9fd}
</style>
<div class="pad-10">
<form action="?m=admin&c=site&a=edit_form&siteid=<?php echo $siteid?>" method="post" id="myform">
<div class="bk10" align="center">ID:<?php echo $res['id'] ?>   别名:<?php echo $res['nickname']; ?></div>
<fieldset>
	<legend>选项：</legend>
	<table width="100%"  class="table_form">
  <tr>
    <th width="80">选项：</th>
    <td class="y-bg">
        <select name="fun" id="">
  <option value="1">增加</option>
  <!-- <option value="0">扣除</option> -->
        </select>
    </td>
  </tr>
</table>
  <table width="100%"  class="table_form">
  <tr>
    <th width="80">类型：</th>
    <td class="y-bg">
        <select name="type" id="">
  <!-- <option value="1">房卡</option> -->
  <option value="1">砖石</option>
  <!-- <option value="0">金币</option> -->
        </select>
    </td>
  </tr>
</table>
<input type="hidden" name="id" value="<?php echo $res['id'];?>">
  <table width="100%"  class="table_form">
  <tr>
    <th width="80">数量：</th>
    <td class="y-bg">
    <select name="num" id="">
  <option value='60'>60砖石</option>
                            <option value='300'>300砖石</option>
                            <option value='1280'>1280砖石</option>
                            <option value='3280'>3280砖石</option>
                            <option value='6180'>6180砖石</option>
                            </select>
    </td>
  </tr>
</table>
  <table width="100%"  class="table_form">
  <tr>
    <th width="80">说明：</th>
    <td class="y-bg">
 <textarea name="comment" id="" cols="30" rows="10"></textarea>
    </td>
  </tr>
</table>
</fieldset>
<script type="text/javascript">
function default_list() {
	var html = '';
	var old = $('#default_style_input').val();
	var checked = '';
	$('#template option:selected').each(function(i,n){
		if (old == $(n).val()) {
			checked = 'checked';
		}
		 html += '<label><input type="radio" name="default_style_radio" value="'+$(n).val()+'" onclick="$(\'#default_style_input\').val(this.value);" '+checked+'> '+$(n).text()+'</label>';
	});
	if(!checked)  $('#default_style_input').val('0');
	$('#default_style').html(html);
}
</script>
</fieldset>
<div class="bk15"></div>
    <input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit')?>" />
</div>
</form>
</div>
</body>
</html>