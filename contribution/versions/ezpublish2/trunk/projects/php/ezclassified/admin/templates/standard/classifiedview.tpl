
<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />
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
<div class="p">{street1}</div>
<div class="p">{street2}</div>
<div class="p">{zip} {place}</div>
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
<div class="p">{company_description}</div><br />

<!-- END company_view_tpl -->

<h2>Stillingsinformasjon</h2>

<p class="boxtext">{intl-title}:</p>
<div class="p">{classified_title}</div>

<p class="boxtext">{intl-duedate}:</p>
<div class="p">{classified_duedate}</div>

<p class="boxtext">{intl-position_type}:</p>
{classified_position_type}

<p class="boxtext">{intl-initiate_type}:</p>
{classified_initiate_type}

<p class="boxtext">{intl-description}:</p>
<div class="p">{classified_description}</div>

<p class="boxtext">{intl-contact_persons}:</p>
<!-- BEGIN person_item_tpl -->
<p>
Name: {person_name}<br />
Title: {person_title}<br />
<!-- BEGIN person_mail_item_tpl -->
Mail: <a href="mailto:{person_mail}">{person_mail}</a><br />
<!-- END person_mail_item_tpl -->
<!-- BEGIN person_phone_item_tpl -->
Phone: {person_phone}<br />
<!-- END person_phone_item_tpl -->
<!-- BEGIN person_fax_item_tpl -->
Fax: {person_fax}<br />
<!-- END person_fax_item_tpl -->
</p>
<!-- END person_item_tpl -->
<!-- BEGIN no_person_item_tpl -->
{intl-no_persons}
<!-- END no_person_item_tpl -->

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-pay}:</p>
	{classified_pay}
	<br /><br />
	</td>
	<td>
	<p class="boxtext">{intl-worktime}:</p>
	{classified_worktime}
	<br /><br />
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-duration}:</p>
	{classified_duration}
	</td>
	<td>
	<p class="boxtext">{intl-workplace}:</p>
	{classified_workplace}
	</td>
</tr>
</table>

<form method="post" action="/classified/edit/{classified_id}/">

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="Edit" value="{intl-edit}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Delete" value="{intl-delete}">
	</td>
</tr>
</table>

</form>