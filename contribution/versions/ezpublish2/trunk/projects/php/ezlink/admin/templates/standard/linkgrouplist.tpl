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

<div onLoad="MM_preloadImages('/ezlink/images/redigerminimrk.gif','/ezlink/images/slettminimrk.gif')"></div>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<!-- BEGIN path_tpl -->


<img src="/ezarticle/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/link/group/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/ezarticle/admin/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/link/group/{group_id}/">{group_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4"/>

<!-- BEGIN group_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h2>{categories}</h2>
	</td>
</tr>
<!-- BEGIN group_item_tpl -->
<tr>
	<td bgcolor="{bg_color}">
	<a href="/link/group/{linkgroup_id}/">{linkgroup_title}</a>
	</td>

	<td width="1%" bgcolor="{bg_color}">
	<a href="/link/groupedit/edit/{linkgroup_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkgroup_id}-red','','/ezlink/images/redigerminimrk.gif',1)"><img name="ela{linkgroup_id}-red" border="0" src="/ezlink/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" bgcolor="{bg_color}">
	<a href="#" onClick="verify( '{intl-delete}', '/link/groupedit/delete/{linkgroup_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkgroup_id}-slett','','/ezlink/images/slettminimrk.gif',1)"><img name="ela{linkgroup_id}-slett" border="0" src="/ezlink/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END group_item_tpl -->
</table>
<!-- END group_list_tpl -->


<!-- BEGIN link_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<h2>{links}</h2>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/link/gotolink/addhit/{link_id}/?Url={link_url}">{link_title}</a><br />
	{link_description}
	</td>
	<td class="{td_class}" width="80" align="right">
	(Hits: {link_hits})
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-red','','/ezlink/images/redigerminimrk.gif',1)"><img name="el{link_id}-red" border="0" src="/ezlink/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" bgcolor="{bg_color}">
	<a href="#" onClick="verify( '{intl-deletelink}', '/link/linkedit/delete/{link_id}/'); return false;"
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-slett','','/ezlink/images/slettminimrk.gif',1)"><img name="el{link_id}-slett" border="0" src="/ezlink/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<!-- END link_list_tpl -->
