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

<table width="100%" border="0">
<tr>
	<td valign="bottom">
	    <h1>{intl-person_list_headline}</h1>
	</td>
	<td rowspan="2" align="right">
	    <form action="/contact/person/search/" method="post">
	    	<input type="text" name="SearchText" size="12" value="{search_form_text}" />
		<input type="submit" value="{intl-search}" />
	    </form>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_persons_tpl -->
<h2>{intl-no_persons_found}</h2>
<!-- END no_persons_tpl -->

<!-- BEGIN person_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="/contact/person/view/{person_id}">{person_lastname}, {person_firstname}&nbsp;</a>
	
	</td>

	<td width="1%">
	<a href="/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/person/edit/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{person_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<a href="/contact/person/delete/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{person_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>	

</tr>
<!-- END person_item_tpl -->
</table>

<!-- BEGIN person_list_tpl -->
<table>
<tr>

<!-- BEGIN person_list_previous_tpl -->
<td>
<a href="/contact/person/{action}/{item_previous_index}/{search_text}">{intl-previous}</a>
</td>
<!-- END person_list_previous_tpl -->
<!-- BEGIN person_list_previous_inactive_tpl -->
<td>
{intl-previous}
</td>
<!-- END person_list_previous_inactive_tpl -->

<!-- BEGIN person_list_item_tpl -->
<td>
<a href="/contact/person/{action}/{item_index}/{search_text}">{item_name}</a>
</td>
<!-- END person_list_item_tpl -->

<!-- BEGIN person_list_next_tpl -->
<td>
<a href="/contact/person/{action}/{item_next_index}/{search_text}">{intl-next}</a>
</td>
<!-- END person_list_next_tpl -->
<!-- BEGIN person_list_next_inactive_tpl -->
<td>
{intl-next}
</td>
<!-- END person_list_next_inactive_tpl -->

</tr>
</table>
<!-- END person_list_tpl -->

<!-- END person_table_tpl -->

<form method="post" action="/contact/person/new">
<input class="okbutton" type="submit" value="{intl-new_person}">
</form>
