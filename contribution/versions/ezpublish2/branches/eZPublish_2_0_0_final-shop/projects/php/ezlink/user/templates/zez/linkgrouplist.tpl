<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN path_tpl -->

<img src="/images/path-arrow.gif" height="10" width="12" border="0">

<a class="path" href="/link/group/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="16" border="0">

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
<tr>
	<td class="{td_class}" valign="top">
	<b><a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a></b><br />
<!--	<div class="spacer"><span class="p">{linkgroup_description}</span></div> -->
	</td>
    <td class="{td_class}">
	<!-- BEGIN image_item_tpl -->
	<a href="/link/group/{linkgroup_id}/">
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" /></a>
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
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h2>{links}</h2>
	</td>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td valign="top" class="{td_class}">
	<b><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}"  target="_blank">{link_title}</a></b><br />
 	<span class="p">{link_description}</span><br />
	<span class="small">(Hits: {link_hits})</span>
   	</td>
	<td valign="top"  class="{td_class}">
	<!-- BEGIN link_image_item_tpl -->
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
	<!-- END link_image_item_tpl -->
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<!-- END link_list_tpl -->
