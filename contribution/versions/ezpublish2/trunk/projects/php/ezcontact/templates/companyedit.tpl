<script language="JavaScript">

<!--
   function Update( number, phoneID, phoneTypeID )
   {
      document.CompanyEdit.PhoneNumber.value = number;
      document.CompanyEdit.PhoneID.value = phoneID;
      document.CompanyEdit.PhoneType.selectedIndex = phoneTypeID;
      document.CompanyEdit.PhoneAction.value = 'UpdatePhone';
   }
//-->

</script>

<h1>{message}</h1>

<form method="post" name="CompanyEdit" action="index.php4?page={document_root}companyedit.php4">
Kontakt firma type:
<br>
<select name="CompanyType">
{company_type}
</select>
<br>
Firmanavn:<br>
<input type="text" name="CompanyName" value="{company_name}"><br>

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

Telefon:<br>
<select name="PhoneType">
{phone_type}
</select>

<input type="text" name="PhoneNumber" value="{phone_edit_number}">
<input type="hidden" name="PhoneID" value="{phone_edit_id}">
<input type="hidden" name="PhoneAction" value="{phone_action}">
<input type="submit" value="{phone_action_value}">
<br>

{phone_list}
<br>

Kommentar:<br>
<textarea rows="5" name="Comment">{comment}</textarea><br>


<input type="hidden" name="Insert" value="TRUE">
<input type="hidden" name="CID" value="{company_id}">

<input type="hidden" name="Action" value="{edit_mode}">
<input type="submit" value="{submit_text}">

</form>
