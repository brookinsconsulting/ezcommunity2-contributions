<h1>{intl-configure}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/config/" enctype="multipart/form-data" >

<h2>{intl-account_setup}:</h2>
<hr noshade="noshade" size="4">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="33%">{intl-name}:</th>
	<th width="32%">{intl-type}:</th>
	<th width="33%">{intl-folder}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN account_item_tpl -->
<tr>
	<td class="{td_class}">
	{account_name}
	</td>

	<td class="{td_class}">
	{account_type}
	</td>
	<td class="{td_class}">
	{account_folder}
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="AccountArrayID[]" value="{account_id}" />
	</td>
</tr>
<!-- END account_item_tpl -->
</table>

<hr noshade="noshade" size="4">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="Move" value="{intl-new}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="NewFolder" value="{intl-delete}" /></td>
</tr>
</table>
<hr noshade="noshade" size="4">
<input class="okbutton" type="submit" name="Ok" value="{intl-ok}" />

</form>