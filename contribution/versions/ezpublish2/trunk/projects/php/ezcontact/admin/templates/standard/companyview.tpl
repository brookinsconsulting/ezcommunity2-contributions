<h1>{name}</h1>

<hr noshade size="4"/>

<br />

<!-- <p class="boxtext">{intl-logo}:</p> -->

<!-- BEGIN no_logo_tpl -->
<p>{intl-no_logo}</p>
<!-- END no_logo_tpl -->

<!-- BEGIN logo_view_tpl -->
<!--       <p class="boxtext">{logo_name}</p> -->
<img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />
<!-- END logo_view_tpl -->


<p class="boxtext">{intl-name}:</p>
<span class="text">
{name}
</span>

<p class="boxtext">{intl-orgno}:</p>
<span class="text">
{companyno}
</span>

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
<span class="text">
{street1}<br />
{street2}
</span>

<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-zip}:</p>
	<span class="text">
	{zip}
	</span>
	</td>
	<td>
	<p class="boxtext">{intl-place}:</p>
	<span class="text">
	{place}
	</span>
	</td>
</tr>
</table>
<!-- END address_item_tpl -->

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">

<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
<span class="text">
{telephone}
</span>
<!-- END phone_item_tpl -->

	</td>
	<td>
	
<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
<span class="text">
{fax}
</span>
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
<span class="text">
{web}
</span>
<!-- END web_item_tpl -->

	</td>
	<td>

<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
<span class="text">
{email}
</span>
<!-- END email_item_tpl -->

	</td>
</tr>
</table>

<!-- BEGIN no_image_tpl -->
<p>{intl-no_logo}</p>
<!-- END no_image_tpl -->

<!-- BEGIN image_view_tpl -->
<!--     <p class="boxtext">{intl-company_image}:</p>  -->
       <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />
<!-- END image_view_tpl -->

<p class="boxtext">{intl-description}:</p>
<span class="text">
{description}
</span>

<form method="post" action="/contact/company/http/{company_id}/" enctype="multipart/form-data">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Edit" value="{intl-edit}" />
<input class="okbutton" type="submit" name="Delete" value="{intl-delete}" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

</form>
