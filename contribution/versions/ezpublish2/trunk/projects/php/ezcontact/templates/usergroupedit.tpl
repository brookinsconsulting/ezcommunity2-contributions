<form method="post" action="index.php4?prePage={document_root}usergroupedit.php4">
<h1>{intl-headline}</h1>

<p>
{intl-usergroup}<br>
<input type="text" name="Name" value="{user_group_name}">
</p>

<p>
{intl-desc}<br>
<textarea name="Description" rows="5">{user_group_description}</textarea>
</p>

<p>
<b>{intl-premission}</b>
</p>

<table>
	<tr>
		<td>
			<input type="checkbox" name="PersonDelete" {person_delete_checked}>
		</td>
		<td>
			<p>{intl-deleteperson}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="CompanyDelete" {company_delete_checked}>
		</td>
		<td>
			<p>{intl-deletefirm}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="UserAdmin" {user_checked}>
		</td>
		<td>
			<p>{intl-useradmin}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="UserGroupAdmin" {user_group_checked}>
		</td>
		<td>
			<p>{intl-usergroupadmin}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="PersonTypeAdmin" {person_type_checked}>
		</td>
		<td>
			<p>{intl-persontypeadmin}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="CompanyTypeAdmin" {company_type_checked}>
		</td>
		<td>
			<p>{intl-firmtypeadmin}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="PhoneTypeAdmin" {phone_type_checked}>
		</td>
		<td>
			<p>{intl-contacttypeadmin}</p>
		</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="AddressTypeAdmin" {address_type_checked}>
		</td>
		<td>
			<p>{intl-addresstypeadmin}</p>
		</td>
	</tr>
</table>
<br>

<input type="hidden" name="UGID" value="{user_group_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>