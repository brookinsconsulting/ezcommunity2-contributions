<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="35%">
	<h1>Links</h1>
	</td>
	<td width="45%" align="right">
	<form method="post" action="/link/search/">
	<input type="text" name="QueryText" size="20" value=""><input type="submit" value="Search">
	<input type="hidden" name="Action" value="search">
	</form>
	</td>
	</td>
<tr>
</table>

<hr noshade size="4">

{printpath}

<hr noshade size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN group_list_tpl -->
{start_tr}
	<td class="{td_class}">
	<a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a> &nbsp;({total_links}, {new_links} {intl-new})
	</td>
{stop_tr}
<!-- END group_list_tpl -->
</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN link_list_tpl -->
<tr>
	<td bgcolor="{bg_color}">
	<a href="/link/gotolink/addhit/{link_id}/{link_url}/"  target="_blank">{link_title}</a><br />
        {link_description}
	</td>
	<td bgcolor="{bg_color}" width="80" align="right">
	(Hits: {link_hits})
     	</td>
</tr>
<!-- END link_list_tpl -->
</table>

