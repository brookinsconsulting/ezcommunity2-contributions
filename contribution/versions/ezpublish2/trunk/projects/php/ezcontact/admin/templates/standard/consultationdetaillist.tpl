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

<!-- BEGIN no_consultations_item_tpl -->
<p>{intl-consultation_no_consultations}:</p>
<!-- END no_consultations_item_tpl -->

<!-- BEGIN consultation_table_item_tpl -->
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

<!-- BEGIN new_person_consultation_item_tpl -->
<form method="post" action="/contact/consultation/person/new/{person_id}">
<!-- END new_person_consultation_item_tpl -->
<!-- BEGIN new_company_consultation_item_tpl -->
<form method="post" action="/contact/consultation/company/new/{company_id}">
<!-- END new_company_consultation_item_tpl -->
<input class="okbutton" type="submit" name="New" value="{intl-new_consultation}">
</form>

<!-- END consultation_table_item_tpl -->
