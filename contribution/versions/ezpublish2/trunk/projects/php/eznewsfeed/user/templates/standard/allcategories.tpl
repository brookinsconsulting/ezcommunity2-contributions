<h1>{intl-latest_news}</h1>

<!-- BEGIN news_list_tpl -->

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="{num_cols}">
	<a href="/newsfeed/latest/{category_id}/"><h1>{category_name}</h1></a>
	</td>
</tr>

<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<a href="{news_url}" target="_vblank"><h2>{news_name}</h2></a>
	<span class="small">( {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}" target="_vblank">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>


<!-- END news_list_tpl -->

