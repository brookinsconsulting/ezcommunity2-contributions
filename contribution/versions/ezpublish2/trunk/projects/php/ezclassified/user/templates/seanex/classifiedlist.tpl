<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Jobbmarked</span> | Stillingsannonser</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<form action="/contact/search/company" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="h2">Søk i stillingsannonsene&nbsp;&nbsp;</td>
	<td align="right" valign="bottom">
	
	<input type="text" name="SearchText" size="12" /><br />   
	<input type="image" value="{intl-search}" src="/images/button-searchmain.gif" border="0" />

	</td>
</tr>
</table>
</form>

<hr noshade="noshade" size="4"/ >
		
<!-- BEGIN path_tpl -->

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/classified/classifiedlist/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/classified/classifiedlist/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4"/ >

<!-- BEGIN category_list_tpl -->
<h2>{intl-categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}" -->
	<a href=/classified/classifiedlist/list/{category_id}>{category_name}</a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN classified_list_tpl -->

<h2>{intl-companies}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-title}:</th>
	<th>{intl-company}:</th>
	<th>{intl-valid_until}:</th>
</tr>
<!-- BEGIN classified_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/classified/view/{classified_id}/">{classified_title}</a>
	</td>
	<td class="{td_class}">
	{company_name}
	</td>
	<td class="{td_class}">
	{valid_until}
	</td>

</tr>
<!-- END classified_item_tpl -->
</table>
<!-- END classified_list_tpl -->


