<!-- <h1>{intl-latest_news}</h1>

<hr noshade="noshade" size="4" />
-->

<!-- BEGIN news_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="2">
	<a href="{first_news_url}/">
	<div class="h1">{first_news_name}</div>
	</a>
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	<p class="newslist">{first_news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{first_news_url}">{intl-read_more}</a>
	</td>
</tr>
<tr>
	<td class="tdmini" colspan="2" align="center">

	<!-- Reklamebanner herfra! -->

	<img src="/images/reklame.gif" width="468" height="60" align="center" border="0" alt="" />

	<!-- Reklamebanner fram til hit! -->

	</td>
</tr>
<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<a href="{news_url}/"><div class="h2">{news_name}</div></a>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN short_news_item_tpl -->
<tr>
	<td valign="top">
	<a href="{news_url}/"><b>{news_name}</b></a>&nbsp;&nbsp;
	<span class="small">
	( {news_origin} - {news_date} )
	</span>
	</td>
<!--
	<td valign="top" align="right">
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}">{intl-read_more}</a>
	</td>
-->
<tr>
<!-- END short_news_item_tpl -->

</table>
<!-- END news_list_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">

	<!-- Reklamebanner herfra! -->

	<img src="/images/reklame.gif" width="468" height="60" align="center" border="0" alt="" /><br />

	<!-- Reklamebanner fram til hit! -->

	</td>
</tr>
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td align="center">
	<form action="/newsfeed/search/" method="post">
	<span class="path">Søk i nyhetsarkivet: </span><input type="text" name="SearchText" size="20" />	
	<input type="submit" value="{intl-search}" />
	</form>
	</td>
</tr>
</table>



