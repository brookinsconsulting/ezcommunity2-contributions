<h2>{intl-latest_news}</h1>

<!-- BEGIN news_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<a href="{first_news_url}" onclick="return popup('{first_news_url}')">
	<div class="h2">{first_news_name}</div>
	</a>
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	<p class="newslist">{first_news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{first_news_url}" onclick="return popup('{first_news_url}')">{intl-read_more}</a>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<a href="{news_url}" onclick="return popup('{news_url}')"><div class="h2">{news_name}</div></a>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}" onclick="return popup('{news_url}')">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN short_news_item_tpl -->
<tr>
	<td valign="top">
	<a href="{news_url}" onclick="return popup('{news_url}')"><b>{news_name}</b></a>&nbsp;&nbsp;
	<span class="small">
	( {news_origin} - {news_date} )
	</span>
	</td>
<tr>
<!-- END short_news_item_tpl -->

</table>
<!-- END news_list_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td align="center">
	<form action="/newsfeed/search/" method="post">
	<span class="path">{intl-search_the_archive}: </span><input type="text" name="SearchText" size="20" />	
	<input type="submit" value="{intl-search}" />
	</form>
	</td>
</tr>
</table>


