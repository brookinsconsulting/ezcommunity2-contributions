<!-- BEGIN list_tpl -->
<table width="100%" border="0">
<tr>
	<td valign="bottom">
	    <h1>{intl-headline_list}</h1>
	</td>
	<td rowspan="2" align="right">
	    <form action="/contact/company/search/" method="post">
	    <table>
	    <tr>
	        <td>
	    	<input type="text" name="SearchText" size="12" />
		<input type="submit" value="{intl-search}" />
	    	<input type="hidden" name="SearchCategory" value="{current_id}" />
	        </td>
	    </tr>
	    <tr>
	        <td>
		<input type="checkbox" name="CurrentCategory" checked />
		<span class="boxtext">{intl-only_current_category}</span><br />
	        </td>
	    </tr>
	    </table>
	    </form>
	</td>
</tr>
</table>
<!-- END list_tpl -->
<!-- BEGIN view_tpl -->
<h1>{intl-headline_view}</h1>
<!-- END view_tpl -->

<!-- BEGIN path_tpl -->

<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<!-- <h2>{current_name}</h2>
<p>{current_description}</p> -->
<!-- BEGIN image_item_tpl -->
<!-- <p class="boxtext">{intl-th_type_current_image}:</p> -->
<p><img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" /></p>
<!-- END image_item_tpl -->
<!-- END current_type_tpl -->

<!-- BEGIN not_root_tpl -->
<!-- <p><a href="/{intl-module_name}/{intl-command_type}/{intl-command_edit}/{current_id}">{intl-button_edit}</a></p> -->
<!-- END not_root_tpl -->


<!-- BEGIN category_list_tpl -->

<h2>{intl-headline_categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{current_id}?OrderBy=Name">{intl-th_type_name}:</a></th>
    <th><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{current_id}?OrderBy=Description">{intl-th_type_description}:</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr class="{theme-type_class}">
    <td><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{type_id}/">{type_name}</a></td>
    <td>{type_description}</td>
    <td width="1%"><a href="/{intl-module_name}/{intl-category_command_type}/{intl-command_edit}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezct{type_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezct{type_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a></td>
    <td width="1%"><a href="/{intl-module_name}/{intl-category_command_type}/{intl-command_delete}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezct{type_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezct{type_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a></td>
</tr>
<!-- END category_item_tpl -->

</table>
<!-- END category_list_tpl -->

<!-- BEGIN no_category_item_tpl -->
<!-- <h2>{intl-headline_no_categories}</h2>
{intl-error_no_categories} -->
<!-- END no_category_item_tpl -->


<!-- BEGIN type_list_tpl -->
<h2>{intl-headline_types}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{current_id}?OrderBy=Name">{intl-th_type_name}:</a></th>
    <th><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{current_id}?OrderBy=Description">{intl-th_type_description}:</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr class="{theme-type_class}">
    <td><a href="/{intl-module_name}/{intl-command_type}/{intl-command_list}/{type_id}/">{type_name}</a></td>
    <td>{type_description}</td>
    <td width="1%"><a href="/{intl-module_name}/{intl-category_command_type}/{intl-command_edit}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezuser{type_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td width="1%"><a href="/{intl-module_name}/{intl-category_command_type}/{intl-command_delete}/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezuser{type_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top"></a></td>
</tr>
<!-- END type_item_tpl -->

</table>
<!-- END type_list_tpl -->

<!-- BEGIN no_type_item_tpl -->
<h2>{intl-headline_no_types}</h2>
{intl-error_no_types}
<!-- END no_type_item_tpl -->


<form method="post" action="/contact/companycategory/new/{current_id}">

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-new_company_category}">
</form>

<h2>{intl-companylist_headline}</h2>

<!-- BEGIN no_companies_tpl -->
	<p class="error">{intl-no_companies_error}</p>
<!-- END no_companies_tpl -->

<!-- BEGIN companies_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>{intl-logo}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/contact/company/view/{company_id}">{company_name}</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN image_view_tpl -->
        <img src="{company_logo_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_view_tpl -->
	<!-- BEGIN no_image_tpl -->
	<p>{intl-no_image}</p>
	<!-- END no_image_tpl -->	
	</td>

	<!-- BEGIN company_consultation_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/contact/consultation/company/new/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{company_id}-red','','/images/addminimrk.gif',1)"><img name="ezn{company_id}-red" border="0" src="/images/addmini.gif" width="16" height="16" align="top" alt="Add consultation" /></a>
	</td>
	<!-- END company_consultation_button_tpl -->

	<td class="{td_class}" width="1%">
	<a href="/contact/company/edit/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{company_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{company_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td class="{td_class}" width="1%">
	<a href="/contact/company/delete/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{company_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{company_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END companies_table_tpl -->

<form method="post" action="/contact/company/new/{current_id}">

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-new_company}">
</form>
