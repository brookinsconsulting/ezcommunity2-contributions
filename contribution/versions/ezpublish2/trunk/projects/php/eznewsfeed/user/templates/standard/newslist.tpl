<h1>Siste nytt</h1>

<hr noshade="noshade" size="4" />
<br />
<!-- BEGIN news_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="2">
	<a href="{first_news_url}/">
	<p class="newslist">
	<span class="h1">{first_news_name}</span>
	</a><br />
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	</p>
	<p class="newslist">{first_news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{first_news_url}">{intl-read_more}</a>
	</td>
</tr>
<tr>
	<td colspan="2">
	<!-- Reklamebanner herfra! -->
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center">
     		<img src="/images/reklame.gif" width="468" height="60" align="center" border="0" alt="" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	<!-- Reklamebanner fram til hit! -->
	</td>
</tr>
<!-- BEGIN news_item_tpl -->
{starttr}
	<td valign="top" width="50%">
	<p class="newslist">
	<a href="{news_url}/"><span class="h2">{news_name}</span></a><br />
	<span class="small">( {news_origin} - {news_date} )</span>
	</p>
	<p class="newslist">{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->

</table>
<br />
<!-- END news_list_tpl -->

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td align="center">
	<form action="/newsfeed/search/" method="post">
	<span class="path">Søk i nyhetsarkivet: </span><input type="text" name="SearchText" size="20" />	
	<input type="submit" value="Søk!" />
	</form>
	</td>
</tr>
</table>



