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
<h1>{intl-consultation_list_headline}</h1>
<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_companies_item_tpl -->
<p>{intl-consultation_no_companies}:</p>
<!-- END no_companies_item_tpl -->

<!-- BEGIN company_table_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-company_name}:</th>
	<th>{intl-consultation_count}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN company_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="/contact/consultation/company/list/{company_id}">{company_name}&nbsp;</a>
	</td>

	<td width="1%">
        {consultation_count}
	</td>

	<td width="1%">
	<a href="/contact/consultation/company/new/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcc{company_id}-slett','','/images/redigerminimrk.gif',1)"><img name="ezcc{company_id}-slett" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>	

	<td width="1%">
	<a href="/contact/consultation/company/delete/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{company_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{company_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END company_table_item_tpl -->

<!-- BEGIN no_persons_item_tpl -->
<p>{intl-consultation_no_persons}:</p>
<!-- END no_persons_item_tpl -->

<!-- BEGIN person_table_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th>{intl-consultation_count}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="/contact/consultation/person/list/{person_id}">{person_lastname}, {person_firstname}&nbsp;</a>
	</td>

	<td width="1%">
        {consultation_count}
	</td>

	<td width="1%">
	<a href="/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezpc{person_id}-slett','','/images/redigerminimrk.gif',1)"><img name="ezpc{person_id}-slett" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>	

	<td width="1%">
	<a href="/contact/consultation/person/delete/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezp{person_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezp{person_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END person_item_tpl -->
</table>
<!-- END person_table_item_tpl -->

<form method="post" action="/contact/consultation/new">
<input class="okbutton" type="submit" value="{intl-new_consultation}">
</form>
