<form method="post" action="/user/userwithaddress/new/">
<!-- BEGIN new_user_tpl -->
<h1>{intl-head_line}</h1>
<!-- END new_user_tpl -->
<!-- BEGIN edit_user_tpl -->
<h1>{intl-edit_head_line}</h1>
<!-- END edit_user_tpl -->

<hr noshade="noshade" size="1" />

<!-- BEGIN info_item_tpl -->
<ul>
    <!-- BEGIN info_updated_tpl -->
    <li>{intl-info_update_user}
    <!-- END info_updated_tpl -->
</ul>

<hr noshade="noshade" size="1"/>

<br />
<!-- END info_item_tpl -->

<!-- BEGIN errors_item_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_login_tpl -->
    <li>{intl-error_login}
    <!-- END error_login_tpl -->

    <!-- BEGIN error_login_exists_tpl -->
    <li>{intl-error_login_exists}
    <!-- END error_login_exists_tpl -->

    <!-- BEGIN error_first_name_tpl -->
    <li>{intl-error_first_name}
    <!-- END error_first_name_tpl -->

    <!-- BEGIN error_last_name_tpl -->
    <li>{intl-error_last_name}
    <!-- END error_last_name_tpl -->

    <!-- BEGIN error_email_tpl -->
    <li>{intl-error_email}
    <!-- END error_email_tpl -->

    <!-- BEGIN error_email_not_valid_tpl -->
    <li>{intl-error_email_not_valid}
    <!-- END error_email_not_valid_tpl -->

    <!-- BEGIN error_password_too_short_tpl -->
    <li>{intl-error_password_too_short}
    <!-- END error_password_too_short_tpl -->

    <!-- BEGIN error_password_match_tpl -->
    <li>{intl-error_passwordmatch_item}
    <!-- END error_password_match_tpl -->

    <!-- BEGIN error_address_street1_tpl -->
    <li>{intl-error_street1}
    <!-- END error_address_street1_tpl -->

    <!-- BEGIN error_address_street2_tpl -->
    <li>{intl-error_street2}
    <!-- END error_address_street2_tpl -->

    <!-- BEGIN error_address_zip_tpl -->
    <li>{intl-error_zip}
    <!-- END error_address_zip_tpl -->

    <!-- BEGIN error_address_place_tpl -->
    <li>{intl-error_place}
    <!-- END error_address_place_tpl -->

    <!-- BEGIN error_missing_address_tpl -->
    <li>{intl-error_missing_address}
    <!-- END error_missing_address_tpl -->

    <!-- BEGIN error_missing_country_tpl -->
    <li>{intl-error_missing_country}</li>
    <!-- END error_missing_country_tpl -->
</ul>

<hr noshade="noshade" size="1"/>

<br />
<!-- END errors_item_tpl -->

<!-- BEGIN edit_user_info_tpl -->
<h3>{intl-edit_usage}</h3>
<!-- END edit_user_info_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-firstname}:</p>
	<input type="text" size="20" name="FirstName" value="{first_name_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-lastname}:</p>
	<input type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-login}:</p>
<!-- BEGIN login_item_tpl -->
<input type="text" size="20" name="Login" value="{login_value}"/>
<!-- END login_item_tpl -->
<!-- BEGIN disabled_login_item_tpl -->
{login_value}<br />
<!-- END disabled_login_item_tpl -->

<p class="boxtext">{intl-email}:</p>
<input type="text" size="20" name="Email" value="{email_value}"/>

<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-password}:</p>
	<input type="password" size="20" name="Password" value="{password_value}"/>
	</td>
	<td>
	<p class="boxtext">{intl-verifypassword}:</p>
	<input type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-auto_cookie_login}:</p>
<input type="checkbox" name="AutoCookieLogin" {is_cookie_selected} /><br />{intl-cookie_explanation}

<!-- BEGIN address_tpl -->
<hr noshade="noshade" size="1" />
<h2>{intl-address_number} {address_number} </h2> 
<!-- BEGIN delete_address_tpl -->
<input class="okbutton" type="submit" name="DeleteAddressButton{address_id}" value="{intl-delete_this_address}">
<!-- END delete_address_tpl -->
<input type="hidden" name="AddressID[]" value="{address_id}"/>
<input style="background: #FFFFFF" {is_checked} type="radio" name="MainAddressID" value="{address_id}"> {intl-main_address}

<p class="boxtext">{intl-street1}:</p>
<input type="text" size="20" name="Street1[]" value="{street1_value}"/>

<!--
<p class="boxtext">{intl-street2}:</p>
<input type="text" size="20" name="Street2[]" value="{street2_value}"/>
-->

<p class="boxtext">{intl-zip}:</p>
<input type="text" size="20" name="Zip[]" value="{zip_value}"/>

<p class="boxtext">{intl-place}:</p>
<input type="text" size="20" name="Place[]" value="{place_value}"/>

<!-- BEGIN country_tpl -->
<p class="boxtext">{intl-country}:</p>
<select name="CountryID[]" size="5">
<!-- BEGIN country_option_tpl -->
<option {is_selected} value="{country_id}">{country_name}</option>
<!-- END country_option_tpl -->
</select>
<!-- END country_tpl -->

<!-- END address_tpl -->


<br />

<hr noshade="noshade" size="1" />

<input class="okbutton" type="submit" value="{intl-new_address}" name="NewAddress" />

<hr noshade="noshade" size="1" />


<input type="hidden" name="UserID" value="{user_id}" />
<!-- BEGIN ok_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="&nbsp;{intl-ok}&nbsp;" />
<!-- END ok_button_tpl -->
<!-- BEGIN submit_button_tpl -->
<input class="okbutton" type="submit" name="OK" value="{intl-submit}" />
<!-- END submit_button_tpl -->

<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>



