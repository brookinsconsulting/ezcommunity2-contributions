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

<h1>Bedriftsdatabase</h1>

<hr noshade="noshade" size="4"/ >

<!-- BEGIN path_tpl -->

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/contact/company/list/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="/images/path-slash.gif" height="10" width="20" border="0">

<a class="path" href="/contact/company/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4"/ >

<!-- BEGIN category_list_tpl -->
<h2>{intl-categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td> <!-- class="{td_class}" -->
	<a href=/contact/company/list/{category_id}>{category_name}</a>
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN company_list_tpl -->
<h2>{intl-companies}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-image}:</th>
	<th>{intl-name}:</th>
	<th>{intl-telephone}:</th>
</tr>
<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">

	<!-- BEGIN image_view_tpl -->
        <img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_view_tpl -->

	<!-- BEGIN no_image_tpl -->
<!--	<p>{intl-no_image}</p> -->
	<!-- END no_image_tpl -->	

	</td>
	<td class="{td_class}">
	<a href="/contact/company/view/{company_id}/">{company_name}</a>
	</td>
	<td class="{td_class}">
	{company_telephone}
	</td>

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END company_list_tpl -->


