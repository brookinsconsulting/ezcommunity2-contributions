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

<h1>Produktkatalog</h1>

<hr noshade="noshade" size="4" />

<img src="/ezforum/images/path-arrow.gif" height="10" width="15" border="0">
<a class="path" href="/trade/categorylist/parent/0/">Hovedkategori</a>

<!-- BEGIN path_item_tpl -->
<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/trade/categorylist/parent/{category_id}/">{category_name}</a>

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>Kategori:</th>
	<th>Beskrivelse:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/categorylist/parent/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-red','','/eztrade/images/redigerminimrk.gif',1)"><img name="eztc{category_id}-red" border="0" src="/eztrade/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/categoryedit/delete/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-slett','','/eztrade/images/slettminimrk.gif',1)"><img name="eztc{category_id}-slett" border="0" src="/eztrade/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END category_item_tpl -->

</table>
<hr noshade="noshade" size="4" />
<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>Produkt:</th>
	<td class="path" align="right">Pris:</td>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN product_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/productedit/productpreview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','/eztrade/images/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="/eztrade/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/productedit/delete/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-slett','','/eztrade/images/slettminimrk.gif',1)"><img name="ezti{product_id}-slett" border="0" src="/eztrade/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END product_item_tpl -->
</table>
<!-- END product_list_tpl -->




