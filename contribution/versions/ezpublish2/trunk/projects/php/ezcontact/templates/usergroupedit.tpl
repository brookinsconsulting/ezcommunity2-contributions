<form method="post" action="index.php4?page=usergroupedit.php4">
<h1>Legg til en ny brukergruppe</h1>

Brukergruppe navn:<br>
<input type="text" name="Name" value="{user_group_name}"><br>
Beskrivelse:<br>
<textarea name="Description" rows="5">{user_group_description}</textarea><br>

<h2>Rettigheter</h2>

<input type="checkbox" name="UserAdmin" {user_checked}>
Bruker administrasjon.<br>

<input type="checkbox" name="UserGroupAdmin" {user_group_checked}>
Brukergruppe administrasjon.<br>

<input type="checkbox" name="PersonTypeAdmin" {person_type_checked}>
Persontype administrasjon.<br>

<input type="checkbox" name="CompanyTypeAdmin" {company_type_checked}>
Firmatype administrasjon.<br>

<input type="checkbox" name="PhoneTypeAdmin" {phone_type_checked}>
Telefontype administrasjon.<br>

<input type="checkbox" name="AddressTypeAdmin" {address_type_checked}>
Adressetype administrasjon.<br>

<input type="hidden" name="UGID" value="{user_group_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>