
<form method="post" action="/filemanager/upload/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{int-file_uplolad}</h1>


<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-subfolder_of}:</p>

<select name="FolderID">
<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-file_name}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	
	<p class="boxtext">{intl-file_description}:</p>
	<input type="text" size="40" name="Description" value="{description_value}"/>
	
	<p class="boxtext">{intl-filefile}:</p>
	<input size="40" name="userfile" type="file" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="FileID" value="{file_id}" />
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


