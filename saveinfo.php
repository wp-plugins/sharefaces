<?php
if(isset($_GET['u']) && isset($_GET['p']) && $_GET['u']!='' && $_GET['p']!='')
{

	require('../../../wp-blog-header.php'); 
	global $wpdb;
	$table=$wpdb->prefix ."FBshare_data";
	$sql="select * from $table where FB_id='".$_GET['u']."' and post_id='".$_GET['p']."'";
	$wpdb->query($sql);
	
	
	if($wpdb->num_rows)
	{
		$sql="update $table set count=count+1 where FB_id='".$_GET['u']."' and post_id='".$_GET['p']."'";
	}
	else
	{
		$sql="insert into $table (`id`,`FB_id`,`post_id`,`count`) values('','".$_GET['u']."','".$_GET['p']."','1');";
	}
	
	$wpdb->query($sql);

}
else
{
?>
<script>
alert('something goes wrong....');
</script>
<?php
}
?>
