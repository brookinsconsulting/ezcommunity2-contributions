<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Brukerinfo</span> | {intl-edit_headline}</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<form method="post" action="/contact/person/{action_value}/{person_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_firstname_item_tpl -->
<li>{intl-error_firstname}
<!-- END error_firstname_item_tpl -->

<!-- BEGIN error_lastname_item_tpl -->
<li>{intl-error_lastname}
<!-- END error_lastname_item_tpl -->

<!-- BEGIN error_birthdate_item_tpl -->
<li>{intl-error_birthdate}
<!-- END error_birthdate_item_tpl -->

<!-- BEGIN error_personno_item_tpl -->
<li>{intl-error_personno}
<!-- END error_personno_item_tpl -->

<!-- BEGIN error_loginname_item_tpl -->
<li>{intl-error_loginname}
<!-- END error_loginname_item_tpl -->

<!-- BEGIN error_password_item_tpl -->
<li>{intl-error_password}
<!-- END error_password_item_tpl -->

<!-- BEGIN error_password_too_short_item_tpl -->
<li>{intl-error_password_too_short}
<!-- END error_password_too_short_item_tpl -->

<!-- BEGIN error_email_not_valid_item_tpl -->
<li>{intl-error_email_not_valid_item}
<!-- END error_email_not_valid_item_tpl -->

<!-- BEGIN error_passwordrepeat_item_tpl -->
<li>{intl-error_passwordrepeat}
<!-- END error_passwordrepeat_item_tpl -->

<!-- BEGIN error_passwordmatch_item_tpl -->
<li>{intl-error_passwordmatch}
<!-- END error_passwordmatch_item_tpl -->

<!-- BEGIN error_email_item_tpl -->
<li>{intl-error_email}
<!-- END error_email_item_tpl -->

<!-- BEGIN error_address_item_tpl -->
<li>{intl-error_address}
<!-- END error_address_item_tpl -->

<!-- BEGIN error_userexists_item_tpl -->
<li>{intl-error_loginname_exists}
<!-- END error_userexists_item_tpl -->

</ul>
<!-- END errors_tpl -->
 
<!-- BEGIN person_item_tpl -->
<h2>{intl-personal_headline}</h2>
<p class="boxtext">{intl-required_marked}.</p>
<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td width="50%">
	    <p class="boxtext"><span class="redstar">*</span> {intl-firstname}:</p>
	    <input type="text" size="20" name="FirstName" value="{firstname}"/>
	    </td>
	    <td width="50%">
	    <p class="boxtext"><span class="redstar">*</span> {intl-lastname}:</p>
	    <input type="text" size="20" name="LastName" value="{lastname}"/>
	    </td>
    </tr>
</table>

<p class="boxtext"><span class="redstar">*</span> {intl-birthday_headline}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="50%" valign="bottom">
        <table cellpadding="0" cellspacing="0" border="0">
        <tr valign="bottom">
            <td>
                <div class="small">{intl-year}:</div>
                <input type="text" size="4" name="BirthYear" value="{birthyear}"/>&nbsp;&nbsp;
            </td>
			<td>&nbsp;</td>
            <td>
                <div class="small">{intl-month}:</div>
                <input type="text" size="2" name="BirthMonth" value="{birthmonth}"/>&nbsp;&nbsp;
            </td>
			<td>&nbsp;</td>
            <td>
                <div class="small">{intl-day}:</div>
                <input type="text" size="2" name="BirthDay" value="{birthday}"/>&nbsp;&nbsp;
            </td>
        </tr>
        </table>
    </td>
    <td width="50%">
        &nbsp;
    </td>
</tr>
</table>

<!--- <p class="boxtext">{intl-comment_headline}:</p>
<textarea name="Comment" rows="4" cols="40" wrap="soft">{comment}</textarea> --->
<input type="hidden" name="ContactTypeID" value="{cv_contact_type_id}" />
<input type="hidden" name="UserID" value="{user_id}" />
<!-- END person_item_tpl -->

<!-- BEGIN address_item_tpl -->
<h2>{intl-address_headline}</h2>
<p class="boxtext"><span class="redstar">*</span> {intl-address}:</p>
<input type="text" size="30" name="Street1" value="{street1}"/><br>
<input type="text" size="30" name="Street2" value="{street2}"/>

<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
        <p class="boxtext"><span class="redstar">*</span> {intl-zip}:</p>
        <input type="text" size="4" name="Zip" value="{zip}"/>
	</td>
	<td width="50%">
        <p class="boxtext"><span class="redstar">*</span> {intl-place}:</p>
        <input type="text" size="20" name="Place" value="{place}"/>
	</td>
</tr>
</table>
<input type="hidden" name="AddressTypeID" value="{cv_address_type_id}" />
<input type="hidden" name="AddressID" value="{cv_address_id}" />
<!-- END address_item_tpl -->

<h2>{intl-telephone_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN home_phone_item_tpl -->
        <p class="boxtext">{intl-home_phone}:</p>
        <input class="limit" type="text" size="10" name="Phone[]" value="{home_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_home_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_home_phone_id}">
        <!-- END home_phone_item_tpl -->
    </td>
    <td>
        <!-- BEGIN work_phone_item_tpl -->
        <p class="boxtext">{intl-work_phone}:</p>
        <input class="limit" type="text" size="10" name="Phone[]" value="{work_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_work_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_work_phone_id}">
        <!-- END work_phone_item_tpl -->
    </td>
    <td>
        <!-- BEGIN mobile_phone_item_tpl -->
        <p class="boxtext">{intl-mobile_phone}:</p>
        <input class="limit" type="text" size="10" name="Phone[]" value="{mobile_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_mobile_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_mobile_phone_id}">
        <!-- END mobile_phone_item_tpl -->
    </td>
</tr>
</table>

<h2>{intl-online_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN email_item_tpl -->
        <p class="boxtext"><span class="redstar">*</span> {intl-email}:</p>
        <input type="text" size="20" name="Online[]" value="{email}"/>
        <input type="hidden" name="URLType[]" value="mailto">
        <input type="hidden" name="OnlineTypeID[]" value="{cv_email_online_type_id}">
        <input type="hidden" name="OnlineID[]" value="{cv_email_online_id}">
        <!-- END email_item_tpl -->
    </td>
    <td>
        <!-- BEGIN web_item_tpl -->
        <p class="boxtext">{intl-web}:</p>
        http://<input type="text" size="20" name="Online[]" value="{web}"/>
        <input type="hidden" name="URLType[]" value="http">
        <input type="hidden" name="OnlineTypeID[]" value="{cv_web_online_type_id}">
        <input type="hidden" name="OnlineID[]" value="{cv_web_online_id}">
        <!-- END web_item_tpl -->
    </td>
</tr>
</table>

<!-- BEGIN password_item_tpl -->
<h2>{intl-password_headline}</h2>
<br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="50%">
        <p class="boxtext"><span class="redstar">*</span> {intl-user_name}:</p>
        <input type="text" size="20" name="LoginName" value="{user_name}"/>
		<br /><br />
    </td>
    <td width="50%">
        &nbsp;
    </td>
</tr>
<tr>
    <td>
        <p class="boxtext"><span class="redstar">*</span> {intl-password}:</p>
        <input type="password" size="15" name="Password" value="{password}"/>
    </td>
    <td>
        <p class="boxtext"><span class="redstar">*</span> {intl-repeat_password}:</p>
        <input type="password" size="15" name="PasswordRepeat" value="{password_repeat}"/>
    </td>
</tr>
</table>
<!-- END password_item_tpl -->

<br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" name="AddCV" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/cv/cv/list/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

