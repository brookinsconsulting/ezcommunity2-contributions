<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{article_name}</h1> 
	<div class="byline">{intl-article_author}: {author_text}</div>

	<p>
	{article_body}
	</p>
	</td>
</tr>
</table>

<!-- <span class="boxtext">{intl-link_text}:</span> {link_text} -->


<!-- BEGIN attached_file_list_tpl -->
<p class="boxtext">{intl-attached_files}:</p>
<!-- BEGIN attached_file_tpl -->
<div class="p">{file_name}</div>
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->


<!-- BEGIN page_menu_separator_tpl -->
<br />

<hr noshade="noshade" size="4" />
<!-- END page_menu_separator_tpl -->


<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="/article/articlepreview/{article_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="/article/articlepreview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="/article/articlepreview/{article_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->

<br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
		<form action="/article/articleedit/edit/{article_id}/" method="post">
		<input class="okbutton" type="submit" value="{intl-edit}" />
		</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

