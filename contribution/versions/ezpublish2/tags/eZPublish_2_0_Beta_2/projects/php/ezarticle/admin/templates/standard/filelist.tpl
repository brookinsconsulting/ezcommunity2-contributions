<form action="/article/articleedit/fileedit/new/{article_id}/" method="post">

<h1>{intl-files}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-file_id}:</th>
	<th>{intl-file_name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN file_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{file_number}
	</td>
	<td width="97%" class="{td_class}">
	{file_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/fileedit/edit/{file_number}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/fileedit/delete/{file_number}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-slett','','/images/{site_style}/slettminimrk.gif',1)"><img name="eztp{file_number}-slett" border="0" src="/images/{site_style}/slettmini.gif" width="16" height="16" align="top" border="0" alt="Delete" /></a>
	</td>
</tr>
<!-- END file_tpl -->

</table>

<br/>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewFile"value="{intl-file_upload}" />

<hr noshade="noshade" size="4" />

</form>

<form action="/article/articleedit/edit/{article_id}/" method="post">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

</form>
