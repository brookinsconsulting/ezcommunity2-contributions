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

<table width="100%">
<tr>
	<td valign="top" bgcolor="#ddeedd">
<form method="post" name="CompanyEdit" action="index.php4?page={document_root}companyedit.php4">
Kontakt firma type:
<br>
<select name="CompanyType">
{company_type}
</select>
<br>
Firmanavn:<br>
<input type="text" name="CompanyName" value="{company_name}"><br>


Kommentar:<br>
<textarea rows="5" name="Comment">{company_comment}</textarea><br>


<input type="hidden" name="Insert" value="TRUE">
<input type="hidden" name="CID" value="{company_id}">

<input type="hidden" name="Action" value="{edit_mode}">
<input type="submit" value="{submit_text}">

</form>

       </td>
<tr>
</tr>
       <td valign="top" bgcolor="#eeeedd">

<form method="post" name="CompanyAddressEdit" action="index.php4?page={document_root}companyedit.php4">

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

<input type="hidden" name="AddressID" value="{address_edit_id}">
<input type="hidden" name="AddressAction" value="{address_action}">
<input type="{address_action_type}" name="AddressSubmit" value="{address_action_value}">

	<center>
	<table width="80%" cellspacing="0" cellpadding="3" border="0">
	{address_list}
	</table>
	</center>

<input type="hidden" name="CID" value="{company_id}">
<input type="hidden" name="Action" value="edit">

</form>
	</td>
<tr>
</tr>
	<td valign="top" bgcolor="#eeeeee">

<form method="post" name="CompanyPhoneEdit" action="index.php4?page={document_root}companyedit.php4">

Telefon:<br>
<select name="PhoneType">
{phone_type}
</select>
<br>

<input type="text" name="PhoneNumber" value="{phone_edit_number}">
<input type="hidden" name="PhoneID" value="{phone_edit_id}">
<input type="hidden" name="PhoneAction" value="{phone_action}">
<input type="{phone_action_type}" name="PhoneSubmit" value="{phone_action_value}">
	<center>
	<table width="80%" cellspacing="0" cellpadding="3" border="0">
	{phone_list}
	</table>
	</center>

<input type="hidden" name="CID" value="{company_id}">
<input type="hidden" name="Action" value="edit">

</form>
	</td>
</tr>
</table>