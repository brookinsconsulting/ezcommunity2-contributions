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

<div onLoad="MM_preloadImages('..admin/images/redigerminimrk.gif','../admin/images/slettminimrk.gif')"></div>

<h1>Brukeroversikt</h1>

<table width="100%" cellspacing=0 cellpadding=4 border=0>
<tr>
	<td>
		<p><b>Brukernavn:</b></p>
	</td>
	<td>
		<p><b>Fullt navn:</b></p>
	</td>
	<td>
                <p><b>Gruppe:</b></p>
        </td>
</tr>
{bruker}
</table>
<br>
{prevnext}

<!-- <a href="index.php4?page=user2.php4&new=1">[Ny bruker]</a>
<input type=submit value="Ny bruker" name=new>
</form>

<a href="index.php4">[Tilbake]</a> -->

