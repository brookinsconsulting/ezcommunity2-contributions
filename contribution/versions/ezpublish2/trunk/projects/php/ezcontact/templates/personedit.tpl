
<script language="JavaScript">

<!--
   function Update( number, phoneID, phoneTypeID )
   {
      document.PersonEdit.PhoneNumber.value = number;
      document.PersonEdit.PhoneID.value = phoneID;
      document.PersonEdit.PhoneType.selectedIndex = phoneTypeID;
      document.PersonEdit.PhoneAction.value = 'UpdatePhone';
   }
//-->

</script>

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



<table  border="0">
<tr>
	<td bgcolor="#eeeeee">
Telefon:<br>
<select name="PhoneType">
{phone_type}
</select>

<input type="text" name="PhoneNumber" value="{phone_edit_number}">
<input type="hidden" name="PhoneID" value="{phone_edit_id}">
<input type="hidden" name="PhoneAction" value="{phone_action}">
<input type="{phone_action_type}" value="{phone_action_value}">
<br>
	<center>
	<table width="80%" cellspacing="0" cellpadding="3" border="0">
	{phone_list}
	</table>
	</center>
	</td>	
</tr>
</table>

Kommentar:<br>
<textarea rows="5" name="Comment">{comment}</textarea><br>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="PID" value="{person_id}">


<input type="submit" value="{submit_text}">

</form>
