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

<!--
<div onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif')"></div>
-->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Jobbmarked</span> | {intl-headline_view}</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<br />

<!-- BEGIN cv_person_info -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
	<p class="boxtext">{intl-th_person_name}:</p>
	{person_last_name}, {person_first_name}
	</td>
    <td>
	<p class="boxtext">{intl-th_person_birth_date_personno}:</p>
	{person_birth_date} {person_no}
	</td>  
</tr>
<tr>
    <td colspan="2">
	<br />
	<p class="boxtext">{intl-th_person_comment}:</p>
    {person_comment}
	</td>
</tr>
</table>
<!-- END cv_person_info -->

<br />

<!-- BEGIN cv_info -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<p class="boxtext">{intl-th_sex}:</p>
    {cv_sex}
	</td>
    <td>
	<p class="boxtext">{intl-th_marital_status}:</p>
    {cv_marital_status}
	</td> 
    <td><p class="boxtext">{intl-th_children}:</p>
    {cv_children}
	</td>
</tr>
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td width="50%">
	<p class="boxtext">{intl-th_work_status}:</p>
    {cv_work_status}
	</td>
    <td>
	<p class="boxtext">{intl-th_army_status}:</p>
    {cv_army_status}
	</td>
</tr>
<!-- END cv_info -->
</table>

<!-- BEGIN address_info_tpl -->
        <h2>{intl-th_addresses}</h2>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_address_type}:</td>
                <td class="boxtext">{intl-th_address_street}:</td>
                <td>&nbsp;</td>
                <td class="boxtext">{intl-th_address_zip}:</td>
                <td class="boxtext">{intl-th_address_place}:</td>
                <td class="boxtext">{intl-th_address_country}:</td>
            </tr>
            <!-- BEGIN address_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{address_type}&nbsp;</td>
                <td>{address_street1}&nbsp;</td>
                <td>{address_street2}&nbsp;</td>
                <td>{address_zip}&nbsp;</td>
                <td>{address_place}&nbsp;</td>
                <td>{address_country}&nbsp;</td>
            </tr>
            <!-- END address_item_tpl -->
        </table>
<!-- END address_info_tpl -->

<!-- BEGIN phone_info_tpl -->
        <h2>{intl-th_phone_numbers}</h2>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_phone_type}:</td>
                <td class="boxtext">{intl-th_phone_number}:</td>
            </tr>
            <!-- BEGIN phone_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{phone_type}&nbsp;</td>
                <td>{phone_number}&nbsp;</td>
            </tr>
            <!-- END phone_item_tpl -->
        </table>
<!-- END phone_info_tpl -->

<!-- BEGIN online_info_tpl -->
        <h2>{intl-th_online_addresses}</h2>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_online_type}:</td>
                <td class="boxtext">{intl-th_online_address}:</td>
            </tr>
            <!-- BEGIN online_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{online_type}&nbsp;</td>
                <td>{online_url}&nbsp;</td>
            </tr>
            <!-- END online_item_tpl -->
        </table>
<!-- END online_info_tpl -->



<!-- BEGIN experience_items_tpl -->
<h2>{intl-th_experience_list}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_experience_employer}:</th>
    <th>{intl-th_experience_position}:</th>
<!--    <th colspan="2">&nbsp;</th> -->
</tr>

<!-- BEGIN experience_item_tpl -->
<tr class="{theme-type_class}">
    <td class="small">{item_start_period}</td>
    <td class="small">{item_end_period}</td>
    <td>{item_where}</td>
    <td>{item_what}</td>
<!--
    <td width="1%"><a href="/cv/experience/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezexp{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezexp{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/experience/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezexp{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezexp{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
-->
</tr>

<!-- END experience_item_tpl -->
</table>

<!-- END experience_items_tpl -->

<!-- BEGIN no_experience_items_tpl -->
<h2>{intl-th_experience_list}</h2>
<p>{intl-th_no_experience}</p>
<!-- END no_experience_items_tpl -->



<!-- BEGIN education_items_tpl -->
<h2>{intl-th_education_list}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_education_institution}:</th>
    <th>{intl-th_education_direction}:</th>
<!--    <th colspan="2">&nbsp;</th> -->
</tr>

<!-- BEGIN education_item_tpl -->
<tr class="{theme-type_class}">
    <td class="small">{item_start_period}</td>
    <td class="small">{item_end_period}</td>
    <td>{item_where}</td>
    <td>{item_what}</td>
<!--
    <td width="1%"><a href="/cv/education/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezedu{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezedu{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/education/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezedu{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezedu{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
