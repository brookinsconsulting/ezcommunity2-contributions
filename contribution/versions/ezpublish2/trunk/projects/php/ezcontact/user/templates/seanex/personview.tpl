<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Brukerinfo</span> | {intl-view_headline}</div></td>
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

<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<span class="p">{firstname} {lastname}</span>
	</td>
	<td>
	<p class="boxtext">{intl-birthday_headline}: </p>
	<span class="p">{birthday}.{birthmonth}.{birthyear}</span>
	</td>
</tr>
</table>

<!-- BEGIN address_item_tpl -->
<h2>{intl-addresses_headline}</h2>
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN address_line_tpl -->
	<td>
	<p class="boxtext">{address_type_name}:</p>
	<div class="p">{street1}</div>
	<div class="p">{street2}</div>
	<div class="p">{zip} {place}</div>
	</td>
<!-- END address_line_tpl -->
</tr>
</table>
<!-- END address_item_tpl -->

<!-- BEGIN no_address_item_tpl -->
<h2>{intl-addresses_headline}</h2>
<p>{intl-error_no_addresses}</p>
<!-- END no_address_item_tpl -->


<h2>{intl-telephone_headline}</h2>

<!-- BEGIN phone_item_tpl -->
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN phone_line_tpl -->
	<td valign="top">
	<p class="boxtext">{phone_type_name}:</p>
	{phone}
	</td>
<!-- END phone_line_tpl -->
</tr>
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<h2>{intl-telephone_headline}</h2>
<p>{intl-error_no_phones}</p>
<!-- END no_phone_item_tpl -->

<h2>{intl-online_headline}</h2>
<!-- BEGIN online_item_tpl -->
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN online_line_tpl -->
	<td>
	<p class="boxtext">{online_type_name}:</p>
	{online}
	</td>
<!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->
<!-- BEGIN no_online_item_tpl -->
<h2>{intl-online_headline}:</h2>
<p>{intl-error_no_onlines}</p>
<!-- END no_online_item_tpl -->

<h2>{intl-comment_headline}</h2>
<p>{comment}</p>

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<form method="post" action="/contact/person/edit/{person_id}/" enctype="multipart/form-data">
	<input class="okbutton" type="submit" value="{intl-edit}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/contact/person/list/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

