<form method="post" action="/contact/companyedit/{action_value}/{person_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<h3 class="error">{error}</h3>

<!-- BEGIN person_item_tpl -->
<h3>{intl-personal_headline}</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td width="50%">
	    <p class="boxtext">{intl-firstname}:</p>
	    <input type="text" size="20" name="FirstName" value="{firstname}"/>
	    </td>
	    <td width="50%">
	    <p class="boxtext">{intl-lastname}:</p>
	    <input type="text" size="20" name="LastName" value="{lastname}"/>
	    </td>
    </tr>
</table>
<p class="boxtext">{intl-birthday_headline}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="50%" valign="bottom">
        <table cellpadding="0" cellspacing="0" border="0">
        <tr valign="bottom">
            <td>
                <p class="boxtext">{intl-year}</p>
                <input type="text" size="4" name="BirthYear" value="{birthyear}"/>
            </td>
            <td>
                <p class="boxtext">{intl-month}</p>
                <input type="text" size="2" name="BirthMonth" value="{birthmonth}"/>
            </td>
            <td>
                <p class="boxtext">{intl-day}</p>
                <input type="text" size="2" name="BirthDay" value="{birthday}"/>
            </td>
        </tr>
        </table>
    </td>
    <td width="50%">
        <p class="boxtext">{intl-personno}:</p>
        <input type="text" size="20" name="PersonNo" value="{personno}"/>
    </td>
</tr>
</table>
<p class="boxtext">{intl-comment_headline}:</p>
<textarea rows="4" columns="80" wrap="soft">{comment}</textarea>
<input type="hidden" name="ContactTypeID" value="{cv_contact_type_id}" />
<input type="hidden" name="UserID" value="{user_id}" />
<!-- END person_item_tpl -->

<!-- BEGIN address_item_tpl -->
<h3>{intl-address_headline}</h3>
<p class="boxtext">{intl-address}:</p>
<input type="text" size="20" name="Street1" value="{street1}"/><br>
<input type="text" size="20" name="Street2" value="{street2}"/>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
        <p class="boxtext">{intl-zip}:</p>
        <input type="text" size="4" name="Zip" value="{zip}"/>
	</td>
	<td width="50%">
        <p class="boxtext">{intl-place}:</p>
        <input type="text" size="20" name="Place" value="{place}"/>
	</td>
</tr>
</table>
<input type="hidden" name="AddressTypeID" value="{cv_address_type_id}" />
<!-- END address_item_tpl -->

<h3>{intl-telephone_headline}</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN home_phone_item_tpl -->
        <p class="boxtext">{intl-home_phone}:</p>
        <input type="text" size="20" name="Phone[]" value="{home_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_work_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_home_phone_id}">
        <!-- END home_phone_item_tpl -->
    </td>
    <td>
        <!-- BEGIN work_phone_item_tpl -->
        <p class="boxtext">{intl-work_phone}:</p>
        <input type="text" size="20" name="Phone[]" value="{work_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_work_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_work_phone_id}">
        <!-- END work_phone_item_tpl -->
    </td>
</tr>
</table>

<h3>{intl-electronic_headline}</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN email_item_tpl -->
        <p class="boxtext">{intl-email}:</p>
        <input type="text" size="20" name="Online[]" value="{email}"/>
        <input type="hidden" name="URLType[]" value="mailto">
        <input type="hidden" name="OnlineTypeID[]" value="{cv_web_online_type_id}">
        <input type="hidden" name="OnlineID[]" value="{cv_email_online_id}">
        <!-- END email_item_tpl -->
    </td>
    <td>
        <!-- BEGIN web_item_tpl -->
        <p class="boxtext">{intl-web}:</p>
        <input type="text" size="20" name="Online[]" value="{web}"/>
        <input type="hidden" name="URLType[]" value="http">
        <input type="hidden" name="OnlineTypeID[]" value="{cv_web_online_type_id}">
        <input type="hidden" name="OnlineID[]" value="{cv_web_online_id}">
        <!-- END web_item_tpl -->
    </td>
</tr>
</table>

<hr noshade size="4"/>

<input class="okbutton" name="addcv" type="submit" value="{intl-add_cv}" />
<input class="okbutton" name="addimage" type="submit" value="{intl-add_image}" />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}" />
</form>

<form method="post" action="/contact/companylist/">
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

