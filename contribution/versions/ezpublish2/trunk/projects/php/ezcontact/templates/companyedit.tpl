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

	function MM_swapImgRestore() 
	{
		var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
	}

	function MM_preloadImages() 
	{
		var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
	}

	function MM_findObj(n, d) 
	{
		var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
	}

	function MM_swapImage() 
	{
		var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
		if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
	}

//-->
</script>

<h2>{message}</h2>

<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr>
	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Firmainformasjon</b></font>
	</td>

	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Kommentar</b></font>	
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
&nbsp;&nbsp;<textarea rows="5" name="Comment">{company_comment}</textarea><br>


<input type="hidden" name="Insert" value="TRUE">
<input type="hidden" name="CID" value="{company_id}">

<input type="hidden" name="Action" value="{edit_mode}">
<br>
&nbsp;&nbsp;<input type="submit" value="{submit_text}">

</form>

       </td>
<tr>
</tr>
<tr>
	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Registrer ny adresse</b></font>	
	</td>

	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Registrerte adresser</b></font>	
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
<td bgcolor="#f0f0f0">
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
	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Registrer nytt kontaktmedium</b></font>	
	</td>

	<td bgcolor="#3c3c3c">
	<font color="#ffffff"><b>&nbsp;&nbsp;Registrerte kontaktmedier</b></font>	
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
<td bgcolor="#f0f0f0">
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