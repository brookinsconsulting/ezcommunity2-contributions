<form method="post" action="/contact/company/{action_value}/{company_id}/" enctype="multipart/form-data">

<!-- BEGIN edit_tpl -->

<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_name_item_tpl -->
<li>{intl-error_name}
<!-- END error_name_item_tpl -->

<!-- BEGIN error_address_item_tpl -->
<li>{intl-error_address}{error_address_position}
<!-- END error_address_item_tpl -->

<!-- BEGIN error_phone_item_tpl -->
<li>{intl-error_phone}{error_phone_position}
<!-- END error_phone_item_tpl -->

<!-- BEGIN error_online_item_tpl -->
<li>{intl-error_online}{error_online_position}
<!-- END error_online_item_tpl -->

</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->

<!-- BEGIN company_item_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
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

<table>
<tr>

<td>
<p class="boxtext">{intl-companytype}:</p>
<select multiple size="10" name="CompanyCategoryID[]">
<!-- BEGIN company_type_select_tpl -->
<option value="{company_type_id}" {is_selected}>{company_type_level}{company_type_name}</option>
<!-- END company_type_select_tpl -->
</select>
</td>

<td>
<p class="boxtext">{intl-comment}:</p>
<textarea cols="40" rows="8" name="Comment">{comment}</textarea>
</td>

</tr>
</table>

<!-- END company_item_tpl -->

<!-- BEGIN address_item_tpl -->
<h2>{intl-address_headline}</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN address_table_item_tpl -->
<tr>
	<td>
	    <p class="boxtext">{intl-address_pos} {address_position}</p>
	</td>
</tr>
<tr>
	<td colspan="2">
	<p><select name="AddressTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN address_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END address_item_select_tpl -->

	    </select>
	<input type="checkbox" name="AddressDelete[]" value="{address_index}"/>
	<span class="boxtext">{intl-delete}</span><br />
        </p>
	<p class="boxtext">{intl-address}:</p>
	<input type="text" size="40" name="Street1[]" value="{street1}"/><br>
	<input type="text" size="40" name="Street2[]" value="{street2}"/>
	</td>
</tr>

<tr>
	<td width="1%">
        <p class="boxtext">{intl-zip}:</p>
        <input type="text" size="4" name="Zip[]" value="{zip}"/>
	</td>
	<td>
        <p class="boxtext">{intl-place}:</p>
        <input type="text" size="20" name="Place[]" value="{place}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
        <p class="boxtext">{intl-country}:</p>
        <select size="4" name="Country[]" value="{zip}"/>
	    <!-- BEGIN country_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END country_item_select_tpl -->
	</select>
	</td>
</tr>
<!-- END address_table_item_tpl -->
</table>
<!-- END address_item_tpl -->

