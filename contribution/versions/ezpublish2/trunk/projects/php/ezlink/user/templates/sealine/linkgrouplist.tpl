<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN path_tpl -->

<img src="/ezarticle/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/link/group/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/ezarticle/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/link/group/{group_id}/">{group_name}</a>

<!-- END path_item_tpl -->

<hr noshade size="4">

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
<tr>
	<td>
	<h2>{links}</h2>
	</td>
</tr>
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
