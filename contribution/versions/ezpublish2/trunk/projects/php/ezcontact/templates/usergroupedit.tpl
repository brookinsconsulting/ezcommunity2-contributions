<form method="post" action="index.php4?prePage={document_root}usergroupedit.php4">
<h1>{head_line}</h1>

<p>
Brukergruppe navn:<br>
<input type="text" name="Name" value="{user_group_name}">
</p>

<p>
Beskrivelse:<br>
<textarea name="Description" rows="5">{user_group_description}</textarea>
</p>

<p>
<b>Rettigheter:</b>
</p>

<table>
	<tr>
		<td>
			<input type="checkbox" name="PersonDelete" {person_delete_checked}>
		</td>
		<td>
			<p>Sletting av personer.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="CompanyDelete" {company_delete_checked}>
		</td>
		<td>
			<p>Sletting av firma.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="UserAdmin" {user_checked}>
		</td>
		<td>
			<p>Brukeradministrasjon.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="UserGroupAdmin" {user_group_checked}>
		</td>
		<td>
			<p>Brukergruppeadministrasjon.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="PersonTypeAdmin" {person_type_checked}>
		</td>
		<td>
			<p>Persontypeadministrasjon.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="CompanyTypeAdmin" {company_type_checked}>
		</td>
		<td>
			<p>Firmatypeadministrasjon.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="PhoneTypeAdmin" {phone_type_checked}>
		</td>
		<td>
			<p>Kontaktmediumadministrasjon.</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="AddressTypeAdmin" {address_type_checked}>
		</td>
		<td>
			<p>Adressetypeadministrasjon.</p>
		</td>
	</tr>
</table>
<br>

<input type="hidden" name="UGID" value="{user_group_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>