<form method="post" name="AddressForm" action="{www_dir}{index}/user/userwithaddress/{action_value}/{user_id}/#region">

<!-- BEGIN new_user_tpl -->
<h1>{intl-head_line}</h1>
<!-- END new_user_tpl -->
<!-- BEGIN edit_user_tpl -->
<h1>{intl-edit_head_line}</h1>
<!-- END edit_user_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN info_item_tpl -->
<ul>
    <!-- BEGIN info_updated_tpl -->
    <li>{intl-info_update_user}</li>
    <!-- END info_updated_tpl -->
</ul>

<hr noshade size="4"/>

<!-- END info_item_tpl -->

<!-- BEGIN errors_item_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_login_tpl -->
    <li>{intl-error_login}</li>
    <!-- END error_login_tpl -->

    <!-- BEGIN error_login_exists_tpl -->
    <li>{intl-error_login_exists}</li>
    <!-- END error_login_exists_tpl -->

    <!-- BEGIN error_first_name_tpl -->
    <li>{intl-error_first_name}</li>
    <!-- END error_first_name_tpl -->

    <!-- BEGIN error_last_name_tpl -->
    <li>{intl-error_last_name}</li>
    <!-- END error_last_name_tpl -->

    <!-- BEGIN error_email_tpl -->
    <li>{intl-error_email}</li>
    <!-- END error_email_tpl -->

    <!-- BEGIN error_email_not_valid_tpl -->
    <li>{intl-error_email_not_valid}</li>
    <!-- END error_email_not_valid_tpl -->

    <!-- BEGIN error_password_too_short_tpl -->
    <li>{intl-error_password_too_short}</li>
    <!-- END error_password_too_short_tpl -->

    <!-- BEGIN error_password_match_tpl -->
    <li>{intl-error_passwordmatch_item}</li>
    <!-- END error_password_match_tpl -->

    <!-- BEGIN error_password_not_entered_tpl -->
    <li>{intl-error_password_not_entered}</li>
	<!-- END error_password_not_entered_tpl -->

    <!-- BEGIN error_address_street1_tpl -->
    <li>{intl-error_street1}</li>
    <!-- END error_address_street1_tpl -->

    <!-- BEGIN error_address_street2_tpl -->
    <li>{intl-error_street2}</li>
    <!-- END error_address_street2_tpl -->

    <!-- BEGIN error_address_zip_tpl -->
    <li>{intl-error_zip}</li>
    <!-- END error_address_zip_tpl -->

    <!-- BEGIN error_address_place_tpl -->
    <li>{intl-error_place}</li>
    <!-- END error_address_place_tpl -->

    <!-- BEGIN error_missing_address_tpl -->
    <li>{intl-error_missing_address}</li>
    <!-- END error_missing_address_tpl -->

    <!-- BEGIN error_missing_country_tpl -->
    <li>{intl-error_missing_country}</li>
    <!-- END error_missing_country_tpl -->
	
    <!-- BEGIN error_missing_region_tpl -->
    <li>{intl-error_missing_region}</li>
    <!-- END error_missing_region_tpl -->
    
    <!-- BEGIN error_missing_company_tpl -->
    <li>{intl-error_missing_company}</li>
    <!-- END error_missing_company_tpl -->
    <!-- BEGIN error_missing_phone_tpl -->
    <li>{intl-error_missing_phone}</li>
    <!-- END error_missing_phone_tpl -->
    <!-- BEGIN error_missing_online_tpl -->
    <li>{intl-error_missing_online}</li>
    <!-- END error_missing_online_tpl -->
</ul>

<hr noshade size="4"/>
<!-- END errors_item_tpl -->

<!-- BEGIN edit_user_info_tpl -->
<p>{intl-edit_usage}</p>
<!-- END edit_user_info_tpl -->

<br />

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td valign="top">
	<span class="boxtext">{intl-firstname}:</span><br />
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
	<td valign="top">
	<span class="boxtext">{intl-lastname}:</span><br />
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
<tr>
        <!-- BEGIN companies_tpl -->
	<td colspan="1" valign="top">
 	<span class="boxtext">{intl-companyname}:</span><br />
        <select multiple size="7" name="CompanyID[]">
        <!-- BEGIN company_select_tpl -->
        <option value="{company_id}" {is_selected}>{company_level}{company_name}</option>
        <!-- END company_select_tpl -->
        </select>
	</td>
        <!-- END companies_tpl -->

       	<td colspan="1" valign="top">
	<div style="padding-top: 10px; padding-bottom: 5px;"><span class="boxtext">{intl-login}:</span><br />
	<!-- BEGIN login_item_tpl -->
	<br /><br /><input type="text" size="20" name="Login" value="{login_value}"/>
	<!-- END login_item_tpl -->
	<!-- BEGIN disabled_login_item_tpl -->
	{login_value}<br />
	<!-- END disabled_login_item_tpl -->
	</div>

	<br />

	<span class="boxtext">{intl-email}:</span><br />
	<input type="text" size="20" name="Email" value="{email_value}"/>
	</td>

	<!-- BEGIN company_name_single_tpl -->
        <td colspan="2">
        <span class="boxtext">{intl-companyname}:</span><br />
	<input type="text" size="20" name="CompanyName" value="{company_name_value}"/>	
        </td>
	<!-- END company_name_single_tpl -->
</tr>
<tr>
	<td style="" valign="top">
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
	<td valign="top">
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
</table>

<!-- BEGIN address_tpl -->

<h2>{intl-address_number} {address_number} </h2> 
<input type="hidden" name="AddressArrayID[]" value="{address_id}">

