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

<div onLoad="MM_preloadImages('../ezforum/images/redigerminimrk.gif','../ezforum/images/slettminimrk.gif')"></div>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
		<h1>Forum</h1>
	      </td>
             <td bgcolor="#f0f0f0" align="center">
               <br>
               <form action="index.php" method="post">
               <input type="hidden" name="page" value="{docroot}/main.php">
               <input type="text" name="criteria">
			   <input type="submit" name="search" value="{intl-search}">
              </form>
            </td>
  </tr>
</table>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="ezforum/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
<tr>
	<td bgcolor="3c3c3c"><img src="ezforum/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
<tr>
	<td bgcolor="ffffff"><img src="ezforum/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
</table>

<a><img src="ezforum/images/pil.gif" width="10" height="10" border="0">&nbsp;<b>Sett inn path her!</b></a>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="ezforum/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
<tr>
	<td bgcolor="3c3c3c"><img src="ezforum/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
<tr>
	<td bgcolor="ffffff"><img src="ezforum/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
   <tr>
     <th>
     Emne:
     </th> 
     <th>
     Forfatter: 
     </th>
     <th>
     Tidspunkt: 
     </th>
     <th>
     Notis: 
     </th>
     <td colspan="2">&nbsp;</td>
   </tr>

   <!-- BEGIN message_item_tpl -->
   <tr bgcolor="{color}">
     <td class="{td_class}">
     {spacer}
     <a href="/forum/message/{category_id}/{forum_id}/{message_id}">{message_topic}</a>
     </td>
     <td class="{td_class}">
     {message_user}
     </td>
     <td class="{td_class}">
     {message_postingtime}
     </td>
     <td class="{td_class}">
     {emailnotice}&nbsp;
     </td class="{td_class}">
     <td width="80" align="right" class="{td_class}">
	 <a href="/forum/messageedit/edit/{category_id}/{forum_id}/{message_id}/"  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-red','','/ezforum/images/redigerminimrk.gif',1)"><img name="efm{message_id}-red" border="0" src="/ezforum/images/redigermini.gif" width="16" height="16" align="top"></a>
     &nbsp;&nbsp;&nbsp;&nbsp;
	 <a href="/forum/messageedit/delete/{category_id}/{forum_id}/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('efm{message_id}-slett','','/ezforum/images/slettminimrk.gif',1)"><img name="efm{message_id}-slett" border="0" src="/ezforum/images/slettmini.gif" width="16" height="16" align="top"></a>
	 &nbsp;&nbsp;
	 </td>
   </tr>

   <!-- END message_item_tpl -->

</table>
<br>