<h2>{intl-telephone_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN phone_table_item_tpl -->
<tr>
    <!-- BEGIN phone_item_tpl -->
    <td>
	<p class="boxtext">{intl-phone_pos} {phone_position}</p>
	<p><select name="PhoneTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN phone_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END phone_item_select_tpl -->

	    </select>
        </p>
        <input type="text" size="20" name="Phone[]" value="{phone_number}"/>
        <input type="hidden" name="PhoneID[]" value="{phone_id}" /><br />
	<input type="checkbox" name="PhoneDelete[]" value="{phone_index}"/>
	<span class="boxtext">{intl-delete}</span><br />
    </td>
    <!-- END phone_item_tpl -->
</tr>
<!-- END phone_table_item_tpl -->
</table>

<h2>{intl-online_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN online_table_item_tpl -->
<tr>
    <!-- BEGIN online_item_tpl -->
    <td>
	<p class="boxtext">{intl-online_pos} {online_position}</p>
	<p><select name="OnlineTypeID[]">
	    <option value="-1">{intl-unknown_type}</option>
	    <!-- BEGIN online_item_select_tpl -->
	    <option value="{type_id}" {selected}>{type_name}</option>
	    <!-- END online_item_select_tpl -->

	    </select>
        </p>
        <input type="text" size="20" name="Online[]" value="{online_value}"/>
        <input type="hidden" name="OnlineID[]" value="{online_id}"><br />
	<input type="checkbox" name="OnlineDelete[]" value="{online_index}"/>
	<span class="boxtext">{intl-delete}</span><br />
    </td>
    <!-- END online_item_tpl -->
</tr>
<!-- END online_table_item_tpl -->
</table>

<!-- BEGIN project_item_tpl -->
<h2>{intl-project_headline}</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td width="1%" valign="top">
	            <p class="boxtext">{intl-contact}:</p>
		    <select size="10" name="ContactID" />
		    <!-- BEGIN contact_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_lastname}, {type_firstname}</option>
		    <!-- END contact_item_select_tpl -->
		    </select>
	    </td>
	    <td width="1%" valign="top">
	            <p class="boxtext">{intl-contact_group}:</p>
		    <p>
		    <select name="ContactGroupID" />
		    <option value="-1">{intl-group_all}</option>
		    <!-- BEGIN contact_group_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END contact_group_item_select_tpl -->
		    </select>
		    </p>
		    <input type="submit" name="RefreshUsers" value="{intl-refresh}">
	    </td>

	    <td width="1%" valign="top">
	            <p class="boxtext">{intl-state}:</p>
		    <select name="ProjectID" />
		    <option value="-1">{intl-no_state}</option>
		    <!-- BEGIN project_item_select_tpl -->
		    <option value="{type_id}" {selected}>{type_name}</option>
		    <!-- END project_item_select_tpl -->
		    </select>
	    </td>
    </tr>
</table>

<!-- END project_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
	<h2>{intl-logo}</h2>
<!-- BEGIN logo_add_tpl -->
	<p class="boxtext">{intl-logo}:</p>
	<input size="20" name="logo" type="file" />
<!-- END logo_add_tpl -->

<!-- BEGIN logo_edit_tpl -->
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<!--       <p class="boxtext">{logo_name}:</p> -->
    <td>
    <img src="{logo_image_src}" width="{logo_image_width}" height="{logo_image_height}" border="0" alt="{image_alt}" /><br /><br />
   	<p class="boxtext">{intl-logo}:</p>
	<input size="20" name="logo" type="file" />
	<input type="hidden" name="LogoImageID" value="{logo_id}">
	<br /><br />
	<p class="boxtext">{intl-delete}: <input name="DeleteLogo" type="checkbox" /></p>
    </td>
<tr>
</table>
<!-- END logo_edit_tpl -->

	</td>
	<td valign="top">
	<h2>{intl-image}</h2>

<!-- BEGIN image_add_tpl -->
	<p class="boxtext">{intl-image}:</p>
	<input size="20" name="image" type="file" />
<!-- END image_add_tpl -->

<!-- BEGIN image_edit_tpl -->
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<!--       <p class="boxtext">{image_name}:</p> -->
    <td>
    <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /><br /><br />
  	<p class="boxtext">{intl-image}:</p>
	<input size="20" name="image" type="file" />
	<input type="hidden" name="CompanyImageID" value="{image_id}">
	<br /><br />
	<p class="boxtext">{intl-delete}: <input name="DeleteImage" type="checkbox" /></p>
    </td>
<tr>
</table>

<!-- END image_edit_tpl -->

	</td>
</tr>
</table>

<br />

<input type="hidden" name="CompanyID" value="{company_id}">

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input type="submit" name="NewAddress" value="{intl-new_address}">
	</td>
	<td>
	<input type="submit" name="NewPhone" value="{intl-new_phone}">
	</td>
	<td>
	<input type="submit" name="NewOnline" value="{intl-new_online}">
	</td>
	<td>
	<input type="submit" name="DeleteMarked" value="{intl-delete_marked}">
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Update" value="{intl-update}">
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}">
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />

<!-- END edit_tpl -->

<!-- BEGIN confirm_tpl -->

<h1>{intl-confirm_headline}</h1>

<hr noshade size="4"/>

<br />

<h2>{intl-confirm_delete}{name}{intl-confirm_delete_end}</h2>

<input type="hidden" name="Confirm" value="confirm">

<!-- END confirm_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input type="submit" name="Back" value="{intl-back}">

</form>

