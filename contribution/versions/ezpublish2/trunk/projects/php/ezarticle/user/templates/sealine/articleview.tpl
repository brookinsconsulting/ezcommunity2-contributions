<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1 class="small">{article_name}</h1>
	</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tdmini" width="1%" background="/images/gyldenlinje-strekk.gif"><img src="/images/gyldenlinje-venstre.gif" width="8" height="4" border="0" /><br /></td>
    <td class="tdmini" width="98%" background="/images/gyldenlinje-strekk.gif"><img src="/images/1x1.gif" width="1" height="1" border="0" /><br /></td>
    <td class="tdmini" width="1%" background="/images/gyldenlinje-strekk.gif"><img src="/images/gyldenlinje-hoyre.gif" width="8" height="4" border="0" /><br /></td>
</tr>
</table>

<!-- BEGIN article_header_tpl -->

<!-- END article_header_tpl -->

<p>
{article_body}
</p>

<p>
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

<!-- BEGIN numbered_page_link_tpl -->
| <a class="path" href="/article/articleview/{article_id}/0/">{intl-numbered_page}</a> |
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->

<!-- END print_page_link_tpl -->
</p>