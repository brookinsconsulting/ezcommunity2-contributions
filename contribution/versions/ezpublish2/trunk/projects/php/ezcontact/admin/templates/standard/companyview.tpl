<!-- BEGIN company_information_tpl -->
<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-view_headline}</h1>
        </td>
<!--          <td rowspan="2" align="right"> -->
<!--          <form action="/contact/company/search/" method="post"> -->
<!--          <input type="text" name="SearchText" size="12" />        -->
<!--          <input class="stdbutton" type="submit" value="{intl-search}" /> -->
<!--          </form>  -->
<!--          </td> -->
</tr>
</table>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN logo_view_tpl -->
<img src="{logo_image_src}" width="{logo_width}" height="{logo_height}" border="0" alt="{logo_alt}" /><br /><br />
<!-- END logo_view_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<div class="p">{name}</div>
	</td>
	<td valign="top">
	<p class="boxtext">{intl-company_no}:</p>
	<div class="p">{company_no}</div>
	</td>
</tr>
</table>

<h2>{intl-addresses_headline}</h2>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN address_item_tpl -->
	<td width="50%" valign="top">
	<p class="boxtext">{address_type_name}:</p>
	<div class="p">{street1}</div>
	<div class="p">{street2}</div>
	<div class="p">{zip} {place}</div>
	<div class="p">{country}</div>
	</td>
	<!-- END address_item_tpl -->
	<!-- BEGIN no_address_item_tpl -->
	<td>
	{intl-error_no_addresses}
	</td>
	<!-- END no_address_item_tpl -->
</tr>
</table>

<!-- BEGIN phone_item_tpl -->
<h2>{intl-telephone_headline}</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <!-- BEGIN phone_line_tpl -->
    <td width="{phone_width}%">
        <p class="boxtext">{phone_type_name}:</p>
        {phone}
    </td>
    <!-- END phone_line_tpl -->
</tr>
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<h2>{intl-telephone_headline}</h2>
<div class="p">{intl-error_no_phones}</div>
<!-- END no_phone_item_tpl -->

<!-- BEGIN online_item_tpl -->
<h2>{intl-online_headline}</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <!-- BEGIN online_line_tpl -->
    <td width="{online_width}%">
        <p class="boxtext">{online_type_name}:</p>
        <a href="{online_prefix}{online}">{online_visual_prefix}{online}</a>
    </td>
    <!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->

<!-- BEGIN no_online_item_tpl -->
<h2>{intl-online_headline}</h2>
<div class="p">{intl-error_no_onlines}</div>
<!-- END no_online_item_tpl -->

<!-- BEGIN no_image_tpl -->

<!-- END no_image_tpl -->

<p class="boxtext">{intl-description}:</p>
<!-- BEGIN image_view_tpl -->
<img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" vspace="2" hspace="6" />
<!-- END image_view_tpl -->
<div class="p">{description}</div>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN contact_item_tpl -->
	<td width="50%">
	<p class="boxtext">{intl-contact_person}:</p>
	<!-- BEGIN contact_person_tpl -->
	{contact_lastname}, {contact_firstname}
	<!-- END contact_person_tpl -->
	<!-- BEGIN no_contact_person_tpl -->
	{intl-no_contact_person}
	<!-- END no_contact_person_tpl -->
	</td>
	<!-- END contact_item_tpl -->

	<!-- BEGIN status_item_tpl -->
	<td width="50%">
	<p class="boxtext">{intl-project_status}:</p>
	<!-- BEGIN project_status_tpl -->
	{project_status}
	<!-- END project_status_tpl -->
	<!-- BEGIN no_project_status_tpl -->
	{intl-no_project_status}
	<!-- END no_project_status_tpl -->
	</td>
	<!-- END status_item_tpl -->
</tr>
</table>

<!-- BEGIN person_table_item_tpl -->
<h2>{intl-person_headline} - ({person_start}-{person_end}/{person_max})</h2>

<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th colspan="1">&nbsp;</th>
</tr>
<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
	<a href="/contact/person/view/{person_id}/">{person_lastname}, {person_firstname}</a>
	</td>

	<!-- BEGIN person_consultation_button_tpl -->
	<td width="1%">
	<a href="/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/admin/images/addminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="/admin/images/addmini.gif" width="16" height="16" align="top"></a>
	</td>
	<!-- END person_consultation_button_tpl -->
</tr>
<!-- END person_item_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/contact/company/view/{company_id}/{item_previous_index}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	&nbsp;<a class="path" href="/contact/company/view/{company_id}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="/contact/company/view/{company_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<!-- END person_table_item_tpl -->

<!-- BEGIN consultation_table_item_tpl -->
<h2>{intl-consultation_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="/contact/company/view/{company_id}?OrderBy=Date">{intl-consultation_date}:</a></th>
	<th><a href="/contact/company/view/{company_id}?OrderBy=Description">{intl-consultation_short_description}:</a></th>
	<th><a href="/contact/company/view/{company_id}?OrderBy=Status">{intl-consultation_status}:</a></th>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td>
        {consultation_date}
	</td>
	<td>
        <a href="/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="/contact/consultation/type/list/{consultation_status_id}">{consultation_status}</a>
	</td>
</tr>
<!-- END consultation_item_tpl -->
</table>

<!-- END consultation_table_item_tpl -->

<br />
<form method="post" action="/contact/company/edit/{company_id}/">

<hr noshade="noshade" size="4" />

<!-- BEGIN consultation_buttons_tpl -->
<input class="stdbutton" type="submit" name="ListConsultation" value="{intl-consultation_list}">
<input class="stdbutton" type="submit" name="NewConsultation" value="{intl-consultation}">
<!-- END consultation_buttons_tpl -->

<!-- BEGIN company_edit_button_tpl -->
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">
<!-- END company_edit_button_tpl -->
<!--
<input type="submit" name="Delete" value="{intl-delete}" />
<input type="submit" name="Back" value="{intl-list}">
-->
</form>
<!-- END company_information_tpl -->
<!-- BEGIN no_company_tpl -->
<h1>{intl-no_company_defined}{company_id}</h1>
<!-- END no_company_tpl -->
