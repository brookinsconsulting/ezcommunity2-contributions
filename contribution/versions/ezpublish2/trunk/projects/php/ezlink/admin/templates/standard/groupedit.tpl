<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<form method="post" action="/link/groupedit/{action_value}/{category_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">


<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Title" size="40" value="{category_name}">

<p class="boxtext">{intl-where}:</p>
<select name="ParentCategory">
<option value="0">{intl-topcat}</option>
<!-- BEGIN parent_category_tpl -->
<option {is_selected} value="{grouplink_id}">{option_level}{grouplink_title}</option>
<!-- END parent_category_tpl -->
</select>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="4" name="Description">{category_description}</textarea>

<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-th_type_current_image}:</p>
<p><img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
</p>
<input type="checkbox" name="DeleteImage">{intl-delete_image}
<!-- END image_item_tpl -->
<p class="boxtext">{intl-th_type_image}:</p>
<!-- BEGIN no_image_item_tpl -->

<!-- END no_image_item_tpl -->
<input size="40" name="ImageFile" type="file" /><br /><br />
<hr noshade="noshade" size="4" />

<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-submit_text}">
	</td>
	<td>&nbsp;</td>
	</form>
	<td>
	<form method="post" action="/link/group/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>

</table>
	
