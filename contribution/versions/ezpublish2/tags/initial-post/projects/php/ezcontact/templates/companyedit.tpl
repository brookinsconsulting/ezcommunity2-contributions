<h1>{message}</h1>

<form method="post" action="index.php4?page=companyedit.php4">
Kontakt firma type:
<br>
<select name="CompanyType">
{company_type}
</select>
<br>
Firmanavn:<br>
<input type="text" name="CompanyName" value="{first_name}"><br>

Adresse type:
<br>
<select name="AddressType">
{address_type}
</select>
<br>

Adresse:<br>
<input type="text" name="Street1" value="{street_1}"><br>
<input type="text" name="Street2" value="{street_2}"><br>
Postnummer:<br>
<input type="text" name="Zip" value="{zip_code}"><br>

Kommentar:<br>
<textarea rows="5" name="Comment">{comment}</textarea><br>


<input type="hidden" name="Insert" value="TRUE">

<input type="hidden" name="EditMode" value="{edit_mode}">
<input type="submit" value="{submit_text}">

</form>
