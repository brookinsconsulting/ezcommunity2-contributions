<!-- BEGIN company_information_tpl -->
<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-view_headline}</h1>
        </td>
	<!-- BEGIN company_search_tpl -->
         <td rowspan="2" align="right">  
              <form action="{www_dir}{index}/contact/search/company/" method="get">
              <input type="text" name="SearchText" size="12" />
              <input class="stdbutton" type="submit" value="{intl-search}" />
              </form>
        </td>
	<!-- END company_search_tpl -->
</tr>
</table>

<!--
<style>
.dr
{
        text-align: center;
        margin: 1em auto 0;
        padding: 1px 0 0; /* 17-16 */
        width: 88%;
}
.dr HR { width: 0; line-height: 16px; margin-right: 100%; }
</style>
<hr class="dr" noshade="noshade" size="4" />
-->

<!-- BEGIN logo_view_tpl -->
<br /> 
<img src="{www_dir}{logo_image_src}" width="{logo_width}" height="{logo_height}" border="0" alt="{logo_alt}" /><br /><br />
<!-- END logo_view_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name_headline}:</p>
	<span class="pSpan">{name}</span>

        <!-- BEGIN company_description_item_tpl -->
	<br />
	<p class="boxtext">{intl-description}:</p>
	<span class="pSpan">{description}</span>
	</td>
        <!-- END company_description_item_tpl -->

	<!-- BEGIN company_number_item_tpl -->
	<td valign="top" align="right">
	<p class="boxtext">{intl-company_no}:</p>
	<span class="pSpan">{company_no}</span>
	</td>
        <!-- END company_number_item_tpl -->
</tr>
</table>

<!-- BEGIN address_table_tpl -->
<br />

<!--
<p class="boxtext">{intl-addresses_headline}</p>
-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN address_item_tpl -->
	<td width="50%" valign="top">
	<p class="boxtext">{address_type_name}:</p>
	<span class="p">{street1}</span>
	<span class="p">{street2}</span>
	<span class="p">{zip} {place}</span>
	<span class="p">{country}</span>
	</td>
	<!-- END address_item_tpl -->
	<!-- BEGIN no_address_item_tpl -->
	<td>
	<!-- {intl-error_no_addresses} -->&nbsp;
	</td>
	<!-- END no_address_item_tpl -->
</tr>
</table>
<!-- END address_table_tpl -->

<!-- BEGIN phone_table_tpl -->
<!-- BEGIN phone_item_tpl -->
<!-- <p class="boxtext">{intl-telephone_headline}</p> -->
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
<!--
<p class="boxtext">{intl-telephone_headline}</p>
<span class="p">{intl-error_no_phones}</span>
-->
<!-- END no_phone_item_tpl -->
<!-- END phone_table_tpl -->

<!-- BEGIN online_table_tpl -->
<!-- BEGIN online_item_tpl -->
<!-- <p class="boxtext">{intl-online_headline}</p> -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <!-- BEGIN online_line_tpl -->
    <td width="{online_width}%">
        <p class="boxtext">{online_type_name}:</p>
        <a href="{www_dir}{index}{online_prefix}{online}" target="_blank">{online_visual_prefix}{online}</a>
    </td>
    <!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->

<!-- BEGIN no_online_item_tpl -->
<!--
<p class="boxtext">{intl-online_headline}</p>
<span class="p">{intl-error_no_onlines}</span>
-->
<!-- END no_online_item_tpl -->
<!-- END online_table_tpl -->

<!-- BEGIN no_image_tpl -->

<!-- END no_image_tpl -->

<!-- BEGIN image_view_tpl -->
<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="left" vspace="2" hspace="6" />
<!-- END image_view_tpl -->

<br clear="all" /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN contact_item_tpl -->
	<td width="50%">
	<p class="boxtext">{intl-contact_person}:</p>
	<!-- BEGIN contact_person_tpl -->
	<a href="{www_dir}{index}/contact/person/view/{contact_person_id}/">{contact_lastname}, {contact_firstname}</a>
	<!-- END contact_person_tpl -->
	<!-- BEGIN no_contact_person_tpl -->
	{intl-no_contact_person}
	<!-- END no_contact_person_tpl -->
	</td>
	<!-- END contact_item_tpl -->

	<!-- BEGIN status_item_tpl -->
