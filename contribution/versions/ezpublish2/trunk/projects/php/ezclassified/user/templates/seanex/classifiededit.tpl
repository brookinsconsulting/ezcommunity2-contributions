
<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />
<!-- BEGIN company_view_tpl -->
<h2>Stilling annonse til firma: {company_name}</h2>
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

<h2>Stilling annonse informasjon</h2>

<form method="post" action="/classified/classifiededit/{action_value}/{classified_id}/">

<p class="boxtext">{intl-name}:</p>
<input type="text" size="20" name="Name" value="{classified_name}"/>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{classified_description}</textarea>

<p class="boxtext">{intl-contact_person}:</p>
<textarea cols="40" rows="8" name="ContactPerson">{classified_contact_person}</textarea>

<p class="boxtext">{intl-pay}:</p>
<input type="text" size="20" name="Pay" value="{classified_pay}"/>

<p class="boxtext">{intl-worktime}:</p>
<input type="text" size="20" name="WorkTime" value="{classified_worktime}"/>

<p class="boxtext">{intl-duration}:</p>
<input type="text" size="20" name="Duration" value="{classified_duration}"/>

<p class="boxtext">{intl-workplace}:</p>
<input type="text" size="20" name="WorkPlace" value="{classified_workplace}"/>

<p class="boxtext">{intl-category}:</p>
<select multiple size="10" name="CategoryArray[]">
<!-- BEGIN category_item_tpl -->
<option value="{category_id}" {is_selected}>{category_name}</option>
<!-- END category_item_tpl -->
</select>
<br />

<input type="hidden" value="{company_id}" name="CompanyID">
<input type="submit" value="{intl-ok}">

</form>