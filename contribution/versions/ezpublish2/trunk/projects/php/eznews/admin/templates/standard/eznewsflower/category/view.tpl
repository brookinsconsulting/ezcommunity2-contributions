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

<div onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif')"></div>



<!-- BEGIN this_item_template -->
<h1>{this_name}</h1>

<hr noshade size="4" />

<p>{this_public_description}</p>

<!-- END this_item_template -->


<!-- BEGIN go_to_parent_template -->
<!-- <a href="/{this_path}/{this_canonical_parent_id}">{intl-go_to_parent} {this_canonical_parent_name}</a><br /> -->
<!-- END go_to_parent_template -->

<!-- <a href="/{this_path}/{this_id}?delete+this">{intl-delete_this_category}</a><br /> -->


<!-- BEGIN no_articles_template -->
<h2>{intl-no_articles_in_category}</h2>
<!-- END no_articles_template -->

<!-- BEGIN articles_template -->
<h2>{this_article_count} {intl-article}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
    <tr>
        <th>{intl-name}</th>
        <th>{intl-createdat}</th>
        <th width="1%">&nbsp;</th>
        <th width="1%">&nbsp;</th>
    </tr>

{article_items}
</table>

<!-- END articles_template -->

<br />

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<form method="post" action="/{this_path}/{this_id}?edit+this">
	<input class="okbutton" type="submit" value="{intl-edit_this_category}">
	</form>
	</td>
	<td>
	&nbsp;
	</td>
	<td>
	<form method="post" action="/{this_path}/{this_id}?create+article">
	<input class="okbutton" type="submit" value="{intl-create_article}">
	</form>
	</td>
</tr>
</table>

<!-- 
<a href="/{this_path}/{this_id}?edit+this">{intl-edit_this_category}</a><br />
<a href="/{this_path}/{this_id}?create+article">{intl-create_article}</a><br /> 
-->

