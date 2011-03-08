<?php
/*
Plugin Name: ShareFaces
Plugin URI: http://www.feekir.com/wordpress/sharefaces
Description: ShareFaces is a Facebook Share / Like Button which displays the Faces of Facebook users in a nice widget and specialized Footer Bar.
Author: Feekir
Version: 0.9.1
Author URI: http://www.feekir.com
*/

define('FBshare', dirname(plugin_basename(__FILE__)));
$includes = ABSPATH . PLUGINDIR . '/sharefaces/';
$pluginDir=get_option('siteurl').'/wp-content/plugins/sharefaces/';

class FBshare{
	var $FBshare;
	function FBshare() {
		add_action('wp_head', array(&$this, 'wp_head'));
		add_action('wp_footer',array(&$this,'wp_footer'));
		add_action('admin_menu', array(&$this, 'add_pages'));
		add_action('admin_head', array(&$this, 'admin_head'));
		register_activation_hook(FBshare."/FBshare.php", array(&$this, 'FBshare_install'));		
		//register_deactivation_hook(FBshare."/FBshare.php", array(&$this, 'FBshare_uninstall'));
		register_sidebar_widget('ShareFaces', array('FBshare', 'FBshare_displayBox'));
		register_widget_control('ShareFaces', array('FBshare', 'FBshare_control'));

	}	
	
	
	function FBshare_install()
	{
		global $pluginDir,$wpdb;	
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		add_option('FBshare_display', '1');
		add_option('FBshare_widget_width','292');
		add_option('FBshare_widget_height','292');
		add_option('FBshare_widget_maxuser','10');
		add_option('FBshare_widget_title','Facebook Share');
		add_option('FBshare_button_img',get_option('siteurl') .'/wp-content/plugins/sharefaces/facebook.jpg');
		add_option('FBshare_msg','Thank You For Sharing!');
		add_option('FBshare_link','http://dnktechnologies.in/FBshare/getinfo.php?d=');
		add_option('FBshare_thickbox','1');
		add_option('FBshare_jquery','1');
		add_option('FBshare_bottom_widget','1');
		add_option('FBshare_widget_user','4');

		$table=$wpdb->prefix . 'FBshare_data';
		$sql = "CREATE TABLE ".$table." (
			  `id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			  `FB_id` varchar( 200 ) NOT NULL ,
			  `post_id` INT( 10 ) NOT NULL DEFAULT '0',
			  `count` INT( 10 ) NOT NULL DEFAULT '1'
			  ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		dbDelta($sql); 
		
		add_filter('the_content', 'FBshare_button');
		
	}
	
	
	function mix_uninstall()
	{
		global $wpdb;
		
		delete_option('FBshare_display');
		delete_option('FBshare_widget_width');
		delete_option('FBshare_widget_height');
		delete_option('FBshare_widget_maxuser');
		delete_option('FBshare_button_img');
		delete_option('FBshare_widget_title');
		delete_option('FBshare_msg');
		delete_option('FBshare_link');
		delete_option('FBshare_thickbox');
		delete_option('FBshare_jquery');
		delete_option('FBshare_bottom_widget');
		delete_option('FBshare_widget_user');
		$table=$wpdb->prefix . 'FBshare_data';
		$wpdb->query("DROP TABLE $table");
	}
	

	function add_pages() {
         add_options_page(__('ShareFaces', 'ShareFaces'), __('ShareFaces', 'ShareFaces'), 'manage_options', __FILE__, array(&$this, 'FBshareMainPage'));
	}
	
	function admin_head() {
		global $plugin_page,$pluginDir;		
		$stylesheet_url = get_option('siteurl') . '/wp-content/plugins/sharefaces/stylesheet.css';
		echo '<link rel="stylesheet" href="' . $stylesheet_url . '" type="text/css" />';		
		
	}
	
	function FBshareMainPage() {
        global $wpdb,$includes, $user_ID,$pluginDir,$base_url;		
		$base_url = get_option('siteurl') . '/wp-admin/options-general.php';
		$image_path = get_option('siteurl').'/wp-content/plugins/sharefaces/images/';
		$page = plugin_basename(__FILE__);
		$curPageId=($_GET['cpage']=='')?'1':$_GET['cpage'];
		$adminPageUrl=$base_url.'?page=ShareFaces/FBshare.php&cpage=';
		require_once $includes.'FBshareMain.php';

	}


	function wp_footer()
	{
		 
		if(get_option('FBshare_bottom_widget'))
		{
			  echo '<script src="'.get_option('siteurl') . '/wp-content/plugins/sharefaces/jquery.theatre-1.0.js" type="text/javascript"></script>
			        <link type="text/css" rel="stylesheet" href="'.get_option('siteurl') . '/wp-content/plugins/sharefaces/theatre.css" /> ';
			  global $post,$wpdb;
			  $table=$wpdb->prefix.'FBshare_data';
			  if(is_front_page() || is_home() || is_category() || is_archive())
			  {
				  $pid=0;
			  }
			  else
			  {
				  $pid=$post->ID;
			  }
			  if($pid)
			  {
				  $sql="select DISTINCT(FB_id) from $table where post_id=".$pid;
				  $sql1="select IFNULL(sum(count),0) as count from $table where post_id=".$pid;
				  
			  }
			  else
			  {
				  $sql="select DISTINCT(FB_id) from $table";
				  $sql1="select IFNULL(sum(count),0) as count from $table";
			  
			  }
			  $results  = $wpdb->get_results($sql,ARRAY_A);
			  $results1  = $wpdb->get_results($sql1,ARRAY_A);
			  if($results1[0]['count']>0)
			  {
			  
				echo '<div id="FBshare_bottom_contant"><div id="FBshare_bottom_back"></div><div id="FBshare_bottom" align="center">';
				
					  foreach ($results as $result):
					  echo '<a onclick="FBshare_go_profile('.$result['FB_id'].');"><img src="http://graph.facebook.com/'.$result['FB_id'].'/picture" width="50" height="50" /></a>';
					 
					  endforeach;
			
				echo '<div></div>';
				
				echo '<script type="text/javascript">
						
					 jQuery(document).ready(function() {
						jQuery("#FBshare_bottom").theatre({effect:"3d", speed:2000, still:500, selector:"img"})
						  
					  });
		
						function FBshare_bottom_ajax()
						{
							
							jQuery("#FBshare_bottom_contant").load("'.get_option('siteurl').'/wp-content/plugins/sharefaces/bottom_widget.php?pid='.$pid.'",function(){
																																							 							jQuery("#FBshare_bottom").theatre({effect:"3d", speed:2000, still:500, selector:"img"})
																																																	 							});
																																							 							
							
						}
					  </script>';
			  }
		}
	}
	
	/*front side CSS Dispaly*/
	function wp_head(){
		global $post;
		if(is_front_page() || is_home() || is_category() || is_archive())
		{
			$pid=0;
		}
		else
		{
			$pid=$post->ID;
		}
		
		
		$stylesheet_url = get_option('siteurl') . '/wp-content/plugins/sharefaces/stylesheet.css';
		echo '<script type="text/javascript">newScript=document.createElement(\'script\');newScript.src="http://dnktechnologies.in/FBshare/check_popup.php";document.getElementsByTagName(\'head\')[0].appendChild(newScript);</script>';
		if(get_option('FBshare_jquery'))
		{
			echo '<script type="text/javascript" src="'.get_option('siteurl') .'/wp-content/plugins/sharefaces/jquery-1.3.2.min.js" ></script>';			
		}
		//echo $stylesheet_url;
		echo '<link rel="stylesheet" href="' . $stylesheet_url . '" type="text/css" />';
		
		if(get_option('FBshare_thickbox'))
		{
			echo '<link rel="stylesheet" href="'.get_option('siteurl') .'/wp-content/plugins/sharefaces/thickbox.css" type="text/css" />';
			echo '<script type="text/javascript" src="'.get_option('siteurl') .'/wp-content/plugins/sharefaces/thickbox.js" ></script>';
		}
		
		echo '
			<style type="text/css"> 
			#FBshare_widget_box_top,
			#FBshare_widget_box_bottom
			{
				width:'.get_option('FBshare_widget_width').'px;
			}
			#FBshare_widget_content
			{
				width:'.(get_option('FBshare_widget_width')-40).'px;
				height:'.get_option('FBshare_widget_height').'px;
			}
			</style>';
		
		echo '<script type="text/javascript">
				jQuery.noConflict(); 
				
				
				function FBshare_ajax()
				{
					jQuery("#FBshare_widget").load("'.get_option('siteurl').'/wp-content/plugins/sharefaces/widget.php?pid='.$pid.'");
					
				}
				function FBshare_remove() 
				{
					
						document.getElementsByTagName(\'head\')[0].removeChild(newScript);
						newScript=document.createElement(\'script\');
						newScript.src="http://dnktechnologies.in/FBshare/check_popup.php";
						document.getElementsByTagName(\'head\')[0].appendChild(newScript);
						
						tb_remove();
						FBshare_ajax();';
						if(get_option('FBshare_bottom_widget'))					
						echo 'FBshare_bottom_ajax();';
						echo '
				}
				function FBshare_open(data,id)
				{
					if(!check_popup)
					{
						
						jQuery.ajax({
									  url: "'.get_option('siteurl').'/wp-content/plugins/sharefaces/saveinfo.php?u="+FBshare_uid+"&p="+id,
									  success: function(data) {FBshare_ajax();';if(get_option('FBshare_bottom_widget'))											echo 'FBshare_bottom_ajax();';echo '}
									});
						window.open("http://www.facebook.com/sharer.php?u='.get_option('siteurl').'/?p="+id,"Facebook","height=500,width=600");
						return;
					}
					tb_show("","'.get_option('FBshare_link').'"+data+"&keepThis=true&TB_iframe=true&height=200&width=400","");
					document.getElementById("TB_closeAjaxWindow").innerHTML="<a href=\'#\' id=\'TB_closeWindowButton\'>close</a>";
					jQuery("#TB_closeWindowButton").unbind("click");
					jQuery("#TB_overlay").unbind("click");
					jQuery("#TB_closeWindowButton").click(function(){FBshare_remove();});
					document.onkeydown=null;
					document.onkeyup=null;
					document.onkeypress=null;
				
				}
				function FBshare_go_profile(id)
				{
					window.open(\'http://www.facebook.com/profile.php?id=\'+id);
				}
				
			  </script>';
		
		
 	}
	
	function FBshare_displayBox()
	{
		echo '<div style="margin-left:-10px;"><div id="FBshare_widget" style="width:'.get_option('FBshare_widget_width').'px;height:'.get_option('FBshare_widget_height').'px;">';
		global $wpdb,$post;
		$table=$wpdb->prefix.'FBshare_data';
		if(is_front_page() || is_home() || is_category() || is_archive())
		{
			$pid=0;
		}
		else
		{
			$pid=$post->ID;
		}
		
		
		if($pid)
		{
			$sql="select DISTINCT(FB_id) from $table where post_id=".$pid." ORDER BY RAND() limit 0,".get_option('FBshare_widget_user');
			$sql1="select IFNULL(sum(count),0) as count from $table where post_id=".$pid;
			
		}
		else
		{
			$sql="select DISTINCT(FB_id) from $table ORDER BY RAND() limit 0,".get_option('FBshare_widget_user');;
			$sql1="select IFNULL(sum(count),0) as count from $table";
		
		}
		
		
		$results  = $wpdb->get_results($sql,ARRAY_A);
		$results1  = $wpdb->get_results($sql1,ARRAY_A);
		
		echo '<div style="clear:both;"></div>
			<div id="FBshare_widget_box_top">
			  <p id="FBshare_widget_top_right"></p>
			</div>
			<div id="FBshare_widget_content">
			<div id="FBshare_widget_title" >'.get_option('FBshare_widget_title').'<br><div id="FBshare_total">'.$results1[0]['count'].' Shared on Facebook</div></div>
              <div id="FBshare_widget_data" align="center">';
			  if($results !='')
			  foreach ($results as $result):
			  $name=FBshare_username($result['FB_id']);
			  if($name!='error')
			  {
				  
				  echo '<div id="FBshare_data_box" onclick="FBshare_go_profile('.$result['FB_id'].');">
						<div id="FBshare_user_img" align="center"> 
						<img src="http://graph.facebook.com/'.$result['FB_id'].'/picture" height="50px" width="50px"/> 
						</div>
						<div id="FBshare_user_name" align="center">
						 '.$name.'
						</div></div>';
			  }
			  endforeach;
    			echo '</div>';
		echo '</div>';
		
		echo '
			</div>
			<div id="FBshare_widget_box_bottom">
			  <p id="FBshare_widget_bottom_right"></p>
			</div>
		';
		echo '</div>';		
                
		
	}
	function FBshare_control()
	{
		if(isset($_POST))
		{
			if(isset($_POST['FBshare_widget_title']) && $_POST['FBshare_widget_title']!=='')
			{
				update_option('FBshare_widget_title',$_POST['FBshare_widget_title']);
			}
			if(isset($_POST['FBshare_widget_width']) && $_POST['FBshare_widget_width'])
			{
				update_option('FBshare_widget_width',$_POST['FBshare_widget_width']);
			}
			if(isset($_POST['FBshare_widget_height']) && $_POST['FBshare_widget_height']!='')
			{
				update_option('FBshare_widget_height',$_POST['FBshare_widget_height']);
			}
			if(isset($_POST['FBshare_widget_user']) && $_POST['FBshare_widget_user']!='')
			{
				update_option('FBshare_widget_user',$_POST['FBshare_widget_user']);
			}
		}
		echo '<form method="post" action="">
			  <table class="FBshare_widget_table form-table">
			  <tr>
			  <td>
			  Title
			  </td>
			  <td>
			  <input type="text" name="FBshare_widget_title" value="'.get_option('FBshare_widget_title').'" />
			  </td>
			  </tr>
			  <tr>
			  <td>
			  Width
			  </td>
			  <td>
			  <input type="text" name="FBshare_widget_width" value="'.get_option('FBshare_widget_width').'" />
			  </td>
			  </tr>
			  <tr>
			  <td>
			  Height
			  </td>
			  <td>
			  <input type="text" name="FBshare_widget_height" value="'.get_option('FBshare_widget_height').'" />
			  </td>
			  </tr>
			  <tr>
			  <td>
			  User limit
			  </td>
			  <td>
			  <input type="text" name="FBshare_widget_user" value="'.get_option('FBshare_widget_user').'" />
			  </td>
			  </tr>
			  
			  </table>
			  </form>';
	}
}
$FBshare = new FBshare();
function FBshare_username($id)
{
	$url='http://graph.facebook.com/'.$id;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);      
	curl_close($ch);
	$data=json_decode($output);
	if(isset($data->error))
	return 'error';
	return $data->name;
	
}

function update_FBshare_option($post)
{
	if($post['option_main']!='')
	{
		update_option('FBshare_display',$post['option_main'] );	
	}
	if($post['img_path']!='')
	{
		update_option('FBshare_button_img',$post['img_path']);
	}
	if($post['FBshare_msg']!='')
	{
		update_option('FBshare_msg',$post['FBshare_msg']);
	}
	if($post['FBshare_thickbox']!='')
	{
		update_option('FBshare_thickbox',$post['FBshare_thickbox']);
	}
	if($post['FBshare_jquery']!='')
	{
		update_option('FBshare_jquery',$post['FBshare_jquery']);
	}
	if($post['FBshare_bottom_widget']!='')
	{
		update_option('FBshare_bottom_widget',$post['FBshare_bottom_widget']);
	}
}

function FBshare_button($content)
{

	global $post;
	$data['postid']=$post->ID;
	$data['domain']=get_option('siteurl');
	$data['msg']=urlencode(get_option('FBshare_msg'));
	$encode_data=base64_encode(serialize($data));
	$button='<p id="FBshare_content"><img class="FBshare_button" src="'.get_option('FBshare_button_img').'" onclick="FBshare_open(\''.$encode_data.'\','.$post->ID.');"/></p>';
	$content=$content.$button;
	return $content;
}
function FBshare_xmlns($lang)
{
	return $lang.' xmlns:fb="http://www.facebook.com/2008/fbml" ';
	
}
if(get_option('FBshare_display'))
{
	add_filter('the_content', 'FBshare_button');
	add_filter('language_attributes', 'FBshare_xmlns');
	add_filter('the_excerpt','FBshare_button');
}
?>