<!-- BEGIN country_edit_page -->
<form method="post" action="{www_dir}{index}{form_path}/{action_value}/{item_id}/" enctype="multipart/form-data">

<!-- BEGIN country_edit_tpl -->
<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<input type="hidden" name="ItemID" value="{item_id}">
<input type="hidden" name="BackUrl" value="{back_url}">

<table>
<tr>
<tr>
    <td>
	<p class="boxtext">{intl-parent}:</p>
	<select name="ItemParentID">
	<option value="0" {selected}>{intl-none}</option>
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
    </td>
</tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="box" size="40" name="ItemName" value="{item_name}"/><br>
	</td>
	<td>
	<p class="boxtext">{intl-iso}:</p>
	<input type="text" size="2" name="ItemISO" value="{item_iso}"/><br>
	</td>
	<td>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</td>
	<td>
	<p class="boxtext">{intl-has_vat}:</p>
	<input type="checkbox" name="ItemHasVAT" {item_has_vat} /><br>
	</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}{item_back_command}/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>
<!-- END country_edit_tpl -->

<!-- END country_edit_page -->
