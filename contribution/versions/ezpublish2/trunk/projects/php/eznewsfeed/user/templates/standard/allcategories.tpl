<h2>{intl-latest_news}</h1>

<!-- BEGIN news_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="{num_cols}">
	<a href="/newsfeed/latest/{category_id}/"><h2>{category_name}</h2></a>
	</td>
</tr>

<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<a href="{news_url}"><div class="h2">{news_name}</div></a>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}" onclick="return popup('{news_url}')">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>


<!-- END news_list_tpl -->

