<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" size="12" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<form method="post" action="/link/linkedit/{action_value}/{link_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-title}:</p>
<!-- {intl-titleedit} -->
<input type="text" class="box" name="Title" size="40" value="{title}">

<p class="boxtext">{intl-linkgroup}:</p>
<!-- {intl-choosegroup} -->
<select name="LinkGroupID">
	<!-- BEGIN link_group_tpl -->
	<option {is_selected} value="{link_group_id}">{option_level}{link_group_title}</option>
	<!-- END link_group_tpl -->
</select>

<p class="boxtext">{intl-url}: <a href="/link/gotolink/addhit/{link_id}/?Url={url}">{url}</a> </p>
<!-- {intl-urledit} -->
http://<input type="text" class="halfbox" name="Url" size="40" value="{url}">

<br />

<input class="stdbutton" type="submit" value="{intl-meta}" name="GetSite" />

<p class="boxtext">{intl-key}:</p>
<!-- {intl-search} -->
<textarea class="box" rows="5" cols="40" name="Keywords">{keywords}</textarea>

<br />

<p class="boxtext">{intl-desc}:</p>
<!-- {intl-discedit} -->
<textarea class="box" rows="5" cols="40" name="Description">{description}</textarea>
<br />

<p class="boxtext">{intl-accepted}</p>
<select name="Accepted">
	<option {no_selected} value="0">{intl-no}</option>
	<option	{yes_selected} value="1">{intl-yes}</option>
</select>

<br /><br />

<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-current_image}:</p>
<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />

<input type="checkbox" name="DeleteImage" />&nbsp;{intl-delete_image}
<!-- END image_item_tpl -->

<!-- BEGIN no_image_item_tpl -->

<!-- END no_image_item_tpl -->

<p class="boxtext">{intl-upload_image}:</p>
<input size="40" name="ImageFile" type="file" />&nbsp;
<br /><br /><input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
<br /><br />


<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}">
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</td>
</tr>
</table>

</form>
