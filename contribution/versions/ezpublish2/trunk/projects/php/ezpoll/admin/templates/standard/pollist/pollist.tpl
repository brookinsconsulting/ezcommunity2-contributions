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

<div onLoad="MM_preloadImages('../images/redigerminimrk.gif','../images/slettminimrk.gif')"></div>

<form action="/poll/pollist/">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<br />

<table class="list" width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>{intl-poll}</th>
	<th>{intl-description}</th>
	<th>{intl-enabled}</th>
	<th>{intl-closed}</th>
	<th>{intl-main}</th>
	<th colspan="2">&nbsp;</td>
</tr>
<tr>
	<td>
	{nopolls}
	</td>
	<!-- BEGIN poll_item_tpl -->
	<tr>
	<td class="{td_class}">
	<a href="/poll/polledit/edit/{poll_id}/">{poll_name}</a>
	</td>
	<td class="{td_class}">
	{poll_description}
	</td>

	<td class="{td_class}">
	{poll_is_enabled}
	</td>

	<td class="{td_class}">
	{poll_is_closed}
	</td>

	<td class="{td_class}">
	<input type="radio" name="MainPollID" value="{poll_id}" {is_checked} />
	</td>

	<td width="1%" class="{td_class}">
	<a href="/poll/polledit/edit/{poll_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezp{poll_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezp{poll_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/poll/polledit/delete/{poll_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezp{poll_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezp{poll_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
	</tr>
	<!-- END poll_item_tpl -->
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<input type="hidden" name="Action" value="StoreMainPoll" />
	<input class="okbutton" type="submit" value="Lagre endringer" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/poll/polledit/new/">
	<input class="okbutton" type="submit" value="{intl-addpoll}" />
	</form>
	</td>
</tr>
</table>