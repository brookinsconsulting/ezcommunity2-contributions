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

<h1>{intl-headline}</h1>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	<a href="/user/userlist/name">{intl-name}:</a>
	</th>

	<th>
	&nbsp;
	</th>

	<th>
	&nbsp;
	</th>

</tr>
<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	{company_name}
	</td>
	<td class="{td_class}" width="1%">
	<a href="/contact/companyedit/Edit/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{company_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezuser{company_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="/contact/companyedit/Delete/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{company_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezuser{company_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	
</tr>

<!-- END company_item_tpl -->

<!-- BEGIN error_tpl -->
<tr>
	<td class="{td_class}">
	<p class="error">{error_msg}</p>
	</td>	
</tr>
<!-- END error_tpl -->

</table>


