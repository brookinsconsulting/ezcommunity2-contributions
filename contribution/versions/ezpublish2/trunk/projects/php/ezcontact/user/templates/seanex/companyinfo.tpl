<h1>{headline}</h1>

<hr noshade size="4"/>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	{company_name}
	</td>
	<td>
	<p class="boxtext">{intl-orgno}:</p>
	{company_no}
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<p>{company_comment}

<p class="boxtext">{intl-address}:</p>
{street1}
{street2}

<p class="boxtext">{intl-zip}:</p>
{zip}

<p class="boxtext">{intl-place}:</p>
{place}

<p class="boxtext">{intl-telephone}:</p>
{telephone}

<p class="boxtext">{intl-fax}:</p>
{fax}

<p class="boxtext">{intl-web}:</p>
{web}

<p class="boxtext">{intl-email}:</p>
{email}

<!-- BEGIN image_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{image_name}</p>
    <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
    </td>
<tr>
</table>
<!-- END image_tpl -->

<!-- BEGIN logo_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{logo_name}</p>
    <img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
    </td>
<tr>
</table>
<!-- END logo_tpl -->


