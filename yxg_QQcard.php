<?php  
/*
Plugin Name: QQ显示网站卡片信息
Plugin URI: https://www.520yxl.cn/
Description: 自定义QQ显示网站卡片信息，使得QQ发送网站网址显示你想要的卡片信息。
Version: 1.0.0
Author: 云轩阁
Author URI: https://www.520yxl.cn/
License: GPL
*/
/* 注册激活插件时要调用的函数 */ 
register_activation_hook( __FILE__, 'yxg_QQcard_install');   

/* 注册停用插件时要调用的函数 */ 
register_deactivation_hook( __FILE__, 'yxg_QQcard_remove' );  

function yxg_QQcard_install() {  
    /* 在数据库的 wp_options 表中添加一条记录，第二个参数为默认值 */ 
    add_option("yxg_QQcard_title_text", get_bloginfo('name'), '', 'yes');
    add_option("yxg_QQcard_image_text", plugins_url('wordpress.png',__FILE__), '', 'yes');
    add_option("yxg_QQcard_description_text", get_bloginfo('description'), '', 'yes');
}

function yxg_QQcard_remove() {  
    /* 删除 wp_options 表中的对应记录 */ 
    delete_option('yxg_QQcard_title_text'); 
    delete_option('yxg_QQcard_image_text'); 
    delete_option('yxg_QQcard_description_text');  
}

if( is_admin() ) {
    /*  利用 admin_menu 钩子，添加菜单 */
    add_action('admin_menu', 'yxg_QQcard_menu');
}

function yxg_QQcard_menu() {
    /* add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);  */
    /* 页名称，菜单名称，访问级别，菜单别名，点击该菜单时的回调函数（用以显示设置页面） */
    add_options_page('QQ显示网站卡片信息设置页面', 'QQ显示网站卡片信息', 'administrator','yxg_QQcard', 'yxg_QQcard_html_page');
}
function plugin_add_settings_link ( $links ) {
     $settings_link = '<a href="options-general.php?page=yxg_QQcard">' . __ ( 'Settings' ) . '</a>' ;
     array_push ( $links , $settings_link ) ;
         return $links ;
}
$plugin = plugin_basename ( __FILE__ ) ;
add_filter ( "plugin_action_links_$plugin" , 'plugin_add_settings_link' ) ;

function yxg_QQcard_wp_head() {
        echo '<!-- QQCard BEGIN -->';
        echo '<meta itemprop="name" content="'.get_option('yxg_QQcard_title_text'),'">';
        echo '<meta itemprop="image" content="'.get_option('yxg_QQcard_image_text'),'">';
        echo '<meta name="description" itemprop="description" content="'.get_option('yxg_QQcard_description_text'),'">';
        echo '<!-- QQCard END -->';
}
add_action('wp_head', 'yxg_QQcard_wp_head');
function yxg_QQcard_html_page() {
	wp_enqueue_style('layui_css', '//www.layuicdn.com/layui/css/layui.css');
	
	//当提交了，并且验证信息正确
	if( !empty( $_POST ) && check_admin_referer( 'yxg_QQcard_nonce' )) {
		
		//更新设置
		update_option( 'yxg_QQcard_title_text', $_POST['yxg_QQcard_title_text'] );
		update_option( 'yxg_QQcard_image_text', $_POST['yxg_QQcard_image_text'] );
		update_option( 'yxg_QQcard_description_text', $_POST['yxg_QQcard_description_text'] );?>
		<div id="message" class="updated">
			<p><strong>保存成功！</strong></p>
		</div>
	<?php }?><?php wp_enqueue_media();?>
<script>   
jQuery(document).ready(function(){   
	var yxg_QQcard_image_text_frame;   
	var value_id;   
	jQuery('.layui-btnyxg').on('click',function(e){   
		value_id =jQuery( this ).attr('id');       
		event.preventDefault();   
		if( yxg_QQcard_image_text_frame ){   
			yxg_QQcard_image_text_frame.open();   
			return;   
		}   
		yxg_QQcard_image_text_frame = wp.media({   
			title: '插入图片',   
			button: {   
				text: '插入',   
			},   
			multiple: false   
		});   
		yxg_QQcard_image_text_frame.on('select',function(){  //里面是选择图片后的动作，把图片地址赋值给input 
			attachment = yxg_QQcard_image_text_frame.state().get('selection').first().toJSON();   
			jQuery('input[name='+value_id+']').val(attachment.url);   
		});	   
		yxg_QQcard_image_text_frame.open();   
	});   
});   
</script>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>QQ显示网站卡片信息设置页面</legend>
  </fieldset>
  <div class="layui-row">
    <div class="layui-col-xs6">
      <div class="grid-demo grid-demo-bg1">
      	<form class="layui-form" action="" method="post">
  <div class="layui-form-item">
    <label class="layui-form-label">网站名称</label>
    <div class="layui-input-block">
      <input type="text" value="<?php echo esc_attr( get_option('yxg_QQcard_title_text') ); ?>"name="yxg_QQcard_title_text" lay-verify="title" autocomplete="off" placeholder="请输入网站名称" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item layui-inline">
    <label class="layui-form-label">LOGO图标</label>
    <div class="layui-input-block">
<!--       <input type="text" name="yxg_QQcard_image_text" lay-verify="title" autocomplete="off" placeholder="建议像素200*200px" class="layui-input"> -->
      <input type="text" size="85" value="<?php echo esc_attr( get_option('yxg_QQcard_image_text') ); ?>" name="yxg_QQcard_image_text" lay-verify="title" autocomplete="off" placeholder="建议像素200*200px" class="layui-input" style="vertical-align:middle;" id="yxg_QQcard_image_text"/>
<!-- <a id="yxg_QQcard_image_text" class="upload_button button" href="#">上传</a> -->
  
    </div>

  </div>
  <div class="layui-inline" >
  	<button type="button" class="layui-btn layui-btnyxg" id="yxg_QQcard_image_text">上传图片</button>
  <!-- <button  type="button"  class="layui-btn" id="yxg_QQcard_image_text">上传图片</button> -->
   </div> 
  <div class="layui-form-item">
    <label class="layui-form-label">网站副标题</label>
    <div class="layui-input-block">
      <input type="text" value="<?php echo esc_attr( get_option('yxg_QQcard_description_text') ); ?>" name="yxg_QQcard_description_text" lay-verify="title" autocomplete="off" placeholder="建议不要超过20个汉字" class="layui-input">
    </div>
</div>

     <div>
  <br>	<input type="submit" class="layui-btn" name="submit" value="保存设置" />
  </div>
  <?
				//输出一个验证信息
				wp_nonce_field('yxg_QQcard_nonce');
			?>
      <br>
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">使用说明</div>
        <div class="layui-card-body">
          设置好网站信息后需要打开浏览器的无痕窗口进行访问下面链接：<br><br>
          https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshareget_urlinfo?url=<?php echo home_url(); ?><br><br>
          访问过后不一定能及时生效，可能要等一段时间。<br><br>
          成功后多支持一下作者，可以帮作者推广一下，也可以赞助一下作者<br><br>
          <img src="https://imagecdn.520yxl.cn/wp-content/uploads/2020/09/yxg_zz.png" /><br><br>
          Copyright ©  <a href="https://www.520yxl.cn">云轩阁</a>  版权所有.
        </div>
      </div>
    </div>
    </form>
      </div>
    </div>

<?php
// <!-- QQCard BEGIN -->
// <meta itemprop="name" content="标题">
// <meta itemprop="image" content="图片">
// <meta name="description" itemprop="description" content="描述">
// <!-- QQCard END -->
}
?>