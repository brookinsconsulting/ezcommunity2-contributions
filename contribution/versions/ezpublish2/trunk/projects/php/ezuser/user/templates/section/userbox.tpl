<form method="post" action="/user/login/logout/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="/sitedesign/designsection1/images/articles-dummy.gif" width="110" height="17"><br />
	<img src="/images/1x1.gif" width="1" height="5"><br />
	</td>
</tr>
<tr>
	<td colspan="2" class="menubold">
	<div class="rightmenu">
	{intl-userlogin}:
	</div>
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	<div class="rightmenu">
	{first_name} {last_name}
	</div>
	</td>
</tr>
<tr>
	<td colspan="2">
	<div class="rightmenu">
	<input class="stdbutton" type="submit" value="{intl-logout}" />
	</div>
	</td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{user_edit_url}/{user_id}/">{intl-change_user_info}</a></td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

