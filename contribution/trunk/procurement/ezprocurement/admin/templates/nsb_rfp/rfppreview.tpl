<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<br />

<h1>{rfp_name}</h1>
<!-- 
<div class="byline">{intl-rfp_author}: {author_text}</div>
-->

<!-- BEGIN rfp_author_list_tpl -->
<span class="subdiv">{intl-rfp_author}: </span><br />

<!-- BEGIN rfp_author_tpl -->
<div style="text-indent: 30pt;">
<a class="subdiv" href="{www_dir}{index}/procurement/author/view/{author_id}">{author_text}</a></div>
<!-- END rfp_author_tpl -->
<!-- END rfp_author_list_tpl -->
<br />



<p>{rfp_intro}</p>

<p>{rfp_body}</p>

<!-- BEGIN attribute_list_tpl -->
<!-- BEGIN type_item_tpl -->
<h2>{type_name}</h2>
<!-- BEGIN attribute_item_tpl -->
<p class="boxtext">{attribute_name}:</p>
{attribute_value}
<!-- END attribute_item_tpl -->
<!-- END type_item_tpl -->
<!-- END attribute_list_tpl -->


<!-- BEGIN attached_file_list_tpl -->
<p class="boxtext">{intl-attached_files}:</p>
<!-- BEGIN attached_file_tpl -->
<div class="p">{file_name}</div>
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->

<br clear="all" />

<!-- BEGIN image_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}" valign="top">
	<img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="0" alt="{image_caption}" align="left" />
	<span class="p">{image_caption}</span>
	</td>
</tr>
<!-- END image_tpl -->

</table>
<!-- END image_list_tpl -->

<br clear="all" />
<!-- BEGIN page_menu_separator_tpl -->
<br />

<hr noshade="noshade" size="4" />
<!-- END page_menu_separator_tpl -->


<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="{www_dir}{index}/procurement/preview/{rfp_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="{www_dir}{index}/procurement/preview/{rfp_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="{www_dir}{index}/procurement/preview/{rfp_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<br clear="all" />

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/procurement/edit/edit/{rfp_id}/" method="post">
<input class="okbutton" type="submit" value="{intl-edit}" />
<input class="okbutton" type="submit" name="Publishrfp" value="{intl-publish}" />

</form>

