<?php
/*
Plugin Name: card
Plugin URI: https://www.520yxl.cn/
Description: 卡片分享信息自动生成
Version: 1.1.0
Author: 云轩阁
Author URI: https://www.520yxl.cn/
License: GPL
*/
register_activation_hook( __FILE__, 'card_install');   

register_deactivation_hook( __FILE__, 'card_remove' );  

function card_install() {  
    add_option("card_title_text", get_bloginfo('name'), '', 'yes');
    add_option("card_image_text", plugins_url('wordpress.png',__FILE__), '', 'yes');
    add_option("card_description_text", get_bloginfo('description'), '', 'yes');
}

function card_remove() {  
    delete_option('card_title_text'); 
    delete_option('card_image_text'); 
    delete_option('card_description_text');  
}

if( is_admin() ) {
    add_action('admin_menu', 'card_menu');
}

function card_menu() {

    add_options_page('卡片分享信息设置页面', '网站卡片分享信息', 'administrator','card', 'card_html_page');
}
function plugin_add_settings_link ( $links ) {
     $settings_link = '<a href="options-general.php?page=card">' . __ ( 'Settings' ) . '</a>' ;
     array_push ( $links , $settings_link ) ;
         return $links ;
}
$plugin = plugin_basename ( __FILE__ ) ;
add_filter ( "plugin_action_links_$plugin" , 'plugin_add_settings_link' ) ;




function card_wp_head() {
        if(!is_home()){
        echo '<meta itemprop="name" content="'.get_the_title($post->ID),'">';
        echo '<meta itemprop="description" content="'.wp_trim_words( get_the_excerpt($post->ID), 35 ),'">';
            $card_imgurl=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array(200,200))[0];
            if($card_imgurl){
                echo '<meta itemprop="image" content="'.$card_imgurl,'">';
            }else{
                echo '<meta itemprop="image" content="'.get_option('card_image_text'),'">';
            }
        
        }else{
        echo '<!-- QQCard BEGIN -->
        <meta itemprop="name" content="'.get_option('card_title_text'),'">
        <meta itemprop="image" content="'.get_option('card_image_text'),'">
        <meta itemprop="description" content="'.get_option('card_description_text'),'">
        <!-- QQCard END -->';    
    
}}
add_action('wp_head', 'card_wp_head');
function card_html_page() {
	wp_enqueue_style('layui_css', '//www.layuicdn.com/layui/css/layui.css');
	if( !empty( $_POST ) && check_admin_referer( 'card_nonce' )) {
		
		update_option( 'card_title_text', $_POST['card_title_text'] );
		update_option( 'card_image_text', $_POST['card_image_text'] );
		update_option( 'card_description_text', $_POST['card_description_text'] );?>
		<div id="message" class="updated">
			<p><strong>保存成功！</strong></p>
		</div>
	<?php }?><?php wp_enqueue_media();?>
<script>   
jQuery(document).ready(function(){   
	var card_image_text_frame;   
	var value_id;   
	jQuery('.layui-btnyxg').on('click',function(e){   
		value_id =jQuery( this ).attr('id');       
		event.preventDefault();   
		if( card_image_text_frame ){   
			card_image_text_frame.open();   
			return;   
		}   
		card_image_text_frame = wp.media({   
			title: '插入图片',   
			button: {   
				text: '插入',   
			},   
			multiple: false   
		});   
		card_image_text_frame.on('select',function(){ 
			attachment = card_image_text_frame.state().get('selection').first().toJSON();   
			jQuery('input[name='+value_id+']').val(attachment.url);   
		});	   
		card_image_text_frame.open();   
	});   
});   
</script>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>网站卡片分享信息设置页面</legend>
  </fieldset>
  <div class="layui-row">
    <form class="layui-form" action="" method="post">
    <div class="layui-col-xs6">
      <div class="grid-demo grid-demo-bg1">
  <div class="layui-form-item" >
    <label class="layui-form-label">网站名称</label>
    <div class="layui-input-block">
      <input type="text" value="<?php echo esc_attr( get_option('card_title_text') ); ?>"name="card_title_text" lay-verify="title" autocomplete="off" placeholder="请输入网站名称" class="layui-input" style="max-width:95%">
    </div>
  </div>
  <div class="layui-form-item layui-inline">
    <label class="layui-form-label">LOGO图标</label>
    <div class="layui-input-block">
      <input type="text" size="81" value="<?php echo esc_attr( get_option('card_image_text') ); ?>" name="card_image_text" lay-verify="title" autocomplete="off" placeholder="建议像素200*200px" class="layui-input" style="vertical-align:middle;" id="card_image_text"/>
  
    </div>
  <div class="layui-inline" style="position:absolute;top:0px;right:-15%">
  	<button type="button" class="layui-btn layui-btnyxg" id="card_image_text">上传图片</button>
   </div>
  </div>
 
  <div class="layui-form-item">
    <label class="layui-form-label">网站副标题</label>
    <div class="layui-input-block">
      <input type="text" value="<?php echo esc_attr( get_option('card_description_text') ); ?>" name="card_description_text" lay-verify="title" autocomplete="off" placeholder="建议不要超过20个汉字" class="layui-input" style="max-width:95%">
    </div>
</div>

     <div style="position:absolute;right:4%;">
  <br>	<input type="submit" class="layui-btn" name="submit" value="保存设置" />
  </div>
  <?
				wp_nonce_field('card_nonce');
			?>
      <br>

    
      </div>
    </div>
    <div class="layui-col-xs6" >
      <div class="grid-demo">
            <div class="layui-col-md12" style="max-width:95%">
      <div class="layui-card">
        <div class="layui-card-header">使用说明</div>
        <div class="layui-card-body">
          设置好网站信息后需要打开浏览器的无痕窗口进行访问下面链接：<br><br>
          https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshareget_urlinfo?url=<?php echo home_url(); ?><br><br>
          访问过后不一定能及时生效，可能要等一段时间。<br><br>
          可以配合作者写的软件进行网站的sitemap所有链接提交<br><br>
          <a href="https://www.520yxl.cn/post-861.html">https://www.520yxl.cn/post-861.html</a><br><br>
          成功后多支持一下作者，可以帮作者推广一下，也可以赞助一下作者<br><br>
          <img src="https://imagecdn.520yxl.cn/wp-content/uploads/2020/09/yxg_zz.png" />
        </div>
      </div>
    </div>
        </div>
    </div>
    
    </form>
    <div style="position:absolute;left:50%;top:100%">Copyright ©  <a href="https://www.520yxl.cn">云轩阁</a>  版权所有.</div>
    </div>
<?php
}
?>
