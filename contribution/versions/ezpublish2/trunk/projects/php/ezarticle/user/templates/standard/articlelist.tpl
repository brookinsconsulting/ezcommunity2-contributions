<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td align="right">
	<form action="/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/article/archive/0/">{intl-top_level}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/article/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
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
	<div class="listheadline"><a class="listheadline" href="/article/articleview/{article_id}/"><span class="noline">{article_name}</span></a></div>
	<div class="small">{article_published}</div>

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


