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

<div onLoad="MM_preloadImages('/ezarticle/images/redigerminimrk.gif','/ezarticle/images/slettminimrk.gif')"></div>

<form action="/article/articleedit/imageedit/storedef/{article_id}/" method="post">

<h1>{intl-images}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_caption}:</th>
	<th>{intl-image_preview}:</th>
	<th>{intl-image_mini}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td class="{td_class}">
	{image_number}
	</td>
	<td class="{td_class}">
	{image_name}
	</td>
	<td class="{td_class}">
	<img src="{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/imageedit/edit/{image_id}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-red','','/ezarticle/images/redigerminimrk.gif',1)"><img name="eztp{image_id}-red" border="0" src="/ezarticle/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="/article/articleedit/imageedit/delete/{image_id}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-slett','','/ezarticle/images/slettminimrk.gif',1)"><img name="eztp{image_id}-slett" border="0" src="/ezarticle/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END image_tpl -->

</table>

<br/>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewImage"value="{intl-image_upload}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="/article/articleedit/edit/{article_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