-->
</tr>

<!-- END education_item_tpl -->
</table>

<!-- END education_items_tpl -->

<!-- BEGIN no_education_items_tpl -->
<h2 class="boxtext">{intl-th_education_list}</h2>
<p>{intl-th_no_education}</p>
<!-- END no_education_items_tpl -->


<!-- BEGIN extracurricular_items_tpl -->
<h2>{intl-th_extracurricular_list}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_extracurricular_organization}:</th>
    <th>{intl-th_extracurricular_position}:</th>
<!--    <th colspan="2">&nbsp;</th>  -->
</tr>

<!-- BEGIN extracurricular_item_tpl -->
<tr class="{theme-type_class}">
    <td class="small">{item_start_period}</td>
    <td class="small">{item_end_period}</td>
    <td>{item_where}</td>
    <td>{item_what}</td>
<!--
    <td width="1%"><a href="/cv/extracurricular/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezext{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezext{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/extracurricular/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezext{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezext{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
-->
</tr>

<!-- END extracurricular_item_tpl -->
</table>

<!-- END extracurricular_items_tpl -->

<!-- BEGIN no_extracurricular_items_tpl -->
<h2>{intl-th_extracurricular_list}</h2>
<p>{intl-th_no_extracurricular}</p>
<!-- END no_extracurricular_items_tpl -->


<!-- BEGIN certificate_items_tpl -->
<h2>{intl-th_certificate_list}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_certificate_start}:</th>
    <th>{intl-th_certificate_end}:</th>
    <th>{intl-th_certificate_institution}:</th>
    <th>{intl-th_certificate_category}:</th>
    <th>{intl-th_certificate_type}:</th>
<!--    <th colspan="2">&nbsp;</th>  -->
</tr>

<!-- BEGIN certificate_item_tpl -->
<tr class="{theme-type_class}">
    <td class="small">{certificate_start}&nbsp;</td>
    <td class="small">{certificate_end}&nbsp;</td>
    <td>{certificate_institution}&nbsp;</td>
    <td>{certificate_category}&nbsp;</td>
    <td>{certificate_type}&nbsp;</td>
<!--
    <td width="1%"><a href="/cv/certificate/edit/{certificate_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcrt{certificate_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcrt{certificate_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/certificate/delete/{certificate_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcrt{certificate_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezcrt{certificate_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
-->
</tr>

<!-- END certificate_item_tpl -->
</table>

<!-- END certificate_items_tpl -->

<!-- BEGIN no_certificate_items_tpl -->
<h2>{intl-th_certificate_list}</h2>
<p>{intl-th_no_certificate}</p>
<!-- END no_certificate_items_tpl -->





<!-- BEGIN cv_items_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}</th>
    <th>{intl-th_person_name}</th>
    <th>{intl-th_current_created}</th>
    <th>{intl-th_current_valid_until}</th>
<!--    <th colspan="2">&nbsp;</th>   -->
</tr>
<!-- BEGIN cv_item_tpl -->
<tr class="{theme-type_class}">
    <td>{item_id}</td>
    <td>{person_last_name}, {person_first_name}</td>
    <td>{item_created}</td>
    <td>{item_valid_until}</td>
<!--
    <td width="1%"><a href="/cv/cv/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezuser{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/cv/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezuser{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
-->
</tr>
<!-- END cv_item_tpl -->
</table>
<!-- END cv_items_tpl -->

<!-- BEGIN cv_no_items_tpl -->
<p>{intl-th_no_cvs}</p>
<!-- END cv_no_items_tpl -->


<!-- BEGIN edit_items_tpl -->

<br />

<hr noshade="noshade" size="4" />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>

    <form method="post" action="/contact/person/edit/{person_id}">
    <input class="stdbutton" type="submit" value="{intl-button_person_edit}" name="ok" />
    </form>

	</td>
	<td>&nbsp;</td>
	<td>

    <form method="post" action="/cv/cv/edit/{cv_id}">
<!--
    <input class="stdbutton" type="submit" name="EducationAdd" value="{intl-button_add_education}" />
    <input class="stdbutton" type="submit" name="ExperienceAdd" value="{intl-button_add_experience}" />
    <input class="stdbutton" type="submit" name="ExtracurricularAdd" value="{intl-button_add_extracurricular}" />
    <input class="stdbutton" type="submit" name="CertificateAdd" value="{intl-button_add_certificate}" />
-->
	<input class="stdbutton" type="submit" value="{intl-button_cv_edit}" name="edit" />
    </form>

    </td>
</tr>
</table>
<!-- END edit_items_tpl -->

