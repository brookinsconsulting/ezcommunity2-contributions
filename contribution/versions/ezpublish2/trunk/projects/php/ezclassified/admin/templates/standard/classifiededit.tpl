<form method="post" action="/classified/{action_value}/{classified_id}/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<!-- BEGIN company_select_tpl -->
<p class="boxtext">{intl-company}:</p>
<select single size="10" name="CompanyID">
<!-- BEGIN company_item_tpl -->
<option value="{company_id}" {is_selected}>{company_name}</option>
<!-- END company_item_tpl -->
</select>
<!-- END company_select_tpl -->

<!-- BEGIN company_view_tpl -->
<h2>Firma: {company_name}</h2>
<!-- <p class="boxtext">{intl-logo}:</p> -->
<!-- BEGIN no_logo_tpl -->
<!-- <p>{intl-no_logo}</p> -->
<!-- END no_logo_tpl -->

<!-- BEGIN logo_view_tpl -->
<img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />
<!-- END logo_view_tpl -->

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
{street1}<br/>
{street2}<br />
{zip} {place}<br />
<!-- END address_item_tpl -->

<br clear="all" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
{telephone}
<!-- END phone_item_tpl -->
	</td>
	<td>
<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{fax}
<!-- END fax_item_tpl -->
	</td>
</tr>
</table>

<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
<!-- BEGIN web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
<a href="http://{web}">{web}</a>
<!-- END web_item_tpl -->
	</td>
	<td>
<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
<a href="mailto:{email}">{email}</a>
<!-- END email_item_tpl -->
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
{company_description}<br /><br />

<!-- END company_view_tpl -->

<h2>Stillingsinformasjon</h2>

<p class="boxtext">{intl-title}:</p>
<input type="text" size="20" name="Title" value="{classified_title}"/>

<p class="boxtext">{intl-category}:</p>
<select multiple size="10" name="CategoryArray[]">
<!-- BEGIN category_item_tpl -->
<option value="{category_id}" {is_selected}>{category_level}{category_name}</option>
<!-- END category_item_tpl -->
</select>

<p class="boxtext">{intl-position_type}:</p>
<select single size="10" name="PositionType">
<!-- BEGIN position_type_item_tpl -->
<option value="{position_type_id}" {is_selected}>{position_name}</option>
<!-- END position_type_item_tpl -->
</select>

<p class="boxtext">{intl-initiate_type}:</p>
<select single size="10" name="InitiateType">
<!-- BEGIN initiate_type_item_tpl -->
<option value="{initiate_type_id}" {is_selected}>{initiate_name}</option>
<!-- END initiate_type_item_tpl -->
</select>
<br />

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{classified_description}</textarea>

<p class="boxtext">{intl-contact_persons}:</p>
<!-- <textarea cols="40" rows="8" name="ContactPerson">{classified_contact_person}</textarea> -->
<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-pay}:</p>
<!-- BEGIN classified_pay_edit_tpl -->
	<input type="text" size="20" name="Pay" value="{classified_pay}"/>
<!-- END classified_pay_edit_tpl -->
<!-- BEGIN classified_pay_edit_def_tpl -->
	<input type="text" size="20" name="Pay" value="{intl-pay_default}"/>
<!-- END classified_pay_edit_def_tpl -->
	<br /><br />
	</td>
	<td>
	<p class="boxtext">{intl-duration}:</p>
	<input type="text" size="20" name="Duration" value="{classified_duration}"/>
	<br /><br />
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-worktime}:</p>
	<input type="text" size="20" name="WorkTime" value="{classified_worktime}"/>
	</td>
	<td>
	<p class="boxtext">{intl-workplace}:</p>
	<input type="text" size="20" name="WorkPlace" value="{classified_workplace}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-validUntil}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="small">
	År:<br /> 
	<input type="text" size="5" name="Year" value="{classified_year}"/>&nbsp;&nbsp;
	</td>
	<td class="small">
	Måned:<br />
	<input type="text" size="3" name="Month" value="{classified_month}"/>&nbsp;&nbsp;
	</td>
	<td class="small">
	Dag:<br />
	<input type="text" size="3" name="Day" value="{classified_day}"/>&nbsp;&nbsp;
	</td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-duedate}:</p>
	<input type="text" size="20" name="DueDate" value="{classified_duedate}"/>
	<br /><br />
	</td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-reference}:</p>
	<input type="text" size="20" name="Reference" value="{classified_reference}"/>
	<br /><br />
	</td>
</tr>
</table>
<br /><br />

<input type="hidden" value="{classified_id}" name="PositionID">
<!-- <input type="hidden" value="{company_id}" name="CompanyID"> -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}">
<!-- BEGIN delete_button_tpl -->
<input class="okbutton" type="submit" Name="Delete" value="{intl-delete}">
<!-- END delete_button_tpl -->
</form>