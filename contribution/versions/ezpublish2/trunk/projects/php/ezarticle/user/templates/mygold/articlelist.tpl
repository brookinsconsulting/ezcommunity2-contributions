<!-- BEGIN header_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td align="right">
	<form action="/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="okbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="1" />
<!-- END header_item_tpl -->

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/article/archive/0/">{intl-top_level}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/article/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="1" />


<!-- BEGIN current_image_item_tpl -->
<img src="{current_image_url}" alt="{current_image_caption}" width="{current_image_width}" height="{current_image_height}" border="0" />
<!-- END current_image_item_tpl -->

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr align="left">
        <th>&nbsp;</th>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}" width="1%" valign="top">
	<!-- BEGIN image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td width="49%" class="{td_class}" valign="top">
	<a href="/article/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td width="50%" class="{td_class}" valign="top">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="1" />
<br />
<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/1/{category_id}/">{article_name}</a></div>
	<div class="small">( {article_published} )</div>
	<br />

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


	<div class="p">{article_intro}</div>

        <!-- BEGIN read_more_tpl -->
	<br />
	<a class="path" href="/article/articleview/{article_id}/1/{category_id}/">{article_link_text}</a>
	<br /><br />
        <!-- END read_more_tpl -->
	<hr noshade="noshade" size="1"/>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>

<!-- END article_list_tpl -->



<!-- BEGIN type_list_tpl -->
<br />
<div align="center">
	<!-- BEGIN type_list_previous_tpl -->
	&lt;&lt;&nbsp;<a class="path" href="/article/archive/{category_current_id}/{item_previous_index}">{intl-previous}</a>&nbsp;
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	&lt;&lt;&nbsp;<span class="inactive">&nbsp;{intl-previous}&nbsp;</span>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	|&nbsp; <a class="path" href="/article/archive/{category_current_id}/{item_index}">{type_item_name}</a>&nbsp;
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	|&nbsp; <span class="inactive">{type_item_name}</span>&nbsp;
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	|&nbsp;<a class="path" href="/article/archive/{category_current_id}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	|&nbsp;<span class="inactive">{intl-next}</span>&nbsp;&gt;&gt;
	<!-- END type_list_next_inactive_tpl -->
</div>
<!-- END type_list_tpl -->

