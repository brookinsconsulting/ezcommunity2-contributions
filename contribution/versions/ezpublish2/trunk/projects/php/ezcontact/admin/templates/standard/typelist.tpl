<!-- BEGIN list_page -->
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
<h1>{intl-list_headline}</h1>
<hr noshade="noshade" size="4" />
<br />
<!-- BEGIN list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-name}:</th>
	<th colspan="5">&nbsp;</th>
</tr>
<!-- BEGIN line_item_tpl -->
<tr class="{bg_color}">
	<td>
        {item_name}&nbsp;
	</td>

<!-- BEGIN item_move_up_tpl -->
	<td width="1%"><a href="{item_up_command}/{item_id}">{intl-item_up}</a></td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_up_tpl -->

<!-- BEGIN item_separator_tpl -->
	<td width="1%">/</td>
<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->
        <td width="1%"> &nbsp; </td>
<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_down_tpl -->
	<td width="1%"><a href="{item_down_command}/{item_id}">{intl-item_down}</a></td>
<!-- END item_move_down_tpl -->
<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%"> &nbsp; </td>
<!-- END no_item_move_down_tpl -->

	<td width="1%">
	<a href="{item_edit_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="{item_delete_command}/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END line_item_tpl -->
</table>
<!-- END list_item_tpl -->

<!-- BEGIN no_line_item_tpl -->
<p class="boxtext">{intl-no_item}</p>
<!-- END no_line_item_tpl -->
<hr noshade="noshade" size="4" />
<br />
<form method="post" action="{item_new_command}/">
    <input class="okbutton" type="submit" name="Back" value="{intl-new}">
</form>

<!-- END list_page -->
