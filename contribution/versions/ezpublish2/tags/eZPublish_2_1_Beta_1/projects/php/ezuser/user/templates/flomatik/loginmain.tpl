<form method="post" action="/user/login/login/">

<table width="130" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td colspan="2" bgcolor="#90a0b0" background="/images/menufade-right2.jpg" class="menuhead" align="right">{intl-head_line}</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td colspan="2" class="menubold" align="right">
	{intl-username}:<img src="/images/1x1.gif" width="4" height="1" border="0" alt="" />
	</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td colspan="2" class="menu" align="right">
	<input type="text" size="8" name="Username"/><img src="/images/1x1.gif" width="4" height="1" border="0" alt="" />
	</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td colspan="2" class="menubold" align="right">
	{intl-password}:<img src="/images/1x1.gif" width="4" height="1" border="0" alt="" />
	</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td colspan="2" class="menu" align="right"> 
	<input type="password" size="8" name="Password" /><img src="/images/1x1.gif" width="4" height="1" border="0" alt="" />
	</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td colspan="2" align="right">
	<input class="stdbutton" type="submit" value="{intl-ok}"><img src="/images/1x1.gif" width="4" height="1" border="0" alt="" />
	</td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<tr>
	<td width="99%" align="right"><a class="menu" href="/user/forgot/">{intl-forgot}</a></td>
	<td width="1%" valign="top"><img src="/images/dot-right.gif" width="10" height="12" border="0" alt="" /><br /></td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<!-- BEGIN standard_creation_tpl -->
<tr>
	<td width="99%" align="right"><a class="menu" href="{user_edit_url}">{intl-register}</a></td>
	<td width="1%" valign="top"><img src="/images/dot-right.gif" width="10" height="12" border="0" alt="" /><br /></td>
    <td width="1%" bgcolor="#90a0b0"><img src="images/1x1.gif" width="1" height="1" alt="" /></td>
</tr>
<!-- END standard_creation_tpl -->
<!-- BEGIN extra_creation_tpl -->
{extra_userbox}
<!-- END extra_creation_tpl -->
<tr>
    <td colspan="2" width="99%" bgcolor="#90a0b0" background="/images/menufade-right2.jpg" class="tdbottommini"><img src="/images/1x1.gif" width="1" height="1" alt="" /><br /></td>
    <td class="tdbottommini" width="1%" bgcolor="#90a0b0"><img src="/images/1x1.gif" width="1" height="1" alt="" /><br /></td>
</tr>
<tr>
	<td colspan="3" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

