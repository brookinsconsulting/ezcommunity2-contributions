

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h1>Seanex.no er ute på nettet!</h1>
	<hr noshade="noshade" size="4" />
	<p>
	Egen boks for PR for Seanex.no. Denne kan fylles med hva som helst, men gjerne fisk.
	Egen boks for PR for Seanex.no. Denne kan fylles med hva som helst, men gjerne fisk.
	Egen boks for PR for Seanex.no. Denne kan fylles med hva som helst, men gjerne fisk.
	Egen boks for PR for Seanex.no. Denne kan fylles med hva som helst, men gjerne fisk.
	</p>
	</td>
</tr>
</table>
<br />

<!-- {intl-latest_news} -->
<h1>Siste nytt om fiskerinæringen</h1>

<hr noshade="noshade" size="4" />


<!-- BEGIN news_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<a href="{first_news_url}">
	<div class="h2">{first_news_name}</div>
	</a>
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	<p class="newslist">{first_news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{first_news_url}">{intl-read_more}</a>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="mini"><img src="/images/1x1.gif" height="4" width="1" border="0" alt=""><br /></td>
</tr>
<tr>
	<td class="tdmini" align="center">

	<!-- Reklamebanner herfra! -->

	<img src="/images/reklame.gif" width="468" height="60" align="center" border="0" alt="" />

	<!-- Reklamebanner fram til hit! -->

	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<a href="{news_url}"><div class="h2">{news_name}</div></a>
	<span class="small">( {news_origin} - {news_date} )</span>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->
</table>
<br />

<!--
<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN short_news_item_tpl -->
<tr>
	<td valign="top">
	<a href="{news_url}"><b>{news_name}</b></a>&nbsp;&nbsp;
	<span class="small">
	( {news_origin} - {news_date} )
	</span>
	</td>
<tr>
<!-- END short_news_item_tpl -->

</table>
<!-- END news_list_tpl -->
-->

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