<!-- 
<p class="boxtext">{intl-address_pos}&nbsp;{address_position}:</p>
-->
<p><select name="AddressTypeID[]">
 <option value="-1">{intl-unknown_type}</option>
 <!-- BEGIN address_item_select_tpl -->
 <option value="{type_id}" {selected}>{type_name}</option>
 <!-- END address_item_select_tpl -->
 </select>
<!--
<input type="checkbox" name="AddressDelete[]" value="{address_index}" />
<span class="boxtext">{intl-delete}</span><br />
-->
</p>

<!-- BEGIN main_address_tpl -->
<input {is_checked} type="radio" name="MainAddressID" value="{address_id}"><span class="check">{intl-main_address}</span>
<!-- END main_address_tpl -->

<!-- BEGIN delete_address_tpl -->
<input type="checkbox" name="DeleteAddressArrayID[]" value="{address_id}">
<span class="check">{intl-delete}</span>
<!-- END delete_address_tpl -->
<input type="hidden" name="AddressID[]" value="{address_id}"/>
<input type="hidden" name="RealAddressID[]" value="{real_address_id}"/>

<p class="boxtext">{intl-street}:</p>
<input type="text" size="20" name="Street1[]" value="{street1_value}"/><br />
<input type="text" size="20" name="Street2[]" value="{street2_value}"/>


<p class="boxtext">{intl-place}:</p>
<input type="text" size="20" name="Place[]" value="{place_value}"/>

<!-- BEGIN region_tpl -->
<p class="boxtext"><a href="#" name="region"></a>{intl-region}:</p>
<select name="RegionID[{specialFormId}]" size="5">
<!-- BEGIN region_option_tpl -->
<option {is_selected} value="{region_id}">{region_name}</option>
<!-- END region_option_tpl -->
</select>
<!-- END region_tpl -->
<!-- BEGIN region_line_tpl -->
<p class="boxtext">{intl-region}:</p>
        <input type="text" size="20" name="RegionID" value="{region_name}"/>
        <input type="hidden" name="RegionID" value="{region_id}" />
        <input type="hidden" name="RegionName" value="{region_name}" />
</select>
<!-- END region_line_tpl -->

<p class="boxtext">{intl-zip}:</p>
<input type="text" size="20" name="Zip[]" value="{zip_value}"/>

<!-- BEGIN country_tpl -->
<p class="boxtext">{intl-country}:</p>
<select name="CountryID[]" size="5" onchange="document.AddressForm.submit()">
<!-- BEGIN country_option_tpl -->
<option {is_selected} value="{country_id}">{country_name}</option>
<!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->

<!-- END address_tpl -->

<h2>{intl-telephone_headline}</h2>
<p>{intl-telephone_information}</p>
<p>{intl-telephone_ignore_information}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN phone_table_item_tpl -->
<tr>
    <!-- BEGIN phone_item_tpl -->
    <td valign="top">
        <p class="boxtext">{intl-phone_pos}&nbsp;{phone_position}:</p>
        <select name="PhoneTypeID[]">
            <option value="-1">{intl-unknown_type}</option>
            <!-- BEGIN phone_item_select_tpl -->
            <option value="{type_id}" {selected}>{type_name}</option>
            <!-- END phone_item_select_tpl -->

            </select><br />
        <input type="text" class="halfbox" size="20" name="Phone[]" value="{phone_number}" />
        <input type="hidden" name="PhoneID[]" value="{phone_id}" /><br />
        <input type="checkbox" name="PhoneDelete[]" value="{phone_index}" />
        <span class="boxtext">{intl-delete}</span>
    </td>
    <!-- END phone_item_tpl -->
</tr>
<!-- END phone_table_item_tpl -->
</table>

<h2>{intl-online_headline}</h2>
<p>{intl-online_information}</p>
<p>{intl-online_ignore_information}</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN online_table_item_tpl -->
<tr>
    <!-- BEGIN online_item_tpl -->
    <td valign="top">
        <p class="boxtext">{intl-online_pos}&nbsp;{online_position}:</p>
        <select name="OnlineTypeID[]">
            <option value="-1">{intl-unknown_type}</option>
            <!-- BEGIN online_item_select_tpl -->
            <option value="{type_id}" {selected}>{type_name}</option>
            <!-- END online_item_select_tpl -->

            </select><br />
        <input type="text" class="halfbox" size="20" name="Online[]" value="{online_value}" />
        <input type="hidden" name="OnlineID[]" value="{online_id}"><br />
        <input type="checkbox" name="OnlineDelete[]" value="{online_index}" />
        <span class="boxtext">{intl-delete}</span>
    </td>
    <!-- END online_item_tpl -->
</tr>
<!-- END online_table_item_tpl -->
</table>

<h2>{intl-optionslist}</h2>
<div class="boxtext"><input {deadline_reminder} type="checkbox" name="DeadlineReminder" />&nbsp;{intl-deadlinereminder}</div>
<div class="boxtext"><input {info_subscription} type="checkbox" name="InfoSubscription" />&nbsp;{intl-infosubscription}</div>
<div class="boxtext"><input {info_disclaimer} type="checkbox" name="InfoDisclaimer" />&nbsp;{intl-infodisclaimer}</div>

<br />

<!-- BEGIN address_actions_tpl -->
<!-- END address_actions_tpl -->

<input type="hidden" name="UserID" value="{user_id}" />
<input type="hidden" name="PersonID" value="{person_id}" />
<!-- BEGIN ok_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<!-- END ok_button_tpl -->
<!-- BEGIN submit_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="{intl-submit}" />
<!-- END submit_button_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-new_address}" name="NewAddress" />
<input class="stdbutton" type="submit" value="{intl-delete_address}" name="DeleteAddress" />


<hr noshade="noshade" size="4" />

<input type="hidden" name="GlobalSectionIDOverride" value="{global_section_id}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>




