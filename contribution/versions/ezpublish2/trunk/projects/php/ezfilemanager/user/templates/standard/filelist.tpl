
<!-- BEGIN current_folder_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
   <td>
<a href="/filemanager/list/{folder_id}/"><img src="/ezfilemanager/user/{image_dir}/folder.png" alt="" width="32" height="32" />{folder_name}</a><br />
   </td>
   <td>
   <p>
   {current_folder_description}
   </p>
   </td>
</tr>
</table>

<!-- END current_folder_tpl -->

<hr noshade="noshade" size="4" />

<img src="/ezfilemanager/user/{image_dir}/path-arrow.gif" height="10" width="15" border="0" alt="">
<a class="path" href="/filemanager/list/0/">{intl-file_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="/ezfilemanager/user/{image_dir}/path-slash.gif" height="10" width="20" border="0" alt="">
<a class="path" href="/filemanager/list/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN folder_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >

<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td>
	<a href="/filemanager/list/{folder_id}/"><img src="/ezfilemanager/user/{image_dir}/folder.png" alt="" width="32" height="32" />{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
        <!-- BEGIN folder_write_tpl -->
	<td>
	<a href="/filemanager/folder/delete/{folder_id}/">delete</a><br />
	</td>
        <!-- END folder_write_tpl -->
</tr>
<!-- END folder_tpl -->

</table>
<!-- END folder_list_tpl -->

<form method="post" action="/filemanager/new/" enctype="multipart/form-data">
<!-- BEGIN file_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>

        <th>
	{intl-name}:
	</th>
        <th>
	{intl-size}:
	</th>
        <th>
	{intl-download}:
	</th>
        <th>
	{intl-edit}:
	</th>
        <th>
	{intl-delete}:
	</th>
</tr>
<!-- BEGIN file_tpl -->
<tr>
	<!-- BEGIN read_tpl -->
	<td class="{td_class}">
	<a href="/filemanager/fileview/{file_id}/"><img src="/ezfilemanager/user/{image_dir}/file.png" border="0" alt="" width="32" height="32" /><br />{original_file_name}</a><br />
	</td>
	<td class="{td_class}">
	{file_size}Kb
	</td>
	<td class="{td_class}" width="1%">
	<a href="/filemanager/download/{file_id}/{original_file_name}/">download<br />{original_file_name}</a><br />
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/filemanager/edit/{file_id}/">edit</a><br />
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
	<!-- END write_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->
<table cellspacing="0" cellpadding="4" border="0">
<tr>
        <td>
	<input type="submit" name="NewFile" value="{intl-new_file}">
	</td>
        <td>
	<input type="submit" name="NewFolder" value="{intl-new_folder}">
	<input type="hidden" name="FolderID" value="{main_folder_id}">
	</td>
        <td>
	<input type="submit" name="Delete" value="{intl-delete}">
	</td>
</tr>
</table>
</form>
