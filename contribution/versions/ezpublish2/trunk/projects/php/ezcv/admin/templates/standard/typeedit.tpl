<!-- BEGIN list_page -->
<form method="post" action="{form_path}/{action_value}/{item_id}/" enctype="multipart/form-data">

<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN line_item_tpl -->
<p class="boxtext">{intl-name}:</p>
<input type="hidden" name="ItemID" value="{item_id}">
<input type="hidden" name="BackUrl" value="{back_url}">
<input type="text" size="40" name="ItemName" value="{item_name}"/><br>


<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{item_back_command}/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

<!-- END line_item_tpl -->

<!-- BEGIN no_line_item_tpl -->
<p>{intl-no_such_item}!</p>
<!-- END no_line_item_tpl -->

<!-- END list_page -->
