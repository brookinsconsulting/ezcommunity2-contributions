<form method="post" action="/contact/companyedit/{action_value}/{company_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="20" name="Name" value="{name}"/>
	</td>
	<td>
	<p class="boxtext">{intl-orgno}:</p>
	<input type="text" size="20" name="CompanyNo" value="{companyno}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{description}</textarea>

<p class="boxtext">{intl-companytype}:</p>

<select name="CompanyTypeID">
<!-- BEGIN company_type_select_tpl -->
<option value="{company_type_id}" {is_selected}>{company_type_name}</option>
<!-- END company_type_select_tpl -->
</select>


<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
<input type="text" size="20" name="Street1" value="{street1}"/><br>
<input type="text" size="20" name="Street2" value="{street2}"/>

<p class="boxtext">{intl-zip}:</p>
<input type="text" size="4" name="Zip" value="{zip}"/>

<p class="boxtext">{intl-place}:</p>
<input type="text" size="20" name="Place" value="{place}"/>
<!-- END address_item_tpl -->


<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
<input type="text" size="20" name="Phone[]" value="{telephone}"/>
<input type="hidden" name="PhoneTypeID[]" value="1">
<input type="hidden" name="PhoneID[]" value="{tele_phone_id}">
<!-- END phone_item_tpl -->

<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
<input type="text" size="20" name="Phone[]" value="{fax}"/>
<input type="hidden" name="PhoneTypeID[]" value="2">
<input type="hidden" name="PhoneID[]" value="{fax_phone_id}">
<!-- END fax_item_tpl -->

<!-- BEGIN web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
<input type="text" size="20" name="Online[]" value="{web}"/>
<input type="hidden" name="URLType[]" value="http">
<input type="hidden" name="OnlineTypeID[]" value="1">
<input type="hidden" name="OnlineID[]" value="{web_online_id}">
<!-- END web_item_tpl -->

<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
<input type="text" size="20" name="Online[]" value="{email}"/>
<input type="hidden" name="URLType[]" value="mailto">
<input type="hidden" name="OnlineTypeID[]" value="2">
<input type="hidden" name="OnlineID[]" value="{email_online_id}">
<!-- END email_item_tpl -->



<!-- BEGIN logo_add_tpl -->
	<p class="boxtext">{intl-logo}</p>
	<input size="40" name="userfile" type="file" />
<!-- END logo_add_tpl -->

<!-- BEGIN image_add_tpl -->
	<p class="boxtext">{intl-image}</p>
	<input size="40" name="CompanyImage" type="file" />
<!-- END image_add_tpl -->

<!-- BEGIN logo_delete_tpl -->
<img src="{logo_src}">{image_text}</img>
<!-- END logo_delete_tpl -->

<!-- BEGIN image_delete_tpl -->
<img src="{image_src}">{image_text}</img>
<!-- END image_delete_tpl -->

<br />

<hr noshade size="4"/>

<input type="hidden" name="UserID" value="{user_id}" />
<input class="okbutton" type="submit" value="OK" />
</form>

<form method="post" action="/contact/companylist/">
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

