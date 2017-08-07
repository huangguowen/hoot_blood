$(function () {
	var h=$(window).height();
	var w=$(window).width();
	//iframe高度
	$('#main_frame').css({'height':h-85});
	$(".loginCnt").css({'height':h,'width':w});
	//监听窗口变化
	$(window).on('resize', function () {
        var h=$(window).height();
		var w=$(window).width();
		//iframe高度
		$('#main_frame').css({'height':h-85});
		$(".loginCnt").css({'height':h,'width':w});
    });
	//左侧导航
	$('.nav li').click(function(){
		$(this).addClass('current').siblings().removeClass('current');
		
		var site = $(this).find('a').text();
		if(!$(this).find(".sub").length){
			$('.site').find('span').text(site);
		}
		
		if($(this).find('i').length>0){
			$(this).find('i').toggleClass('open').parent().siblings().find('i').removeClass('open');
		}else{
			$(this).siblings().find('i').removeClass('open');
		}
		
		if($(this).find('a').hasClass('sub')){
			$(this).next().stop().slideToggle();			
			$(this).siblings('li').next('.sub_nav').slideUp();			
		}else{
			$(this).siblings('li').next('.sub_nav').slideUp();
		}
		
	})	
	$('.sub_nav a').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		if($(this).parent().length>0){
			$(this).parent().siblings('.sub_nav').find('a').removeClass('cur');
		}
		$('.site').find('span').text($('.nav .current .sub').text());
	})
	//取消图片
	$(document).on("click",".resetPic_btn",function(){
		$("#avatar_img").attr("src",$("#avatar_img").attr("default-src"));
	});
	
	//上传头像
	if($("#upload-file").length){
    	var options =
	        {
	            imageBox: '.imageBox',
	            imageBox_s: '.smallpicBox',
	            thumbBox: '.thumbBox',
	            spinner: '.spinner',
	            imgSrc: 'images/default-icon.png'
	        }
        var cropper;
        document.querySelector('#upload-file').addEventListener('change', function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = new cropbox(options);
            	$('#avatar_preview1').addClass('hide');
                $('#avatar_preview2').addClass('hide');
            }
            reader.readAsDataURL(this.files[0]);
           // this.files = [];
        })
        /*document.querySelector('#btnCrop').addEventListener('click', function(){
            var img = cropper.getDataURL()；
            document.querySelector('.cropped').innerHTML += '<img src="'+img+'">';
        })*/
    }
    //设置头像确定和取消
     $(document).on("click", ".picSettingCntBottom a", function () {
        if ($(this).hasClass("sure_btn")) {
            //确定操作
            if($("#avatar_preview1").hasClass("hide")){
                var img = cropper.getDataURL()
                $('#avatar_preview1').attr('src',img);
                $('#avatar_preview2').attr('src',img);
                $('#avatar_now').val(img);
                $(window.top.frames["main_frame"]).contents().find("#avatar_img").attr('src', img);
            }else{
                $('#avatar_now').val($('#avatar_preview1').attr('src'));
                $('#avatar_up').val($('#avatar_preview1').attr('src'));
                $('#avatar_up').val(img);
                $(window.top.frames["main_frame"]).contents().find("#avatar_img").attr('src', $('#avatar_preview1').attr('src'));
            }
            //todo：ajax返回后调用以下代码
            parent.layer.close(parent.layer.getFrameIndex(window.name));
        } else {
            //取消操作
            parent.layer.close(parent.layer.getFrameIndex(window.name));
        }
    });
    //设置头像选择默认头像操作
    $(document).on("click", ".picSettingCnt .choseAvatar", function () {
        $(".picSettingCntRight .bigpic,.picSettingCntRight .smallpic").attr("src", $(this).find("img").attr("src"));
        $("#avatar_now").val($(this).find("img").attr("src"));
    });
    //表单全选
    $(document).on("click",".checkboxAll",function(){
    	if($(this).hasClass("checked")){
    		$(this).removeClass("checked");
    		$(".datatable .checkboxItem").prop("checked",false);  
	    }else{
	    	$(this).addClass("checked");
			$(".datatable .checkboxItem").prop("checked",true);
	    } 
    });
})	
//重新上传,清空裁剪参数
function clear_upload(){
    $('#preview-hidden').find('*').remove();
    $('#preview-hidden').hide();
    $('.reupload_btn').css('bottom','-6px');
    $('.crop').children('img').attr('style','');
}
//layer弹出iframe
function showLayerIframe(_title, _url, _areaX, _areaY) {
    window.top.layer.open({
        type: 2,
        title: _title,
        area: [_areaX, _areaY],
        maxmin: true,
        content: _url
    });
}
	


