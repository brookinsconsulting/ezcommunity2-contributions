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

<div onLoad="MM_preloadImages('../eztrade/images/redigerminimrk.gif','../eztrade/images/slettminimrk.gif')"></div>

<h1>{intl-optionlist}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>Opsjon: </th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN option_tpl -->
<tr>
	<td class="{td_class}">
	{option_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/optionedit/edit/{option_id}/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{option_id}-red','','/eztrade/images/redigerminimrk.gif',1)"><img name="ezto{option_id}-red" border="0" src="/eztrade/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/trade/productedit/optionedit/delete/{option_id}/{product_id}/'); return false;" 
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{option_id}-slett','','/eztrade/images/slettminimrk.gif',1)"><img name="ezto{option_id}-slett" border="0" src="/eztrade/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END option_tpl -->

</table>

<br/>


<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<form action="/trade/productedit/optionedit/new/{product_id}/" method="post">
<input class="stdbutton" type="submit" value="{intl-newoption}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
</form>
<form action="/trade/productedit/edit/{product_id}/" method="post">
<input class="okbutton" type="submit" value="{intl-back}" />
</form>
	</td>
</tr>
</table>

