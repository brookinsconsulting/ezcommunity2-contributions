<form method="post" action="/filemanager/new/" enctype="multipart/form-data">

<h1>{intl-files}</h1>

<!-- BEGIN current_folder_tpl -->
<!--
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
   <td>
<img src="/ezfilemanager/user/{image_dir}/folder.gif" alt="" width="16" height="16" border="0" />&nbsp;<a href="/filemanager/list/{folder_id}/">{folder_name}</a><br />
   </td>
   <td>
   <p>
   {current_folder_description}
   </p>
   </td>
</tr>
</table>
-->
<!-- END current_folder_tpl -->

<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/filemanager/list/0/">{intl-file_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/filemanager/list/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="p">{current_folder_description}</div>

<!-- BEGIN folder_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN folder_tpl -->
<tr>
        <!-- BEGIN folder_read_tpl -->
	<td width="1%">
	<img src="/images/folder.gif" alt="" width="16" height="16" border="0" />
	</td>
	<td width="98%">
	<a href="/filemanager/list/{folder_id}/">{folder_name}</a><br />
	</td>
        <!-- END folder_read_tpl -->
        <!-- BEGIN folder_write_tpl -->
	<td width="1%">
	<a href="/filemanager/folder/delete/{folder_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{folder_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezf{folder_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a><br />
	</td>
        <!-- END folder_write_tpl -->
</tr>
<!-- END folder_tpl -->

</table>
<!-- END folder_list_tpl -->

<!-- BEGIN file_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!--
<tr>
	<th>&nbsp;</th>
    <th>{intl-name}:</th>
    <th>{intl-size}:</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
-->
<!-- BEGIN file_tpl -->
<tr>
	<!-- BEGIN read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="95%">
	<a href="/filemanager/fileview/{file_id}/">{original_file_name}</a><br />
	</td>
	<td class="{td_class}" width="1%">
	{file_size}&nbsp;{file_unit}
	</td>
	<td class="{td_class}" width="1%">
	<a href="/filemanager/download/{file_id}/{original_file_name}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-dl','','/images/downloadminimrk.gif',1)"><img name="ezf{file_id}-dl" border="0" src="/images/downloadmini.gif" width="16" height="16" align="top"></a>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/filemanager/edit/{file_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{file_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezf{file_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a><br />
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
	<!-- END write_tpl -->
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_list_tpl -->

<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<input class="stdbutton" type="submit" name="NewFile" value="{intl-new_file}">
	</td>
    <td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NewFolder" value="{intl-new_folder}">
	<input type="hidden" name="FolderID" value="{main_folder_id}">
	</td>
	<td>&nbsp;</td>
    <td>
	<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}">
	</td>
</tr>
</table>
</form>
