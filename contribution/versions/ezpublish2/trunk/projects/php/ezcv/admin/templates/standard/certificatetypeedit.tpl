<h1>{intl-headline_edit}</h1>
<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/cv/certificatecategory/list/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatecategory/view/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<!-- BEGIN current_path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatetype/view/{parent_id}">{intl-current_edit}</a>
<!-- END current_path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<form method="post" action="/cv/certificatetype/{action_value}/{current_id}/" enctype="multipart/form-data">
<p class="boxtext">{intl-th_certificate_name}:</p>
<input type="text" size="40" name="CertificateName" value="{current_name}">

<p class="boxtext">{intl-th_category_parent_name}:</p>
<select size="10" name="ParentID">

<option {root_selected} value="0">{intl-root_category}</option>
<!-- BEGIN parent_item_tpl -->
<option {selected} value="{select_parent_id}">{select_parent_level}{select_parent_name}</option>
<!-- END parent_item_tpl -->

</select>

<p class="boxtext">{intl-th_certificate_description}:</p>
<textarea rows="5" cols="40" name="Description" wrap="soft">{current_description}</textarea>
<input type="hidden" name="TypeID" value="{current_id}">
<input type="hidden" name="OldParentID" value="{parent_id}">
<br /><br />


<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="ok" value="{intl-button_ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/cv/certificatetype/list/{parent_id}/">
	<input class="okbutton" type="submit" name="back" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>
<!-- END current_type_tpl -->
