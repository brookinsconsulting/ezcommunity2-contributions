<h1>{name}</h1>

<hr noshade size="4"/>

<br />

<p class="boxtext">{intl-logo}:</p>
<!-- BEGIN no_logo_tpl -->
<p>{intl-no_logo}</p>
<!-- END no_logo_tpl -->
<!-- BEGIN logo_view_tpl -->
       <p class="boxtext">{logo_name}</p>
       <img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END logo_view_tpl -->


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	{name}
	</td>
	<td>
	<p class="boxtext">{intl-orgno}:</p>
	{companyno}
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<p>
{description}
</p>

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
{street1}
{street2}

<p class="boxtext">{intl-zip}:</p>
{zip}

<p class="boxtext">{intl-place}:</p>
{place}
<!-- END address_item_tpl -->


<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
{telephone}
<!-- END phone_item_tpl -->

<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{fax}
<!-- END fax_item_tpl -->

<!-- BEGIN web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
{web}
<!-- END web_item_tpl -->

<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
{email}
<!-- END email_item_tpl -->

<!-- BEGIN no_logo_tpl -->
<p>{intl-no_logo}</p>
<!-- END no_logo_tpl -->

<!-- BEGIN image_view_tpl -->
     <p class="boxtext">{intl-company_image}:</p>
       <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_view_tpl -->

<form method="post" action="/contact/company/http/{company_id}/" enctype="multipart/form-data">
<input class="okbutton" type="submit" name="Edit" value="{intl-edit}" />
<input class="okbutton" type="submit" name="Delete" value="{intl-delete}" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

</form>
