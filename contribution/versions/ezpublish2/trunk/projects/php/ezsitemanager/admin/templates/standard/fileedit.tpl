<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-edit_file} - {file_name}</h1>
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/sitemanager/file/edit/{file_name}" >

<textarea class="box" name="Contents" cols="40" rows="25" wrap="soft">{file_contents}</textarea>

<br />
<input type="submit" name="Store" value="{intl-store}" />

</form>
