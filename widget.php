<?php
require('../../../wp-blog-header.php');
function FBshare_username_ajax($id)
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
		global $wpdb;
		$pid=$_GET['pid'];
		$table=$wpdb->prefix.'FBshare_data';
		
		if($pid)
		{
			$sql="select DISTINCT(FB_id) from $table where post_id=".$pid." ORDER BY RAND() limit 0,".get_option('FBshare_widget_user');;
			$sql1="select IFNULL(sum(count),0) as count from $table where post_id=".$pid;
			
		}
		else
		{
			$sql="select DISTINCT(FB_id) from $table ORDER BY RAND() limit 0,".get_option('FBshare_widget_user');;
			$sql1="select IFNULL(sum(count),0) as count from $table";
		
		}
		
		
		$results  = $wpdb->get_results($sql,ARRAY_A);
		$results1  = $wpdb->get_results($sql1,ARRAY_A);
		
		echo '
			<div id="FBshare_widget_box_top">
			  <p id="FBshare_widget_top_right"></p>
			</div>
			<div id="FBshare_widget_content">
			<div id="FBshare_widget_title" >'.get_option('FBshare_widget_title').'<br><div id="FBshare_total">'.$results1[0]['count'].' Shared on Facebook</div></div>
              <div id="FBshare_widget_data" align="center">';
			  
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
		
		
?>