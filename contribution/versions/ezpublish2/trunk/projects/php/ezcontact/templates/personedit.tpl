<script language="JavaScript">

<!--
   function UpdatePhone( number, phoneID, phoneTypeID )
   {
      document.CompanyPhoneEdit.PhoneNumber.value = number;
      document.CompanyPhoneEdit.PhoneID.value = phoneID;
      document.CompanyPhoneEdit.PhoneType.selectedIndex = phoneTypeID;
      document.CompanyPhoneEdit.PhoneAction.value = 'UpdatePhone';
      document.CompanyPhoneEdit.PhoneSubmit.value = 'Lagre';

   }

   function UpdateAddress( street1, street2, zip, addressID, addressTypeID )
   {
      document.CompanyAddressEdit.Street1.value = street1;
      document.CompanyAddressEdit.Street2.value = street2;
      document.CompanyAddressEdit.Zip.value = zip;
      document.CompanyAddressEdit.AddressID.value = addressID;
      document.CompanyAddressEdit.AddressType.selectedIndex = addressTypeID;
      document.CompanyAddressEdit.AddressAction.value = 'UpdateAddress';
      document.CompanyAddressEdit.AddressSubmit.value = 'Lagre';
   }

//-->

</script>

<h1>{message}</h1>

<table width="100%" border="0">
<tr>
	<td valign="top"  bgcolor="#ddeedd">

<form method="post"  name="CompanyAddressEdit" action="index.php4?page={document_root}personedit.php4">
Kontakt person type:
<br>
<select name="PersonType">
{person_type}
</select>
<br>
Ansatt i firma:'
<br>
<select name="CompanyID">
{company_type}
</select>
<br>
Fornavn:<br>
<input type="text" name="FirstName" value="{first_name}"><br>
Etternavn:<br>
<input type="text" name="LastName" value="{last_name}"><br>

Kommentar:<br>
<textarea rows="5" name="Comment">{comment}</textarea><br>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="PID" value="{person_id}">

<input type="submit" value="{submit_text}">

</form>

	</td>
<tr>
</tr>
	<td valign="top"  bgcolor="#ddeeee">

<form method="post"  name="CompanyAddressEdit" action="index.php4?page={document_root}personedit.php4">

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

<input type="hidden" name="AddressAction" value="{address_action}">
<input type="hidden" name="PID" value="{person_id}">
<input type="hidden" name="AddressID" value="{address_id}">
<input type="{address_action_type}"  name="AddressSubmit" value="{address_action_value}">
</td>
<td>

	<center>
	<table width="80%" cellspacing="0" cellpadding="3" border="0">
	{address_list}
	</table>
	</center>


<input type="hidden" name="Action" value="edit">

</form>

	</td>
<tr>
</tr>
	<td valign="top" bgcolor="#eeeeee">

<form method="post" name="CompanyPhoneEdit" action="index.php4?page={document_root}personedit.php4">

Telefon:<br>
<select name="PhoneType">
{phone_type}
</select>

<input type="text" name="PhoneNumber" value="{phone_edit_number}">
<input type="hidden" name="PhoneID" value="{phone_edit_id}">
<input type="hidden" name="PhoneAction" value="{phone_action}">
<input type="{phone_action_type}" name="PhoneSubmit" value="{phone_action_value}">
</td>
<td>

	<center>
	<table width="80%" cellspacing="0" cellpadding="3" border="0">
	{phone_list}
	</table>
	</center>

<input type="hidden" name="PID" value="{person_id}">
<input type="hidden" name="Action" value="edit">


</form>

	</td>
</tr>
</table>