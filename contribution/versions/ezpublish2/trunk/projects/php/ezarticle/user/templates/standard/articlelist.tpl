<h1>Artikkel arkiv</h1>

<hr noshade size="4"/>
/ <a href="/article/archive/0/">Toppnivå</a> / 
<!-- BEGIN path_item_tpl -->
<a href="/article/archive/{category_id}/">{category_name}</a> / 
<!-- END path_item_tpl -->

<hr noshade size="4"/>


<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Kategori:
	</td>

	<td>
	Beskrivelse:
	</td>

</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/article/archive/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade size="4"/>
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
	<h3><a href="/article/articleview/{article_id}/">
	{article_name}
	</a>
	</h3>

	<!-- BEGIN article_image_tpl -->
	    <table align="right">
	        <tr>
			<td>
                        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
                        </td>
                </tr>
                <tr>
                         <td>
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->


	<p>
	{article_intro}
	</p>
	<a href="/article/articleview/{article_id}/">
	{article_link_text}
	</a>
	<br />
	<br />
	<br />
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




