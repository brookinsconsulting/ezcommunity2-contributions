<h1>{message}</h1>

<form method="post" action="index.php4?page={document_root}personedit.php4">
Kontakt person type:
<br>
<select name="PersonType">
{person_type}
</select>
<br>
Ansatt i firma:
<br>
<select name="CompanyID">
{company_type}
</select>
<br>
Fornavn:<br>
<input type="text" name="FirstName" value="{first_name}"><br>
Etternavn:<br>
<input type="text" name="LastName" value="{last_name}"><br>

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

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="PID" value="{person_id}">
<input type="submit" value="{submit_text}">

</form>
