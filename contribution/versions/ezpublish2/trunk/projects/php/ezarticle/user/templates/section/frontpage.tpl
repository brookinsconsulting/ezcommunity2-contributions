<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-head_line}</h1>

<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN article_item_tpl -->
{start_tr}
	<td valign="top" width="50%">
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
	<div class="small">( {article_published} )</div>

	<!-- BEGIN article_image_tpl -->
	    <table width="1%" align="right">
	        <tr>
			<td>
			<a href="/article/articleview/{article_id}/1/{category_id}/"><img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
            </td>
                </tr>
                <tr>
                         <td class="pictext">
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->


	<div class="spacer"><div class="p">{article_intro}</div></div>

        <!-- BEGIN read_more_tpl -->
	<img src="/images/path-arrow.gif" height="10" width="8" border="0" alt="" />
	<a class="path" href="/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END read_more_tpl -->
	</td>
{stop_tr}
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<!-- BEGIN article_short_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN article_short_item_tpl -->
<tr>
	<td>
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
	<div class="small">( {article_published} )</div>

	<!-- BEGIN article_short_image_tpl -->
	    <table width="1%" align="right">
	        <tr>
			<td>
			<a href="/article/articleview/{article_id}/1/{category_id}/"><img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
            </td>
                </tr>
                <tr>
                         <td class="pictext">
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_short_image_tpl -->


	<div class="spacer"><div class="p">{article_intro}</div></div>

        <!-- BEGIN article_short_read_more_tpl -->
	<img src="/images/path-arrow.gif" height="10" width="8" border="0" alt="" />
	<a class="path" href="/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END article_short_read_more_tpl -->
	</td>
</tr>
<!-- END article_short_item_tpl -->
</table>
<!-- END article_short_list_tpl -->


