
<form action="/newsfeed/search/" method="post">
<input type="text" name="SearchText" size="12" />	
<input type="submit" value="{intl-search}" />
</form>	

<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="2">
	<a href="{first_news_url}/">
	<h2>
	{first_news_name}
	</h2>
	</a>
	<span class="small">( {first_news_origin} - {first_news_date} )</span>
	<br />
	<p>{first_news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{first_news_url}">{intl-read_more}</a>
	</td>
</tr>
<tr>
	<td colspan="2">
	<!-- Reklamebanner herfra! -->
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
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
	<td valign="top">
	<a href="{news_url}/">{news_name}</a><br />
	<span class="small">( {news_origin} - {news_date} )</span>
	<br />
	<p>{news_intro}</p>
	<img src="/images/path-arrow.gif" height="10" width="15" border="0" alt=""><a class="path" href="{news_url}">{intl-read_more}</a>
	</td>
{endtr}
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->




