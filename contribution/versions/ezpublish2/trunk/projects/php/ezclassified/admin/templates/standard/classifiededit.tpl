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

<div onLoad="MM_preloadImages('/images/redigermini-mrk.gif','/images/slettmini-mrk.gif')"></div>

<form method="post" action="/classified/{action_value}/{classified_id}/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<!-- BEGIN company_select_tpl -->
<p class="boxtext">{intl-company}:</p>
<select single size="10" name="CompanyID">
<!-- BEGIN company_item_tpl -->
<option value="{company_id}" {is_selected}>{company_name}</option>
<!-- END company_item_tpl -->
</select>
<!-- END company_select_tpl -->

<!-- BEGIN company_view_tpl -->
<h2>Firma: {company_name}</h2>
<!-- <p class="boxtext">{intl-logo}:</p> -->
<!-- BEGIN no_logo_tpl -->
<!-- <p>{intl-no_logo}</p> -->
<!-- END no_logo_tpl -->

<!-- BEGIN logo_view_tpl -->
<img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />
<!-- END logo_view_tpl -->

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
{street1}<br/>
{street2}<br />
{zip} {place}<br />
<!-- END address_item_tpl -->

<br clear="all" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
{telephone}
<!-- END phone_item_tpl -->
	</td>
	<td>
<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{fax}
<!-- END fax_item_tpl -->
	</td>
</tr>
</table>

<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
<!-- BEGIN web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
<a href="http://{web}">{web}</a>
<!-- END web_item_tpl -->
	</td>
	<td>
<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
<a href="mailto:{email}">{email}</a>
<!-- END email_item_tpl -->
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
{company_description}<br /><br />

<!-- END company_view_tpl -->

<h2>Stillingsinformasjon</h2>

<p class="boxtext">{intl-title}:</p>
<input type="text" size="20" name="Title" value="{classified_title}"/>

<p class="boxtext">{intl-category}:</p>
<select multiple size="10" name="CategoryArray[]">
<!-- BEGIN category_item_tpl -->
<option value="{category_id}" {is_selected}>{category_level}{category_name}</option>
<!-- END category_item_tpl -->
</select>

<p class="boxtext">{intl-position_type}:</p>
<select single size="10" name="PositionType">
<!-- BEGIN position_type_item_tpl -->
<option value="{position_type_id}" {is_selected}>{position_name}</option>
<!-- END position_type_item_tpl -->
</select>

<p class="boxtext">{intl-initiate_type}:</p>
<select single size="10" name="InitiateType">
<!-- BEGIN initiate_type_item_tpl -->
<option value="{initiate_type_id}" {is_selected}>{initiate_name}</option>
<!-- END initiate_type_item_tpl -->
</select>
<br />

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{classified_description}</textarea>

<p class="boxtext">{intl-contact_persons}:</p>
<!-- <textarea cols="40" rows="8" name="ContactPerson">{classified_contact_person}</textarea> -->

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th>{intl-person_title}:</th>
	<th>{intl-email}:</th>
	<th>{intl-telephone}:</th>
	<th>{intl-fax}:</th>
</tr>
<!-- BEGIN contact_person_item_tpl -->
<tr>
	<td class="{td_class}" -->
	<a href=/contact/person/view/{contact_person_id}>{contact_person_name}</a>
	</td>

	<td class="{td_class}" -->
	{contact_person_title}
	</td>

	<td class="{td_class}" -->
	{contact_person_mail}
	</td>

	<td class="{td_class}" -->
	{contact_person_phone}
	</td>

	<td class="{td_class}" -->
	{contact_person_fax}
	</td>

	<td class="{td_class}" width="1%">
	<a href="/classified/person/edit/{classified_id}/{contact_person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{contact_person_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezuser{contact_person_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="/classified/person/remove/{classified_id}/{contact_person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{contact_person_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezuser{contact_person_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END contact_person_item_tpl -->
<!-- BEGIN no_contact_person_item_tpl -->
{intl-no_persons}
<!-- END no_contact_person_item_tpl -->
</table>


<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-pay}:</p>
<!-- BEGIN classified_pay_edit_tpl -->
	<input type="text" size="20" name="Pay" value="{classified_pay}"/>
<!-- END classified_pay_edit_tpl -->
<!-- BEGIN classified_pay_edit_def_tpl -->
	<input type="text" size="20" name="Pay" value="{intl-pay_default}"/>
<!-- END classified_pay_edit_def_tpl -->
	<br /><br />
	</td>
	<td>
	<p class="boxtext">{intl-duration}:</p>
	<input type="text" size="20" name="Duration" value="{classified_duration}"/>
	<br /><br />
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-worktime}:</p>
	<input type="text" size="20" name="WorkTime" value="{classified_worktime}"/>
	</td>
	<td>
	<p class="boxtext">{intl-workplace}:</p>
	<input type="text" size="20" name="WorkPlace" value="{classified_workplace}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-validUntil}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="small">
	År:<br /> 
	<input type="text" size="5" name="Year" value="{classified_year}"/>&nbsp;&nbsp;
	</td>
	<td class="small">
	Måned:<br />
	<input type="text" size="3" name="Month" value="{classified_month}"/>&nbsp;&nbsp;
	</td>
	<td class="small">
	Dag:<br />
	<input type="text" size="3" name="Day" value="{classified_day}"/>&nbsp;&nbsp;
	</td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-duedate}:</p>
	<input type="text" size="20" name="DueDate" value="{classified_duedate}"/>
	<br /><br />
	</td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-reference}:</p>
	<input type="text" size="20" name="Reference" value="{classified_reference}"/>
	<br /><br />
	</td>
</tr>
</table>
<br /><br />

<input type="hidden" value="{classified_id}" name="PositionID">
<!-- <input type="hidden" value="{company_id}" name="CompanyID"> -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}">
<!-- BEGIN delete_button_tpl -->
<input class="okbutton" type="submit" Name="Delete" value="{intl-delete}">
<!-- END delete_button_tpl -->
</form>