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

<!-- orderlist.tpl --> 
<!-- $Id: orderlist.tpl,v 1.6 2000/11/01 09:11:12 ce-cvs Exp $ -->

<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-head_line}</h1>
	</td>
	<td align="right">
	<form action="/trade/orderlist/" method="post">
	<input type="text" name="QueryText" />
	<input type="submit" value="{intl-search}">
	</form>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN order_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-nr}:</th>
	<th>{intl-created}:</th>
	<th>{intl-modified}:</th>
	<th>{intl-status}:</th>
	<td align="right"><b>{intl-price}:</b></td>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">
	{order_id}
	</td>
	<td class="{td_class}">
	<span class="small">{order_date}</span>
	</td>
	<td class="{td_class}">
	<span class="small">{altered_date}</span>
	</td>
	<td class="{td_class}">
	{order_status}
	</td>
	<td class="{td_class}" align="right">
	{order_price}
	</td>
	<td width="1%" class="{td_class}">
	<a href="/trade/orderedit/{order_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{order_id}-red','','/eztrade/images/redigerminimrk.gif',1)"><img name="ezto{order_id}-red" border="0" src="/eztrade/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/trade/orderedit/{order_id}/delete/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{order_id}-slett','','/eztrade/images/slettminimrk.gif',1)"><img name="ezto{order_id}-slett" border="0" src="/eztrade/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END order_item_tpl -->

</table>
<!-- END order_item_list_tpl -->


<!-- BEGIN previous_tpl -->
<a href="/trade/orderlist/?Offset={prev_offset}&URLQueryString={url_query_string}">
prev
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="/trade/orderlist/?Offset={next_offset}&URLQueryString={url_query_string}">
next
</a>
<!-- END next_tpl -->
