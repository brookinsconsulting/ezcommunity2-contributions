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
	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Firmainformasjon</font>	
	</td>

	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Kommentar</font>	
	</td>
</tr>
<tr>
	<td valign="top" bgcolor="#f0f0f0">
<form method="post" name="CompanyEdit" action="index.php4?page={document_root}companyedit.php4">
<br>
&nbsp;&nbsp;Kontakttype:
<br>
&nbsp;&nbsp;<select name="CompanyType">
{company_type}
</select>
<br>
&nbsp;&nbsp;Firmanavn:<br>
&nbsp;&nbsp;<input type="text" name="CompanyName" value="{company_name}"><br><br>

&nbsp;&nbsp;<input type="submit" value="{submit_text}">
<br>
<br>
</td>

<td bgcolor="#f0f0f0">

<br>
&nbsp;&nbsp;Kommentar:<br>
&nbsp;&nbsp;<textarea rows="5" name="Comment">{company_comment}</textarea><br>


<input type="hidden" name="Insert" value="TRUE">
<input type="hidden" name="CID" value="{company_id}">

<input type="hidden" name="Action" value="{edit_mode}">

</form>

       </td>
<tr>
</tr>
<tr>
	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Registrer ny adresse</font>	
	</td>

	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Registrerte adresser</font>	
	</td>
</tr>

       <td valign="top" bgcolor="#f0f0f0">

<form method="post" name="CompanyAddressEdit" action="index.php4?page={document_root}companyedit.php4">

<br>
&nbsp;&nbsp;Adressetype:
<br>
&nbsp;&nbsp;<select name="AddressType">
{address_type} 
</select>
<br>

&nbsp;&nbsp;Adresse:<br>
&nbsp;&nbsp;<input type="text" name="Street1" value="{street_1}"><br>
&nbsp;&nbsp;<input type="text" name="Street2" value="{street_2}"><br>
&nbsp;&nbsp;Postnummer:<br>
&nbsp;&nbsp;<input type="text" name="Zip" value="{zip_code}"><br>

<input type="hidden" name="AddressID" value="{address_edit_id}">
<input type="hidden" name="AddressAction" value="{address_action}"><br>

&nbsp;&nbsp;<input type="{address_action_type}" name="AddressSubmit" value="{address_action_value}">
<br>
<br>
</td>
<td>
	<br>
	<center>
	<table width="95%" cellspacing="0" cellpadding="3" border="0">
	{address_list}
	</table>
	</center>

<input type="hidden" name="CID" value="{company_id}">
<input type="hidden" name="Action" value="edit">

</form>
	</td>
<tr>
</tr>
<tr>
	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Registrer nytt kontaktmedium</font>	
	</td>

	<td bgcolor="#888888">
	<font color="#ffffff" size="4">&nbsp;&nbsp;Registrerte kontaktmedier</font>	
	</td>
</tr>
	<td valign="top" bgcolor="#f0f0f0">

<form method="post" name="CompanyPhoneEdit" action="index.php4?page={document_root}companyedit.php4">

<br>
&nbsp;&nbsp;Kontaktmedium:<br>
&nbsp;&nbsp;<select name="PhoneType">
{phone_type}
</select>
<br>

&nbsp;&nbsp;<input type="text" name="PhoneNumber" value="{phone_edit_number}">

<input type="hidden" name="PhoneID" value="{phone_edit_id}">
<input type="hidden" name="PhoneAction" value="{phone_action}"><br><br>

&nbsp;&nbsp;<input type="{phone_action_type}" name="PhoneSubmit" value="{phone_action_value}">
<br>
<br>
</td>
<td>
	<br>
	<center>
	<table width="95%" cellspacing="0" cellpadding="3" border="0">
	{phone_list}
	</table>
	</center>

<input type="hidden" name="CID" value="{company_id}">
<input type="hidden" name="Action" value="edit">

</form>
	</td>
</tr>
</table>