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

<div onLoad="MM_preloadImages('/ezcv/images/redigerminimrk.gif','/ezcv/images/slettminimrk.gif')"></div>

<h1>{intl-headline_view}</h1>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN cv_person_info -->
<tr valign="baseline">
    <td class="boxtext">{intl-th_person_name}:</td>
    <td>{person_last_name}, {person_first_name}</td>
    <td class="boxtext">{intl-th_person_birth_date_personno}:</td>
    <td>{person_birth_date} {person_no}</td>  
</tr>
<tr valign="baseline">
    <td class="boxtext">{intl-th_person_comment}:</td>
    <td colspan="3">{person_comment}</td>
</tr>
<!-- END cv_person_info -->



<!-- BEGIN cv_info -->
<tr valign="baseline">
    <td class="boxtext">{intl-th_sex}:</td>
    <td>{cv_sex}</td>
    <td class="boxtext">&nbsp;</td>
    <td>&nbsp;</td>  
</tr>
<tr valign="baseline">
    <td class="boxtext">{intl-th_marital_status}:</td>
    <td>{cv_marital_status}</td>  
    <td class="boxtext">{intl-th_children}:</td>
    <td>{cv_children}</td>
</tr>
<tr valign="baseline">
    <td class="boxtext">{intl-th_work_status}:</td>
    <td>{cv_work_status}</td>  
    <td class="boxtext">{intl-th_army_status}:</td>
    <td>{cv_army_status}</td>
</tr>
<!-- END cv_info -->


<!-- BEGIN address_info_tpl -->
    <tr>
    <td colspan="4">
        <p class="boxtext">{intl-th_addresses}</p>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_address_type}:</td>
                <td class="boxtext">{intl-th_address_street}:</td>
                <td>&nbsp;</td>
                <td class="boxtext">{intl-th_address_zip}:</td>
                <td class="boxtext">{intl-th_address_place}:</td>
                <td class="boxtext">{intl-th_address_country}:</td>
            </tr>
            <!-- BEGIN address_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{address_type}&nbsp;</td>
                <td>{address_street1}&nbsp;</td>
                <td>{address_street2}&nbsp;</td>
                <td>{address_zip}&nbsp;</td>
                <td>{address_place}&nbsp;</td>
                <td>{address_country}&nbsp;</td>
            </tr>
            <!-- END address_item_tpl -->
        </table>
    <td>
    </tr>
<!-- END address_info_tpl -->

<!-- BEGIN phone_info_tpl -->
    <tr>
    <td colspan="4">
        <p class="boxtext">{intl-th_phone_numbers}</p>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_phone_type}:</td>
                <td class="boxtext">{intl-th_phone_number}:</td>
            </tr>
            <!-- BEGIN phone_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{phone_type}&nbsp;</td>
                <td>{phone_number}&nbsp;</td>
            </tr>
            <!-- END phone_item_tpl -->
        </table>
    <td>
    </tr>
<!-- END phone_info_tpl -->

<!-- BEGIN online_info_tpl -->
    <tr>
    <td colspan="4">
        <p class="boxtext">{intl-th_online_addresses}</p>
        <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="boxtext">{intl-th_online_type}:</td>
                <td class="boxtext">{intl-th_online_address}:</td>
            </tr>
            <!-- BEGIN online_item_tpl -->
            <tr class="{theme-type_class}">
                <td>{online_type}&nbsp;</td>
                <td>{online_url}&nbsp;</td>
            </tr>
            <!-- END online_item_tpl -->
        </table>
    <td>
    </tr>
<!-- END online_info_tpl -->


</table>





<!-- BEGIN cv_items_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-th_current_id}</th>
    <th>{intl-th_person_name}</th>
    <th>{intl-th_current_created}</th>
    <th>{intl-th_current_valid_until}</th>
    <th colspan="2">&nbsp;</th>
</tr>
<!-- BEGIN cv_item_tpl -->
<tr class="{theme-type_class}">
    <td>{item_id}</td>
    <td>{person_last_name}, {person_first_name}</td>
    <td>{item_created}</td>
    <td>{item_valid_until}</td>
    <td width="1%"><a href="/cv/cv/edit/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcv{item_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezcv{item_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/cv/cv/delete/{item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcv{item_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezcv{item_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END cv_item_tpl -->
</table>
<!-- END cv_items_tpl -->

<!-- BEGIN cv_no_items_tpl -->
<p>{intl-th_no_cvs}</p>
<!-- END cv_no_items_tpl -->
