<h1>{intl-file_view}</h1>

<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="/filemanager/list/0/">{intl-file_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="/filemanager/list/{folder_id}/">{folder_name}</a>
<!-- END path_item_tpl -->

<br />


<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-file_name}:</p>
    {file_name}

    <p class="boxtext">{intl-file_description}:</p>
    {file_description}
    
    <p class="boxtext">{intl-file_owner}:</p>
    {file_owner}

    <p class="boxtext">{intl-file_size}:</p>
    {file_size}{size_unit}

</tr>
</table>
<!-- END view_tpl -->
<br />

<form method="post" action="/filemanager/edit/{file_id}" enctype="multipart/form-data">

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<!-- BEGIN delete_tpl -->
	<td>
	<input type="submit" name="Delete" value="{intl-delete}">
	</td>
	<!-- END delete_tpl -->
	<!-- BEGIN edit_tpl -->
	<td>&nbsp;</td>
	<td>
	<input type="submit" name="Edit" value="{intl-edit}">
	</td>
	<!-- END edit_tpl -->
	<!-- BEGIN download_tpl -->
	<td>&nbsp;</td>
	<td>
	<input type="submit" name="Download" value="{intl-download}">
	<input type="hidden" name="FileName" value="{file_name}">
	</td>
	<!-- END download_tpl -->

</tr>
</table>
</form>


