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

<div onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif')"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Jobbmarked</span> | {intl-headline_edit}</div></td>
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

<!-- BEGIN current_type_tpl -->
<form method="post" action="/cv/cv/{action_value}/{current_id}/">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td colspan="2">
	<p class="boxtext">{intl-th_cv_for}:</p>
    {person_first_name} {person_last_name}<br/><br/>
	</td>
</tr>
<!-- BEGIN edit_tpl -->
<tr>
    <td>
	<p class="boxtext">{intl-th_created}:</p>
    <div class="small">{current_created}</div>
	</td>
    <td>
	<p class="boxtext">{intl-th_updated}:</p>
    <div class="small">{current_updated}</div>
	</td>
<!-- END edit_tpl -->
    <td>
	<p class="boxtext">{intl-th_valid_until}:</p>
	<div class="small">{current_valid_until}<div
	</td>
</tr>
</table>

<p>{intl-th_valid_until_message1}. {intl-th_valid_until_message2}.</p>
<p>{intl-th_valid_until_message3} {current_valid_for} {intl-th_valid_until_message4}. {intl-th_valid_until_message5}</p>
<p>{intl-th_valid_until_message6}.</p>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-th_nationality}:</p>

<select size="10" name="NationalityID">

<!-- BEGIN nation_tpl -->
<option {country_selected} value="{nation_id}">{nation_name}</option>
<!-- END nation_tpl -->

</select>

<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-th_sex}: </p>
    <select name="Sex">
        <!-- BEGIN sex_option_tpl -->
            <option {selected} value="{value}">{name}</option>
        <!-- END sex_option_tpl -->
    </select>
    </td>
    <td>
    <p class="boxtext">{intl-th_marital_status}: </p>
    <select name="MaritalStatus">
        <!-- BEGIN marital_option_tpl -->
            <option {selected} value="{value}">{name}</option>
        <!-- END marital_option_tpl -->
    </select>
    </td>
    <td>
    <p class="boxtext">{intl-th_children}: </p>
    <input type="text" size="2" name="Children" value="{current_children}" />
    </td>
</tr>
</table>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-th_work_status}: </p>
    <select name="WorkStatus">
    	<!-- BEGIN work_option_tpl -->
            <option {selected} value="{value}">{name}</option>
        <!-- END work_option_tpl -->
    </select>
    </td>
    <td>
    <p class="boxtext">{intl-th_army_status}: </p>
    <select name="ArmyStatus">
        <!-- BEGIN army_option_tpl -->
            <option {selected} value="{value}">{name}</option>
        <!-- END army_option_tpl -->
    </select>
    </td>
</tr>
</table>

<p class="boxtext">{intl-th_comment}:</p>
<textarea rows="5" cols="40" name="Comment" wrap="soft">{current_comment}</textarea>

<input type="hidden" name="CVID" value="{current_id}"><br />
<input type="hidden" name="PersonID" value="{person_id}"><br />



<!-- BEGIN experience_info_tpl -->
<h2>{intl-th_experience_list}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}:</th>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_experience_position}:</th>
    <th>{intl-th_experience_employer}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN experience_item_tpl -->
<tr class="{theme-type_class}">
    <td>{experience_id}&nbsp;</td>
    <td class="small">{experience_start}&nbsp;</td>
    <td class="small">{experience_end}&nbsp;</td>
    <td>{experience_position}&nbsp;</td>
    <td>{experience_employer}&nbsp;</td>
    <td width="1%"><a href="/cv/experience/edit/{experience_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezexp{experience_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezexp{experience_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/experience/delete/{experience_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezexp{experience_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezexp{experience_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END experience_item_tpl -->
</table>
<!-- END experience_info_tpl -->



<!-- BEGIN education_info_tpl -->
<h2>{intl-th_education_list}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}:</th>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_education_institution}:</th>
    <th>{intl-th_education_direction}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN education_item_tpl -->
<tr class="{theme-type_class}">
    <td>{education_id}&nbsp;</td>
    <td class="small">{education_start}&nbsp;</td>
    <td class="small">{education_end}&nbsp;</td>
    <td>{education_institution}&nbsp;</td>
    <td>{education_direction}&nbsp;</td>
    <td width="1%"><a href="/cv/education/edit/{education_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezedu{education_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezedu{education_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/education/delete/{education_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezedu{education_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezedu{education_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END education_item_tpl -->
</table>
<!-- END education_info_tpl -->



<!-- BEGIN extracurricular_info_tpl -->
<h2>{intl-th_extracurricular_list}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}:</th>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_extracurricular_organization}:</th>
    <th>{intl-th_extracurricular_position}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN extracurricular_item_tpl -->
<tr class="{theme-type_class}">
    <td>{extracurricular_id}&nbsp;</td>
    <td class="small">{extracurricular_start}&nbsp;</td>
    <td class="small">{extracurricular_end}&nbsp;</td>
    <td>{extracurricular_organization}&nbsp;</td>
    <td>{extracurricular_position}&nbsp;</td>
    <td width="1%"><a href="/cv/extracurricular/edit/{extracurricular_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezext{extracurricular_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezext{extracurricular_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/extracurricular/delete/{extracurricular_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezext{extracurricular_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezext{extracurricular_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END extracurricular_item_tpl -->
</table>
<!-- END extracurricular_info_tpl -->

<!-- BEGIN certificate_info_tpl -->
<h2>{intl-th_certificate_list}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}:</th>
    <th>{intl-th_start}:</th>
    <th>{intl-th_end}:</th>
    <th>{intl-th_certificate_institution}:</th>
    <th>{intl-th_certificate_category}:</th>
    <th>{intl-th_certificate_type}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN certificate_item_tpl -->
<tr class="{theme-type_class}">
    <td>{certificate_id}&nbsp;</td>
    <td class="small">{certificate_start}&nbsp;</td>
    <td class="small">{certificate_end}&nbsp;</td>
    <td>{certificate_institution}&nbsp;</td>
    <td>{certificate_category}&nbsp;</td>
    <td>{certificate_type}&nbsp;</td>
    <td width="1%"><a href="/cv/certificate/edit/{certificate_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvce{certificate_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcvce{certificate_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/certificate/delete/{certificate_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvce{certificate_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezcvce{certificate_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END certificate_item_tpl -->
</table>
<!-- END certificate_info_tpl -->


<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="path">
	{intl-add}:
	</td>
	<td>&nbsp;&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="EducationAdd" value="{intl-button_add_education}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="ExperienceAdd" value="{intl-button_add_experience}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="ExtracurricularAdd" value="{intl-button_add_extracurricular}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="CertificateAdd" value="{intl-button_add_certificate}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="ok" value="{intl-button_ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/cv/cv/list/{current_id}/">
	<input class="okbutton" type="submit" name="back" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>
<!-- END current_type_tpl -->

