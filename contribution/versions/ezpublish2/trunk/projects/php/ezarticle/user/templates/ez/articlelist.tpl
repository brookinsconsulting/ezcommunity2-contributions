<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{current_category_name}</div>
	</td>
</tr>
</table>
<br />
<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->

<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#c0c0c0">
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/">{article_name}</a></div>
	</td>
</tr>
</table>
	<div class="small"><br />{article_published}</div>

	<!-- BEGIN article_image_tpl -->
	    <table align="right">
	        <tr>
			<td>
			<a href="/article/articleview/{article_id}/"><img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
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
	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/article/articleview/{article_id}/">{article_link_text}</a>
	<br /><br />
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

<!-- BEGIN previous_tpl -->
<a class="path" href="/article/archive/{category_id}/{prev_offset}/">
&lt;&lt; {intl-prev}
</a>
<!-- END previous_tpl -->
     </td>
     <td align="right">

<!-- BEGIN next_tpl -->
<a class="path" href="/article/archive/{category_id}/{next_offset}/">
{intl-next} &gt;&gt;
</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>    


