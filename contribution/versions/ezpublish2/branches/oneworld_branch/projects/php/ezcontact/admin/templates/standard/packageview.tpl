<form method="post" action="{www_dir}{index}/contact/package/view/update/{item_id}" enctype="multipart/form-data">

<h1>{package_name}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-companies}:</p>

<select name="CompanyArray[]" size="3" multiple>
<!-- BEGIN group_item_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END group_item_tpl -->
</select>

<br />
<input type="submit" value="{intl-ok}" name="OK" />
<input type="submit" value="{intl-cancel}" name="Cancel" />
</form>