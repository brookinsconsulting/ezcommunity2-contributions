<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-view_headline}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="/contact/company/search/" method="post">
        <input type="text" name="SearchText" size="12" />       
        <input type="submit" value="{intl-search}" />
        </form> 
        </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<div class="p">{name}</div>
	<!-- BEGIN logo_view_tpl -->
	<img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END logo_view_tpl -->
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
<!--     <p class="boxtext">{intl-company_image}:</p> -->
<img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="left" vspace="2" hspace="6" />
<!-- END image_view_tpl -->

<div class="p">{description}</div>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-contact_person}:</p>
	<!-- BEGIN contact_person_tpl -->
	{contact_lastname}, {contact_firstname}
	<!-- END contact_person_tpl -->
	<!-- BEGIN no_contact_person_tpl -->
	{intl-no_contact_person}
	<!-- END no_contact_person_tpl -->
	</td>

	<td width="50%">
	<p class="boxtext">{intl-project_status}:</p>
	<!-- BEGIN project_status_tpl -->
	{project_status}
	<!-- END project_status_tpl -->
	<!-- BEGIN no_project_status_tpl -->
	{intl-no_project_status}
	<!-- END no_project_status_tpl -->
	</td>
</tr>
</table>

<!-- BEGIN consultation_table_item_tpl -->
<h2>{intl-consultation_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{intl-consultation_date}:</th>
	<th>{intl-consultation_short_description}:</th>
	<th>{intl-consultation_status}:</th>
	<th colspan="2">&nbsp;</th>
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

	<td width="1%">
	<a href="/contact/consultation/edit/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezc{consultation_id}-red" border="0" src="/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<a href="/contact/consultation/delete/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezc{consultation_id}-slett" border="0" src="/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	

</tr>
<!-- END consultation_item_tpl -->
</table>

<!-- END consultation_table_item_tpl -->

<form method="post" action="/contact/company/edit/{company_id}/">

<hr noshade="noshade" size="4" />

<!-- BEGIN consultation_buttons_tpl -->
<input class="stdbutton" type="submit" name="ListConsultation" value="{intl-consultation_list}">
<input class="stdbutton" type="submit" name="NewConsultation" value="{intl-consultation}">
<hr noshade="noshade" size="4" />
<!-- END consultation_buttons_tpl -->

<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">
<!--
<input type="submit" name="Delete" value="{intl-delete}" />
<input type="submit" name="Back" value="{intl-list}">
-->
</form>
