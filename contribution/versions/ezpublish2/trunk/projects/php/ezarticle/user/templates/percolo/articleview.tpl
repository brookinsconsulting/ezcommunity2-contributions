<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
	<h3>Artikkelen er funnet på http://{article_url}</h3>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{article_name}</h1>
	</td>
	<td align="right">
	<form action="/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN article_header_tpl -->

<!-- END article_header_tpl -->

<p>
{article_intro}
</p>

<p>
{article_body}
</p>

<!-- BEGIN attached_file_list_tpl -->
<p class="boxtext">{intl-attached_files}:</p>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN attached_file_tpl -->
<tr class="{td_class}">
     <td width="20%">
     {file_name}
     </td>
     <td width="80%">
     <div class="p"><a href="/filemanager/download/{file_id}/{original_file_name}/">( {original_file_name} {file_size}&nbsp;{file_unit} )</a></div>
     </td>
</tr>
<tr class="{td_class}">
     <td colspan="2">
     {file_description}
     </td>
</tr>
<tr>
     <td>&nbsp;</td>
</tr>
<!-- END attached_file_tpl -->
</table>
<!-- END attached_file_list_tpl -->

<br clear="all" />

<div align="center">
<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="/article/articleview/{article_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| <span class="p"> &lt;&nbsp;{page_number}&nbsp;&gt; </span>
<!-- END current_page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="/article/articleprint/{article_id}/">{intl-print_page}</a> |
<!-- END print_page_link_tpl -->
</div>