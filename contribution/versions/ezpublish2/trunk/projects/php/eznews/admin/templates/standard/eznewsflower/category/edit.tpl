<form method="post" action="/{this_path}/{this_id}?edit+this">

<h1>{intl-edit_category}: {this_name}</h1>

<hr noshade size="4" />

<p class="boxtext">{intl-public_text}</p>
<textarea name="PublicText" rows="10" cols="40">{this_public_description}</textarea>

<input type="hidden" name="ItemID" value="{this_id}">
<input type="hidden" name="Name" value="{this_name}">

<br /><br />

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" name="form_preview" type="submit" value="{intl-preview}">
	</form>
	</td>
	<td>
	&nbsp;
	</td>

<!-- BEGIN go_to_parent_template -->

	<td>
	<form action="/{this_path}/{this_canonical_parent_id}">
	<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>

<!-- END go_to_parent_template -->

<!-- BEGIN go_to_self_template -->

	<td>
	<form method="post" action="/{this_path}/{this_id}">
	<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>

<!-- END go_to_self_template -->
