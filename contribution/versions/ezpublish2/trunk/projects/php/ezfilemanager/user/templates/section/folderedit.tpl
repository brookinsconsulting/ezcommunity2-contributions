<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<form method="post" action="/filemanager/folder/{action_value}/{folder_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-folder_edit}</h1>

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_write_permission -->
    <li>{intl-error_write_permission}
    <!-- END error_write_permission -->

    <!-- BEGIN error_name_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_tpl -->

    <!-- BEGIN error_description_tpl -->
    <li>{intl-error_description}
    <!-- END error_description_tpl -->

    <!-- BEGIN error_parent_check_tpl -->
    <li>{intl-error_parent_check}
    <!-- END error_parent_check_tpl -->

    <!-- BEGIN error_read_everybody_permission_tpl -->
    <li>{intl-error_read_everybody_check}
    <!-- END error_read_everybody_permission_tpl -->

    <!-- BEGIN error_write_everybody_permission_tpl -->
    <li>{intl-error_write_everybody_check}
    <!-- END error_write_everybody_permission_tpl -->

</ul>
<!-- END errors_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td colspan="2">
    <p class="boxtext">{intl-folder}:</p>
    <select name="ParentID">
    <option value="0" {selected}>{intl-top_folder}</option>
    <!-- BEGIN value_tpl -->
    <option value="{option_value}" {selected}>{option_level}{option_name}</option>
    <!-- END value_tpl -->
    </select>

    <p class="boxtext">{intl-folder_name}:</p>
    <input type="text" class="box" size="40" name="Name" value="{name_value}"/>

    <p class="boxtext">{intl-folder_description}:</p>
    <textarea name="Description" class="box" cols="40" rows="5" wrap="soft">{description_value}</textarea>
	<br />
	<br />
    </td>
</tr>

<tr>
    <td>
    <p class="boxtext">{intl-read_permissions}</p>
    <select multiple size="5" name="ReadGroupArrayID[]">
    <option value="0" {read_everybody}>{intl-everybody}</option>
    <!-- BEGIN read_group_item_tpl -->
    <option value="{group_id}" {is_read_selected1}>{group_name}</option>
    <!-- END read_group_item_tpl -->
    </select>
    </td>
    <td>
    <p class="boxtext">{intl-write_permissions}</p>
    <select multiple size="5" name="WriteGroupArrayID[]">
    <option value="0" {write_everybody}>{intl-everybody}</option>
    <!-- BEGIN write_group_item_tpl -->
    <option value="{group_id}" {is_write_selected1}>{group_name}</option>
    <!-- END write_group_item_tpl -->
    </select>
    </td>
</tr>
</table>
<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />

	</td>
	<td>&nbsp;</td>
	<td>

	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

	</td>

</tr>
</table>

</form>


