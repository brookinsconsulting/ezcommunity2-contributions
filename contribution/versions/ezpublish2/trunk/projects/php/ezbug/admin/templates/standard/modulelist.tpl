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

<div onLoad="MM_preloadImages('/eztodo/images/redigerminimrk.gif','/eztodo/images/slettminimrk.gif')"></div>

<form action="/bug/module/new/">

<h1>{intl-headline}</h1>

<!-- BEGIN path_tpl -->

<hr noshade size="4" />

<img src="/ezarticle/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/bug/module/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/ezarticle/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/bug/module/list/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/module/list/{module_id}">{module_name}</a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/module/edit/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{module_id}-red','','/images/redigerminimrk.gif',1)"><img name="pt{module_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/bug/module/delete/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('pt{module_id}-slett','','/images/slettminimrk.gif',1)"><img name="pt{module_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END module_item_tpl -->
</table>

<hr noshade size="4" />

<input class="okbutton" type="submit" value="{intl-newmodule}">

</form>
