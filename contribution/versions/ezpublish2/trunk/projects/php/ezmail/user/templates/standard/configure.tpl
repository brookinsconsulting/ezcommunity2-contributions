<form method="post" action="/mail/config/" enctype="multipart/form-data" >

<h1>{intl-configure}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-account_setup}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="33%">{intl-name}:</th>
	<th width="32%">{intl-type}:</th>
	<th width="27%">{intl-folder}:</th>
	<th width="5%">{intl-active}:</th>
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
	<input type="checkbox" name="AccountActiveArrayID[]" value="{account_id}" {account_active_checked}/>
	</td>
	<td class="{td_class}">
	  <a href="/mail/accountedit/{account_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{account_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{account_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	</td>	
	<td class="{td_class}">
	<input type="checkbox" name="AccountArrayID[]" value="{account_id}" />
	</td>
</tr>
<!-- END account_item_tpl -->
</table>
<br />
<br />

<h2>{intl-filters_setup}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="98%">{intl-name}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN filter_item_tpl -->
<tr>
	<td class="{td_class}">
	{filter_name}
	</td>
	<td class="{td_class}">
	  <a href="/mail/filteredit/{filter_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{filter_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{filter_id}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	</td>	
	<td class="{td_class}">
	<input type="checkbox" name="FilterArrayID[]" value="{filter_id}" />
	</td>
</tr>
<!-- END filter_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteAccounts" value="{intl-delete}" />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="NewAccount" value="{intl-new}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="NewFilter" value="{intl-new_filter}" /></td>
</tr>
</table>
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Ok" value="{intl-ok}" />

</form>