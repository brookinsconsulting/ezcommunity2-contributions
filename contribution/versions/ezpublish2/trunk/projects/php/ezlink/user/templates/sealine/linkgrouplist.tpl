<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>L I N K E R</h1>
     </td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN group_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h2>{categories}</h2>
	</td>
</tr>
<!-- BEGIN group_item_tpl -->
{start_tr}
	<td class="{td_class}">
	<b><a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a></b>
	</td>
{stop_tr}
<!-- END group_item_tpl -->
</table>
<!-- END group_list_tpl -->


<!-- BEGIN link_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN link_item_tpl -->
<tr>
	<td bgcolor="{bg_color}">
	<b><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}"  target="_blank">{link_title}</a></b><br />
        {link_description}
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<!-- END link_list_tpl -->
