<SCRIPT LANGUAGE="JavaScript1.2">
<!--//

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
</SCRIPT> 
<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-view_headline}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="/contact/person/search/" method="post">
        <input type="text" name="SearchText" size="12" />       
        <input type="submit" value="{intl-search}" />
        </form> 
        </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<span class="p">{firstname} {lastname}</span>
	</td>

	<td>
	<p class="boxtext">{intl-birthday_headline}: </p>
	<span class="p">{birthdate}</span>
	</td>
</tr>
</table>

<!-- BEGIN address_item_tpl -->
<h2>{intl-addresses_headline}</h2>
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN address_line_tpl -->
	<td>
	<p class="boxtext">{address_type_name}:</p>
	<div class="p">{street1}</div>
	<div class="p">{street2}</div>
	<div class="p">{zip} {place}</div>
	<div class="p">{country}</div>
	</td>
<!-- END address_line_tpl -->
</tr>
</table>
<!-- END address_item_tpl -->

<!-- BEGIN no_address_item_tpl -->
<h2>{intl-addresses_headline}</h2>
<p>{intl-error_no_addresses}</p>
<!-- END no_address_item_tpl -->


<h2>{intl-telephone_headline}</h2>
<!-- BEGIN phone_item_tpl -->
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN phone_line_tpl -->
	<td valign="top">
	<p class="boxtext">{phone_type_name}:</p>
	{phone}
	</td>
<!-- END phone_line_tpl -->
</tr>
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<p>{intl-error_no_phones}</p>
<!-- END no_phone_item_tpl -->

<h2>{intl-online_headline}</h2>
<!-- BEGIN online_item_tpl -->
<br />
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<!-- BEGIN online_line_tpl -->
	<td>
	<p class="boxtext">{online_type_name}:</p>
	<a href="{online_prefix}{online}">{online_visual_prefix}{online}</a>
	</td>
<!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->
<!-- BEGIN no_online_item_tpl -->
<p>{intl-error_no_onlines}</p>
<!-- END no_online_item_tpl -->

<h2>{intl-description_headline}</h2>
<p>{description}</p>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-contact_person}:</p>
	<!-- BEGIN contact_person_tpl -->
	<p>{contact_lastname}, {contact_firstname}</p>
	<!-- END contact_person_tpl -->
	<!-- BEGIN no_contact_person_tpl -->
	<p>{intl-no_contact_person}</p>
	<!-- END no_contact_person_tpl -->
	</td>

	<td width="50%">
	<p class="boxtext">{intl-project_status}:</p>
	<!-- BEGIN project_status_tpl -->
	<p>{project_status}</p>
	<!-- END project_status_tpl -->
	<!-- BEGIN no_project_status_tpl -->
	<p>{intl-no_project_status}</p>
	<!-- END no_project_status_tpl -->
	</td>
</tr>
</table>

<!-- BEGIN consultation_table_item_tpl -->
<h2>{intl-consultation_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-consultation_date}:</th>
	<th>{intl-consultation_short_description}:</th>
	<th>{intl-consultation_status}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td>
        {consultation_date}
	</td>
	<td>
        <a href="/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="/contact/consultation/type/list/{consultation_status_id}">{consultation_status}</a>
	</td>

	<td width="1%">
	<a href="/contact/consultation/edit/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{consultation_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/consultation/delete/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{consultation_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END consultation_item_tpl -->
</table>

<!-- END consultation_table_item_tpl -->

<hr noshade="noshade" size="4" />
<br />

<form method="post" action="/contact/person/edit/{person_id}/">

<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">
<input type="submit" name="Delete" value="{intl-delete}" />
<input type="submit" name="Back" value="{intl-list}">
<!-- BEGIN consultation_buttons_tpl -->
<input type="submit" name="ListConsultation" value="{intl-consultation_list}">
<input type="submit" name="NewConsultation" value="{intl-consultation}">
<!-- END consultation_buttons_tpl -->
</form>
