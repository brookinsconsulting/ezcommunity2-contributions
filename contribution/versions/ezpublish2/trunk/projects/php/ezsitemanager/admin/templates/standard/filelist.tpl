<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-files}</h1>
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/sitemanager/file/list/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

</ul>
<hr noshade size="4"/>

<table width="100%">
<!-- BEGIN file_tpl -->
<tr>
	<td>
	{file_name}  
	</td>
	<td>
	<a href="{www_dir}{index}/sitemanager/file/edit/{file_name}">{intl-edit}</a> 
	</td>
	<td align="right">
	<input type="checkbox" name="FileDeleteArray[]" value="{file_name}" />
	<td>
</tr>
<!-- END file_tpl -->

</table>

<table width="100%">
<!-- BEGIN image_tpl -->
<tr>
	<td>
	{file_name}  
	</td>
	<td align="right">
	<input type="checkbox" name="ImageDeleteArray[]" value="{file_name}" />
	<td>
</tr>
<!-- END image_tpl -->
</table>


<hr noshade size="4"/>

<table class="bglight" width="100%">
<tr>
	<td>
	<p class="boxtext">{intl-upload_file}:</p>
	<input size="20" name="userfile" type="file" />

	</td>
	<td>
	<p class="boxtext">{intl-upload_image}:</p>
	<input size="20" name="userimage" type="file" />
	</td>
</tr>
<tr>
</tr>
	<td>
	<input type="submit" name="Upload" value="{intl-upload}" />
	<input type="submit" name="Delete" value="{intl-delete}" />
	</td>
</tr>

</table>
</form>
