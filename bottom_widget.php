<?php
require('../../../wp-blog-header.php');

		global $wpdb;
		$pid=$_GET['pid'];
		$table=$wpdb->prefix.'FBshare_data';
		
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
			echo '<div id="FBshare_bottom_back"></div><div id="FBshare_bottom" align="center">';
			  foreach ($results as $result):
			  echo '<a onclick="FBshare_go_profile('.$result['FB_id'].');"><img src="http://graph.facebook.com/'.$result['FB_id'].'/picture" width="50" height="50" /></a>';
			  
			  endforeach;
			echo '</div>';
		}
	
?>
