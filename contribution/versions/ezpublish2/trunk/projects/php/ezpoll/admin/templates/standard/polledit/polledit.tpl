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

<form method="post" action="/poll/polledit/{action_value}/{poll_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-desc}</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>

<br /><br />

<p class="checkhead">{intl-settings}</p>
<div class="check"><input type="checkbox" name="IsEnabled" {is_enabled}>&nbsp;Aktiv</div>
<div class="check"><input type="checkbox" name="IsClosed" {is_closed}>&nbsp;Avsluttet</div>
<div class="check"><input type="checkbox" name="ShowResult" {show_result}>&nbsp;Vis resultat</div>

<div class="check"><input type="checkbox" name="Anonymous" {anonymous}> Anonym avstemming</div>
<div class="check"><input type="checkbox" name="UserEditRule" {user_edit_rule}> Bruker kan redigere egen stemme</div>

<p class="checkhead">{intl-choices}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<div class="check"><input type="radio" value="And" name="And">Og</div>
	</td>
	<td>
	<input value="Or" type="radio" name="And"> Eller</div>
	</td>
</tr>
</table>

<p class="checkhead">{intl-show}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<div class="check"><input type="checkbox" name="Number" {number}> Antall stemmer</div>
	</td>
	<td>
	<div class="check"><input type="checkbox" name="Percent" {percent}> Prosent</div>
	</td>
</tr>
</table>

<br />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">Svaralternativer:</th>
	<th><span align="right">Antall stemmer:</span></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<tr>
	<td>
	{nopolls}
	</td>
	<!-- BEGIN poll_choice_tpl -->
	<tr>
		<td>
			<a href="/poll/polledit/{choice_id}/">{poll_choice_name}</a>
		</td>
		<td>
			{poll_number}
		</td>
		<td width="1%">
			<a href="/poll/choiceedit/edit/{poll_id}/{choice_id}/"onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezpoll{choice_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezpoll{choice_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a>
		</td>
		<td width="1%">
			<a href="/poll/choiceedit/delete/{poll_id}/{choice_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezpoll{choice_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezpoll{choice_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a>
		</td>	
	</tr>	
	<!-- END poll_choice_tpl -->
</tr>
</table>

<br />

<hr noshade size="4"/>

<input class="stdbutton" type="submit" name="Choice" value="Nytt svaralternativ">

<hr noshade size="4"/>

<input type="hidden" name="PollID" value="{poll_id}" />
<input class="okbutton" type="submit" value="OK" />

<form method="post" action="/poll/pollist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>

