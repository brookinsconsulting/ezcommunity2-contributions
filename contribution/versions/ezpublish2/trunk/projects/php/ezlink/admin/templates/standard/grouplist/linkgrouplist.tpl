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
	<td>
	<h1>Linkoversikt</h>
	</td>

	<td bgcolor="#f0f0f0">
	<center><br>
	<form method="post" action="/link/search/">
	<input type="text" name="QueryText" value=""><input type="submit" value="søk">
	<input type="hidden" name="Action" value="search">
	</form>
	</center>
	</td>
	
	<td align="right">
	<a href=/link/linkedit/new/{linkgroup_id}/><img src="/ezlink/images/nylink.gif" width="32" height="32" border="0"></a>
	</td>
<tr>
</table>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="/ezlink/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
<tr>
	<td bgcolor="3c3c3c"><img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
<tr>
	<td bgcolor="ffffff"><img src="/ezlink/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
</table>

<h2>Kategorier:</h2>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
{group_list}
</table>
<br>
<!-- <a href=index.php?page=../ezlink/admin/groupedit.php>[Legg til ny gruppe]</a> -->

<br>
<br>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="/ezlink/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
<tr>
	<td bgcolor="3c3c3c"><img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
<tr>
	<td bgcolor="ffffff"><img src="/ezlink/images/1x1.gif" width="1" height="6" border="0"></td>
</tr>
</table>

<h2>Linker:</h2>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
{link_list}
</table>
<br>
<!-- <a href=index.php?page=../ezlink/admin/linkedit.php>[Legg til ny link]</a> -->
