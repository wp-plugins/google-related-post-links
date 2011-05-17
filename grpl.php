<?php
/*
Plugin Name: Google related post links
Plugin URI: http://letusbuzz.com
Description: Google related post links
Author URI: http://letusbuzz.com
Author: Sudipto Pratap Mahato
Version: 1.1
*/
add_filter('the_content', 'g_ser');

function g_ser($content)
{
global $post;
$plink = get_permalink($post->ID);
$elink = urlencode($plink);
$etitle = urlencode(str_replace(" ","+",get_the_title($post->ID)));
$domain=str_replace("http://","",site_url());
if(!is_single())return $content;
if(get_option('gr_noposts')==""){
update_option('gr_noposts','5');
update_option('gr_rstitle','Related Search');
update_option('gr_rptitle','Related Posts');
}
$sel=get_option('gr_noposts');
$url='http://relatedlinks.googlelabs.com/config/demo?url='.$elink.'&title='.$etitle.'&domain='.$domain.'&language=en';
$boy = get_data_url($url);
if($boy != "")
{
   $ex=explode("<form action",$boy);
   $ex2=explode("</table>",$ex[1]);
   $boy=$ex2[1];
   
   $rep = array("<tbody>","<tr>","</tbody>","</tr>","<td>","<table>","<br>","</form>","\n","\r");
$boy = str_replace($rep, "", $boy);
$ex3=explode("Related Searches by Google</td>",$boy);
$ex4=explode("</td>",trim($ex3[0]));
if($sel>count($ex4)-1)$sel=count($ex4)-1;
$boy='<div id="grelpost"><h3>'.get_option('gr_rptitle').'</h3><ul>';
$hp=true;
for($i=1;$i<=$sel;$i++)
{
	if(trim($ex4[$i])!=""){
	 $boy.="<li>".$ex4[$i]."</li>";
	 $hp=false;
	 }
}
if($hp==true)$boy.="No related posts found";
$boy.="</ul></div>";
if(get_option('gr_showrs')=="checked")
{
$boy.='<div id="grelser"><h3>'.get_option('gr_rstitle').'</h3><ul><li>'.str_replace("</td>","</li><li>",substr(trim($ex3[1]),0,-5))."</li></ul></div>";	
}
}else $boy="No related links found";


return $content.$boy;
} 
 function get_data_url($url)
  {
      $aglist[] = "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)";
      $aglist[] = "Mozilla/5.0 (compatible; Konqueror/3.92; Microsoft Windows) KHTML/3.92.0 (like Gecko)";
      $aglist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; .NET CLR 1.1.4322; Windows-Media-Player/10.00.00.3990; InfoPath.2";
      $aglist[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; Dealio Deskball 3.0)";
      $aglist[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; NeosBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
      $ua = $aglist[array_rand($aglist)];
      if (function_exists('curl_init')) {
          $handle = curl_init();
          curl_setopt($handle, CURLOPT_URL, $url);
          curl_setopt($handle, CURLOPT_USERAGENT, $ua);
          curl_setopt($handle, CURLOPT_TIMEOUT, 60);
          curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 1);
          curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
          $buffer = curl_exec($handle);
          curl_close($handle);
          return $buffer;
      } else return false;
 }
function gr_option()
{
if(get_option('gr_noposts')==""){
update_option('gr_noposts','5');
update_option('gr_rstitle','Related Search');
update_option('gr_rptitle','Related Posts');
}?>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<h1>Google Related Post Links</h1>
Like this Plugin then why not hit the like button<br />
<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLet-us-Buzz%2F149408071759545&layout=standard&show_faces=false&width=450&action=like&font=verdana&colorscheme=light&height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe><br />And if you are too generous then you can always <b>DONATE</b> by clicking the donation button.<br/>If you like the plugin then <b>write a review</b> of it pointing out the plus and minus points.
<table class="form-ta">	
<tr valign="top">
<td>
<p><b>Options</b></p>
Number of Related posts to display
<select name="gr_noposts">
<?php
$sel=get_option('gr_noposts');
for ($i = 1; $i <= 8; $i++) {
?>
<option <?php if($i==$sel)echo 'selected="selected"'; ?>><?php echo $i; ?></option>
<?php } ?>
</select>
<p><input type="checkbox" name="gr_showrs" value="checked" <?php echo get_option('gr_showrs'); ?> />Show Google related search</p>
<p>Related Posts Title<input type="text" name="gr_rptitle" value="<?php echo get_option('gr_rptitle'); ?>" /></p>
<p>Related Search Title<input type="text" name="gr_rstitle" value="<?php echo get_option('gr_rstitle'); ?>" /></p>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="gr_noposts,gr_showrs,gr_rptitle,gr_rstitle" />
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</td>
<td width="20%"><b>Follow us on</b><br/><a href="http://twitter.com/letusbuzz" target="_blank"><img src="http://a0.twimg.com/a/1303316982/images/twitter_logo_header.png" /></a><br/><a href="http://facebook.com/letusbuzzz" target="_blank"><img src="https://secure-media-sf2p.facebook.com/ads3/creative/pressroom/jpg/b_1234209334_facebook_logo.jpg" height="38px" width="118px"/></a><p></p><b>Feeds and News</b><br /><?php get_feeds_grs('http://letusbuzz.com/feed','style="list-style-type:circle"') ?>
<p></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="isudipto@gmail.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Google Related Post Links Plugin">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br />Consider a Donation and remember $X is always better than $0
</td>
</tr>
</table>

<?php
}
function get_feeds_grs($link,$style) {
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed($link);
if (!is_wp_error( $rss )){
$rss5 = $rss->get_item_quantity(5); 
$rss1 = $rss->get_items(0, $rss5); 
}
?>
<ul>
<?php if (!$rss5 == 0)foreach ( $rss1 as $item ){?>
<li <?php echo $style; ?>>
<?php $plink=$item->get_permalink(); ?>
<?php if($plink!="" && isset($plink)){ ?>
<a target="_blank" href='<?php echo $item->get_permalink(); ?>'><?php echo $item->get_title(); ?></a>
<?php }else echo $item->get_title(); ?>
</li>
<?php } ?>
</ul>
<?php
}
function gr_admin()
{
	add_options_page('Google Related Posts', 'Google Related Posts', 7, 'googleretated', 'gr_option');
}
add_action('admin_menu', 'gr_admin');
?>
