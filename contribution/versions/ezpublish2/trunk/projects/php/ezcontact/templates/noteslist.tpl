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

   function UpdateConsult( consultTitle, consultID, consultBody )
   {
      document.PersonConsultEdit.ConsultTitle.value = consultTitle;
      document.PersonConsultEdit.ConsultID.value = consultID;
      document.PersonConsultEdit.ConsultBody.value = consultBody;
      document.PersonConsultEdit.ConsultAction.value = 'UpdateConsult';
      document.PersonConsultEdit.ConsultSubmit.value = 'Lagre';

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


<h1>Notater for: {user} </h1>

<table width="100%" cellspacing="0" cellpadding="2" border="0">
{notes}
</table>
<br>
<a href="index.php4?page={document_root}noteedit.php4">[nytt notat]</a>
