<form method="post" action="{www_dir}{index}/contact/package/{action}/{item_id}" enctype="multipart/form-data">

<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" value="{package_name}" name="Name" class="box" />
<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box">{package_description}</textarea>

<p class="boxtext">{intl-groups}:</p>
<select name="GroupArray[]" size="3" multiple>
<option value="0" {all_selected}>{intl-all}</option>
<!-- BEGIN group_item_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END group_item_tpl -->
</select>

<br />
<input type="submit" value="{intl-ok}" name="OK" />
<input type="submit" value="{intl-cancel}" name="Cancel" />
</form>