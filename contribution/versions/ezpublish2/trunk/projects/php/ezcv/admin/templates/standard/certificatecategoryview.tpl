<!-- BEGIN view_headline_tpl -->
<h1>{intl-headline_view}: {current_name}</h1>
<!-- END view_headline_tpl -->
<!-- BEGIN list_headline_tpl -->
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
<h1>{intl-headline_list}: {current_name}</h1>
<!-- END list_headline_tpl -->

<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/cv/certificatecategory/list/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatecategory/list/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<!-- BEGIN current_path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatecategory/view/{parent_id}">{intl-current_edit}</a>
<!-- END current_path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<p class="boxtext">{intl-th_category_institution}:</p>
{current_institution}

<!-- BEGIN parent_item_tpl -->
<!-- -->
<!-- END parent_item_tpl -->

<p class="boxtext">{intl-th_category_description}:</p>
{current_description}

<hr noshade="noshade" size="4" />

<!-- END current_type_tpl -->


<!-- BEGIN no_category_list_box_tpl -->
<h2>{intl-category_certificate_category_list}</h2>
{intl-no_certificate_categories}
<!-- END no_category_list_box_tpl -->

<!-- BEGIN category_list_box_tpl -->
<h2>{intl-category_certificate_category_list}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_category_name}:</th>
    <th>{intl-th_category_institution}:</th>
    <th>{intl-th_category_description}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr class="{theme-type_class}">
    <td><a href="/cv/certificatecategory/list/{item_id}">{item_name}&nbsp;</a></td>
    <td>{item_institution}&nbsp;</td>
    <td>{item_description}&nbsp;</td>
    <td width="1%"><a href="/cv/certificatecategory/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvct{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcvct{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/certificatecategory/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvct{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezcvct{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_box_tpl -->



<!-- BEGIN no_list_box_tpl -->
<h2>{intl-category_certificate_types_list}</h2>
{intl-no_certificate_types}
<!-- END no_list_box_tpl -->
<!-- BEGIN list_box_tpl -->
<h2>{intl-category_certificate_types_list}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_certificate_name}:</th>
    <th>{intl-th_certificate_description}:</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN certificate_item_tpl -->
<tr class="{theme-type_class}">
    <td><a href="/cv/certificatetype/view/{item_id}">{item_name}&nbsp;</a></td>
    <td>{item_description}&nbsp;</td>
    <td width="1%"><a href="/cv/certificatetype/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvcrt{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcvcrt{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/certificatetype/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcvcrt{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezcvcrt{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END certificate_item_tpl -->
</table>
<!-- END list_box_tpl -->
