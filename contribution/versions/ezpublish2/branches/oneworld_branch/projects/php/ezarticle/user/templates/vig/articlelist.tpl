<!-- BEGIN header_item_tpl -->

<!-- END header_item_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN article_item_tpl -->
<tr>
	<td width="100%">
	<a class="listheadline" href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
	<div class="small">( {article_published} )</div>

	<!-- BEGIN article_image_tpl -->
	    <table width="1%" cellpadding="0" cellspacing="0" border="0" align="right">
	        <tr>
			<td>
			<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
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
	<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" /><a class="path" href="{www_dir}{index}/article/articleview/{article_id}/">{article_link_text}</a>
	</td>
</tr>
<tr>
	<td><br /></td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<!-- BEGIN type_list_tpl -->
<br />

	<!-- BEGIN type_list_previous_tpl -->

	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->

	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->

	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->

	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->


	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->

	<!-- END type_list_next_inactive_tpl -->

<!-- END type_list_tpl -->

