<h1>{intl-header}</h1>
<hr height="1" noshade />
<!-- BEGIN error_tpl -->
<h2>{intl-error}</h2>
<ul>
  <!-- BEGIN price_too_low_tpl -->
  <li><b>{intl-price}:</b> {intl-price_too_low}</li>
  <!-- END price_too_low_tpl -->

  <!-- BEGIN valid_until_invalid_tpl -->
  <li><b>{intl-valid_until}:</b> {intl-valid_until_invalid}</li>
  <!-- END valid_until_invalid_tpl -->

  <!-- BEGIN from_name_empty_tpl -->
  <li><b>{intl-from_name}:</b> {intl-from_name_empty}</li>
  <!-- END from_name_empty_tpl -->

  <!-- BEGIN from_email_wrong_tpl -->
  <li><b>{intl-from_email}:</b> {intl-email_wrong}</li>
  <!-- END from_email_wrong_tpl -->

  <!-- BEGIN to_surname_empty_tpl -->
  <li>{intl-to_surname_wrong} {number}{intl-to_address_empty}</li>
  <!-- END to_surname_empty_tpl -->

  <!-- BEGIN to_lastname_empty_tpl -->
  <li>{intl-to_lastname_wrong} {number}{intl-to_address_empty}</li>
  <!-- END to_lastname_empty_tpl -->

  <!-- BEGIN to_email_wrong_tpl -->
  <li>{intl-to_email_wrong} {number}{intl-to_address_wrong}</li>
  <!-- END to_email_wrong_tpl -->

  <!-- BEGIN description_empty_tpl -->
  <li><b>{intl-description}:</b> {intl-description_empty}</li>
  <!-- END description_empty_tpl -->
</ul>
<hr height="1" noshade />
<!-- END error_tpl -->

<!-- BEGIN form_tpl -->
<form action="/trade/vouchercreatebyemail/" method="post">
  <input type="hidden" name="Action" value="Create" />
  <table border="0" cellspacing="6" cellpadding="3">
    <tr> 
      <td colspan="2"><b>{intl-price}:</b><br />
        <input type="text" name="Price" value="{price}" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-valid_until}:</b><br />
        <input type="text" name="ValidUntil" value="{valid_until}" />
      </td>
      <td><br />
        <input type="checkbox" name="Valid" value="unlimited" {checked} />
        &nbsp;<b>{intl-unlimited}</b></td>
    </tr>
    <tr> 
      <td><b>{intl-from_name}:</b><br />
        <input type="text" name="FromName" value="{from_name}" />
      </td>
      <td><b>{intl-from_email}:</b><br />
        <input type="text" name="FromEmail" value="{from_email}" />
      </td>
    </tr>
    <tr> 
      <td colspan="2"><b>{intl-addresses}</b>:<br />
        <textarea cols="40" rows="4" wrap="soft" name="Addresses">{addresses}</textarea>
        <br />
        <span class="small">{intl-explain}</span></td>
    </tr>
    <tr> 
      <td colspan="2"><b>{intl-description}:</b><br />
        <textarea cols="40" rows="8" wrap="soft" name="Description">{description}</textarea>
      </td>
    </tr>
  </table>
  <hr height="1" noshade />
  <input type="Submit" name="Submit" value="OK" />
</form>
<!-- END form_tpl -->

<!-- BEGIN success_tpl -->
<h2>{intl-success}</h2>
<!-- END success_tpl -->
<!-- BEGIN address_success_tpl -->
<b>{intl-voucher_successful_send_to}:</b> {prename} {lastname} (<a href="mailto:{email}">{email}</a>)<br />
<!-- END address_success_tpl -->