<!--
	<td width="50%" align="right">
	<p class="boxtext">{intl-project_status}:</p>
	<!-- BEGIN project_status_tpl -->
	{project_status}
	<!-- END project_status_tpl -->
	<!-- BEGIN no_project_status_tpl -->
	{intl-no_project_status}
	<!-- END no_project_status_tpl -->
	</td>
-->
	<!-- END status_item_tpl -->
</tr>
</table>

<!-- BEGIN person_table_item_tpl -->
<h2>{intl-person_headline} - ({person_start}-{person_end}/{person_max})</h2>

<table class="list" width="100%" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th align="left">{intl-person_name}:</th>
	<th colspan="1">&nbsp;</th>
</tr>
<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td align="left">
	<a href="{www_dir}{index}/contact/person/view/{person_id}/">{person_lastname}, {person_firstname}</a>
	</td>

	<!-- BEGIN person_consultation_button_tpl -->
	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/design/admin/images/addminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top"></a>
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
	<a class="path" href="{www_dir}{index}/contact/company/view/{company_id}/{item_previous_index}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/contact/company/view/{company_id}/{item_index}">{type_item_name}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/contact/company/view/{company_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
	<th><a href="{www_dir}{index}/contact/company/view/{company_id}?OrderBy=Date">{intl-consultation_date}:</a></th>
	<th><a href="{www_dir}{index}/contact/company/view/{company_id}?OrderBy=Description">{intl-consultation_short_description}:</a></th>
	<th><a href="{www_dir}{index}/contact/company/view/{company_id}?OrderBy=Status">{intl-consultation_status}:</a></th>
</tr>

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td>
        {consultation_date}
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/type/list/{consultation_status_id}">{consultation_status}</a>
	</td>
</tr>
<!-- END consultation_item_tpl -->
</table>

<!-- END consultation_table_item_tpl -->

<!-- BEGIN order_table_item_tpl -->
<h2>{intl-sales_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-order_date}:</th>
	<th>{intl-order_status}:</th>
	<th>{intl-order_price}:</th>
</tr>

<!-- BEGIN order_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_date}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_status}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/trade/orderedit/{order_id}/">{order_price}</a>
	</td>
</tr>
<!-- END order_item_tpl -->
</table>

<!-- END order_table_item_tpl -->

<!-- BEGIN mail_table_item_tpl -->
<h2>{intl-mail_headline}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-mail_date}:</th>
	<th>{intl-mail_subject}:</th>
	<th>{intl-mail_email}:</th>
</tr>

<!-- BEGIN mail_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_date}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_subject}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/mail/view/{mail_id}/">{mail_email}</a>
	</td>
</tr>
<!-- END mail_item_tpl -->
</table>

<!-- END mail_table_item_tpl -->

<br />
<form method="post" action="{www_dir}{index}/contact/company/edit/{company_id}/">

<!-- BEGIN consultation_buttons_tpl -->
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="ListConsultation" value="{intl-consultation_list}">
<input class="stdbutton" type="submit" name="NewConsultation" value="{intl-consultation}">
<!-- END consultation_buttons_tpl -->

<!-- BEGIN file_button_tpl -->
<input class="stdbutton" type="submit" name="FileButton" value="{intl-files}">
<!-- END file_button_tpl -->
<!-- BEGIN buy_button_tpl -->
<input class="stdbutton" type="submit" name="BuyButton" value="{intl-buy}">
<!-- END buy_button_tpl -->
<!-- BEGIN mail_button_tpl -->
<input class="stdbutton" type="submit" name="MailButton" value="{intl-mail}">
<!-- END mail_button_tpl -->
<!-- BEGIN company_edit_button_tpl -->
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">

<input class="okbutton" type="submit" name="Delete" value="{intl-delete}" />
<input class="okbutton" type="submit" name="Back" value="{intl-list}">
<!-- END company_edit_button_tpl -->
</form>
<!-- END company_information_tpl -->
<!-- BEGIN no_company_tpl -->
<h1>{intl-no_company_defined}{company_id}</h1>
<!-- END no_company_tpl -->
