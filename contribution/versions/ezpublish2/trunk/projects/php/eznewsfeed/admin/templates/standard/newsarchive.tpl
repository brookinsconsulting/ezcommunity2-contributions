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

<div onLoad="MM_preloadImages('/eznewsfeed/admin/images/redigerminimrk.gif','/eznewsfeed/admin/images/slettminimrk.gif')"></div>

<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/newsfeed/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_category_description}</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="/eznewsfeed/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/newsfeed/archive/0/">{intl-top_category}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/eznewsfeed/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/newsfeed/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</td>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/newsfeed/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/newsfeed/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','/eznewsfeed/admin/images/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="/eznewsfeed/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/newsfeed/categoryedit/delete/{category_id}/'); return false;" 
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-slett','','/eznewsfeed/admin/images/slettminimrk.gif',1)"><img name="ezac{category_id}-slett" border="0" src="/eznewsfeed/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade size="4"/>
<!-- END category_list_tpl -->


<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-news}:</th>
	<th>{intl-news_origin}:</th>
	<th>{intl-news_date}:</th>
	<th>{intl-published}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN news_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/newsfeed/news/{news_id}/">
	{news_name}
	</a>&nbsp;
	</td>
	<td class="{td_class}">
	{news_origin}&nbsp;
	</td>
	<td class="{td_class}">
	{news_date}&nbsp;
	</td>
	<td class="{td_class}">
	<!-- BEGIN news_is_published_tpl -->
	{intl-is_published}
	<!-- END news_is_published_tpl -->
	<!-- BEGIN news_not_published_tpl -->
	{intl-not_published}
	<!-- END news_not_published_tpl -->
	&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="/newsfeed/news/edit/{news_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-red','','/eznewsfeed/admin/images/redigerminimrk.gif',1)"><img name="ezaa{news_id}-red" border="0" src="/eznewsfeed/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/newsfeed/news/delete/{news_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-slett','','/eznewsfeed/admin/images/slettminimrk.gif',1)"><img name="ezaa{news_id}-slett" border="0" src="/eznewsfeed/admin/images/slettmini.gif" width="16" height="16" align="top"></a>

	</td>
</tr>
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->




