<?php

$update_msg=0;
if(isset($_POST['FBshare']))
{
	update_FBshare_option($_POST);
	$update_msg=1;
}
$main_option=get_option('FBshare_display');

?>
<div class="wrap">
<?php if($update_msg==1) echo '<div class="updated" id="message"><p>Setting Sucessfully saved.</p></div>';
?>
<h2>ShareFaces Option</h2>
<form method="post" action="">
<table class="FBshare_table form-table">
<tr>
<p>Like ShareFaces? Do you want to see more features in the upcoming plugin updates? Kindly <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RQNBTGSWVG85A">make a donation</a> through our Tolong.org program. We appreciate your support!</p>
</tr>
<tr>
<td>
Automatically add Share Button to your posts?*
</td>
<td>
			<select name="option_main">
            <option value="1" <?php if($main_option==1)echo 'selected="selected"';?>>Yes</option>
            <option value="0" <?php if($main_option==0)echo 'selected="selected"';?>>No</option>
            </select>
</td>
</tr>

<tr>
<td>
Display FacesBar (Footer)
</td>
<td>
<select name="FBshare_bottom_widget">
<option value="1" <?php if(get_option('FBshare_bottom_widget')==1) echo 'selected="selected"';?>>Yes</option>
<option value="0" <?php if(get_option('FBshare_bottom_widget')==0) echo 'selected="selected"';?>>No</option>
</select>
</td>
</tr>

<tr>
<td>
Include Jquery script (*if error then select no)
</td>
<td>
<select name="FBshare_jquery">
<option value="1" <?php if(get_option('FBshare_jquery')==1) echo 'selected="selected"';?>>Yes</option>
<option value="0" <?php if(get_option('FBshare_jquery')==0) echo 'selected="selected"';?>>No</option>
</select>
</td>
</tr>


<tr>
<td>
Include thickbox script (*if error then select no)
</td>
<td>
<select name="FBshare_thickbox">
<option value="1" <?php if(get_option('FBshare_thickbox')==1) echo 'selected="selected"';?>>Yes</option>
<option value="0" <?php if(get_option('FBshare_thickbox')==0) echo 'selected="selected"';?>>No</option>
</select>
</td>
</tr>

<tr>
<td>
Facebook Button Image Path (*please insert full path) 
</td>
<td>
<input type="text" name="img_path" value="<?php echo get_option('FBshare_button_img');?>" size="40"/>
</td> 
</tr>
<tr>
<td>
Messages to be displayed in the Thank You Box
</td>
<td>
<textarea name="FBshare_msg" id="FBshare_msg"><?php echo get_option('FBshare_msg');?></textarea>
</td>
</tr>

<tr>
<td>
<input type="submit" value="Save Changes" class="button-primary" name="FBshare">
</td>
<td>
</td>
</tr>
</table>
</form>
</div>