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

<div onLoad="MM_preloadImages('../ezforum/images/redigerminimrk.gif','../ezforum/images/slettminimrk.gif')"></div>

<h1>{intl-headline}</h1>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
   <tr>
     <td><p><b>{intl-name}</b></p></td>
     <td><p><b>{intl-description}</b></p></td>
     <td colspan="3">&nbsp;</td>
   </tr>

<!-- BEGIN forum_item_tpl -->
   <tr bgcolor="{color}">
     <td class={td_class}><a href="/forum/messagelist/{category_id}/{forum_id}/">{forum_name}</a></td>
     <td class={td_class}>{forum_description}&nbsp;</td>
     <td width="120" align="right" class={td_class}>
	 <a href="/forum/forumedit/edit/{category_id}/{forum_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{forum_id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="eff{forum_id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
     &nbsp;&nbsp;&nbsp;&nbsp;
	 <a href="/forum/forumedit/delete/{category_id}/{forum_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{forum_id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="eff{forum_id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
	 &nbsp;&nbsp;
<!-- END forum_item_tpl -->

</table>
<br>
<a href="index.php?page={docroot}/admin/category.php">[Tilbake]</a>