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

<div onLoad="MM_preloadImages('/ezbug/admin/images/redigerminimrk.gif','/ezbug/admin/images/slettminimrk.gif')"></div>

<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-bug_archive} - {current_module_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="/bug/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_module_description}</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="/ezbug/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/bug/archive/0/">{intl-top_level}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/ezbug/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/bug/archive/{module_id}/">{module_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN module_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-module}:</td>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN module_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/archive/{module_id}/">{module_name}</a>&nbsp;
	</td>

	<td class="{td_class}">
	{module_description}&nbsp;
	</td>

	<td width="1%" class="{td_class}">
	<a href="/bug/moduleedit/edit/{module_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{module_id}-red','','/ezbug/admin/images/redigerminimrk.gif',1)"><img name="ezac{module_id}-red" border="0" src="/ezbug/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/bug/moduleedit/delete/{module_id}/'); return false;" 
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{module_id}-slett','','/ezbug/admin/images/slettminimrk.gif',1)"><img name="ezac{module_id}-slett" border="0" src="/ezbug/admin/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END module_item_tpl -->
</table>
<hr noshade size="4"/>
<!-- END module_list_tpl -->


<!-- BEGIN bug_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-bug}:</th>
	<th>{intl-status}:</th>
	<th>{intl-priority}:</th>
	<th>{intl-is_closed}:</th>

	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN bug_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bug/bugpreview/{bug_id}/">
	{bug_name}&nbsp;
	</a>
	</td>

	<td class="{td_class}">
	{bug_status}&nbsp;
	</td>

	<td class="{td_class}">
	{bug_priority}&nbsp;
	</td>

	<td class="{td_class}">
	<!-- BEGIN bug_is_closed_tpl -->
	{intl-is_closed}&nbsp;
	<!-- END bug_is_closed_tpl -->

	<!-- BEGIN bug_is_open_tpl -->
	{intl-is_open}&nbsp;
	<!-- END bug_is_open_tpl -->

	</td>

	<td width="1%" class="{td_class}">
	<a href="/bug/edit/edit/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-red','','/ezbug/admin/images/redigerminimrk.gif',1)"><img name="ezaa{bug_id}-red" border="0" src="/ezbug/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="#" onClick="verify( '{intl-delete}', '/bug/bugedit/delete/{bug_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{bug_id}-slett','','/ezbug/admin/images/slettminimrk.gif',1)"><img name="ezaa{bug_id}-slett" border="0" src="/ezbug/admin/images/slettmini.gif" width="16" height="16" align="top"></a>

	</td>
</tr>
<!-- END bug_item_tpl -->

</table>
<!-- END bug_list_tpl -->
