<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>Nyttige lenker</h1>
     </td>
     <td align="right">
<!--
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
-->
     </td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN group_list_tpl -->
<h2>Lenkekategorier</h2>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN group_item_tpl -->
<tr>
	<td valign="top" width="99%">
	<b><a href="/link/group/{linkgroup_id}/"class="link">{linkgroup_title}</a></b><br />
	<span class="p">{linkgroup_description}</span>
	</td>
    <td width="1%">
	<!-- BEGIN image_item_tpl -->
	<a href="/link/group/{linkgroup_id}/">
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" /></a>
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
    </td>
</tr>
<!-- END group_item_tpl -->
</table>
<!-- END group_list_tpl -->


<!-- BEGIN link_list_tpl -->
<h2>{group_name}</h2>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN link_item_tpl -->
<tr>
	<td valign="top" class="{td_class}" width="99%">
	<b><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}" target="_blank" class="link">{link_title}</a></b><br />
 	<span class="p">{link_description}</span><br />
   	</td>
	<td valign="top"  class="{td_class}" width="1%">
	<!-- BEGIN link_image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END link_image_item_tpl -->
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<!-- END link_list_tpl -->
