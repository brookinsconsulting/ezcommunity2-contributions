<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{intl-headline}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="/contact/search/company" method="post">
        <input type="text" name="SearchText" size="12" />       
        <input type="submit" value="{intl-search}" />
        </form>
        </td>
</tr>
</table>

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
<!-- BEGIN no_phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
{intl-no_telephone}
<!-- END no_phone_item_tpl -->
	</td>
	<td>
<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{fax}
<!-- END fax_item_tpl -->
<!-- BEGIN no_fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{intl-no_fax}
<!-- END no_fax_item_tpl -->
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
<!-- BEGIN no_web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
{intl-no_web}
<!-- END no_web_item_tpl -->
	</td>
	<td>
<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
<a href="mailto:{email}">{email}</a>
<!-- END email_item_tpl -->
<!-- BEGIN no_email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
{intl-no_email}
<!-- END no_email_item_tpl -->
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
{company_description}<br /><br />

<!-- END company_view_tpl -->

<h2>Stillingsinformasjon</h2>

<p class="boxtext">{intl-title}:</p>
{classified_title}

<p class="boxtext">{intl-position_type}:</p>
{classified_position_type}

<p class="boxtext">{intl-initiate_type}:</p>
{classified_initiate_type}

<p class="boxtext">{intl-description}:</p>
{classified_description}

<p class="boxtext">{intl-contact_person}:</p>
{classified_contact_person}

<p class="boxtext">{intl-pay}:</p>
{classified_pay}

<p class="boxtext">{intl-worktime}:</p>
{classified_worktime}

<p class="boxtext">{intl-duration}:</p>
{classified_duration}

<p class="boxtext">{intl-workplace}:</p>
{classified_workplace}

