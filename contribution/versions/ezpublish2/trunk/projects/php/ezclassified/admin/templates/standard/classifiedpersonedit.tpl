<!-- BEGIN person_form_tpl -->
<form method="post" action="/classified/person/{action_value}/{classified_id}/{company_id}/{person_id}/" enctype="multipart/form-data">
<!-- END person_form_tpl -->
<!-- BEGIN no_person_form_tpl -->
<form method="post" action="/classified/person/{action_value}/{classified_id}/{company_id}/" enctype="multipart/form-data">
<!-- END no_person_form_tpl -->

<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_firstname_item_tpl -->
<li>{intl-error_firstname}
<!-- END error_firstname_item_tpl -->

<!-- BEGIN error_lastname_item_tpl -->
<li>{intl-error_lastname}
<!-- END error_lastname_item_tpl -->

<!-- BEGIN error_email_not_valid_item_tpl -->
<li>{intl-error_email_not_valid_item}
<!-- END error_email_not_valid_item_tpl -->

<!-- BEGIN error_email_item_tpl -->
<li>{intl-error_email}
<!-- END error_email_item_tpl -->

</ul>
<!-- END errors_tpl -->

<!-- BEGIN person_item_tpl -->
<h2>{intl-personal_headline}</h2>

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

<p class="boxtext">{intl-title}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td width="50%">
	    <input type="text" size="20" name="Title" value="{title}"/>
	    </td>
    </tr>
</table>

<h2>{intl-telephone_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN work_phone_item_tpl -->
        <p class="boxtext">{intl-work_phone}:</p>
        <input type="text" size="20" name="Phone[]" value="{work_phone}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_work_phone_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_work_phone_id}">
        <!-- END work_phone_item_tpl -->
    </td>
    <td>
        <!-- BEGIN work_fax_item_tpl -->
        <p class="boxtext">{intl-phone_fax}:</p>
        <input type="text" size="20" name="PhoneFax[]" value="{work_fax}"/>
        <input type="hidden" name="PhoneTypeID[]" value="{cv_work_fax_type_id}">
        <input type="hidden" name="PhoneID[]" value="{cv_work_fax_id}">
        <!-- END work_fax_item_tpl -->
    </td>
</tr>
</table>

<h2>{intl-online_headline}</h2><br />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <!-- BEGIN email_item_tpl -->
        <p class="boxtext">{intl-email}:</p>
        <input type="text" size="20" name="Online[]" value="{email}"/>
        <input type="hidden" name="URLType[]" value="mailto">
        <input type="hidden" name="OnlineTypeID[]" value="{cv_email_online_type_id}">
        <input type="hidden" name="OnlineID[]" value="{cv_email_online_id}">
        <!-- END email_item_tpl -->
    </td>
</tr>
</table>

<br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/classified/edit/{classified_id}/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

