<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	<h1>{article_name}</h1>
	</td>
    </tr>
</table>
<hr noshade="noshade" size="1" />
<br />

<!-- BEGIN article_header_tpl -->
<!-- END article_header_tpl -->
<p>
{article_body}
</p>

<p>

<!-- BEGIN attached_file_list_tpl -->
<h3>{intl-attached_files}:</h3>
<!-- BEGIN attached_file_tpl -->
<a href="/filemanager/download/{file_id}/{original_file_name}/">{file_name}</a><br />
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->

<!-- BEGIN prev_page_link_tpl -->
<a class="path" href="/article/articleview/{article_id}/{prev_page_number}/">&lt;&lt; {intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->

<!-- BEGIN current_page_link_tpl -->
| < {page_number} >
<!-- END current_page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/{next_page_number}/">{intl-next_page} &gt;&gt;</a>
<!-- END next_page_link_tpl -->
</p>

<p>
<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
| <a class="path" href="/article/articleprint/{article_id}/">{intl-print_page}</a> |
<!-- END print_page_link_tpl -->
</p>