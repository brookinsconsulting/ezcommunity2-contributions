
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
{begin_tr}
	<td>
	<a href="/filemanager/list/{folder_id}/"><img src="/ezfilemanager/user/{image_dir}/folder.png" alt="" width="32" height="32" />{folder_name}</a><br />
	</td>
{end_tr}
<!-- END folder_tpl -->

</table>
<!-- END folder_list_tpl -->

<!-- BEGIN file_list_tpl -->

<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN file_tpl -->
{begin_tr}
	<td>
<a href="/filemanager/download/{file_id}/{original_file_name}/"><img src="/ezfilemanager/user/{image_dir}/file.png" border="0" alt="" width="32" height="32" /><br />{original_file_name}</a><br />
	</td>
{end_tr}
<!-- END file_tpl -->
</table>

<!-- END file_list_tpl -->