<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-name}:
	</th>

	<td align="right">
	<b>{intl-telephone}:</b>
	</td>
</tr>
<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	{company_name}
	</td>
	<td align="right" class="{td_class}">
	{telephone}
	</td>
</tr>

<!-- END company_item_tpl -->

<!-- BEGIN error_tpl -->
<tr>
	<td class="{td_class}">
	<p class="error">{error_msg}</p>
	</td>	
</tr>
<!-- END error_tpl -->

</table>


