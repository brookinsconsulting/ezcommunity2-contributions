<h1>{intl-header}</h1>
<hr height="1" noshade />
<!-- BEGIN error_tpl -->
<h2>{intl-error}</h2>
<ul>
  <!-- BEGIN qty_too_low_tpl -->
  <li><b>{intl-qty}:</b> {intl-qty_too_low}</li>
  <!-- END qty_too_low_tpl -->

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

  <!-- BEGIN to_name_empty_tpl -->
  <li><b>{intl-to_name}:</b> {intl-to_name_empty}</li>
  <!-- END to_name_empty_tpl -->

  <!-- BEGIN to_email_wrong_tpl -->
  <li><b>{intl-to_email}:</b> {intl-email_wrong}</li>
  <!-- END to_email_wrong_tpl -->

  <!-- BEGIN description_empty_tpl -->
  <li><b>{intl-description}:</b> {intl-description_empty}</li>
  <!-- END description_empty_tpl -->
</ul>
<hr height="1" noshade />
<!-- END error_tpl -->

<!-- BEGIN form_tpl -->
<form action="/trade/vouchercreate/" method="post">
  <input type="hidden" name="Action" value="Create" />
  <table border="0" cellspacing="6" cellpadding="3">
    <tr> 
      <td><b>{intl-qty}:</b><br />
        <input type="text" name="Qty" value="{qty}" />
      </td>
      <td rowspan="5">&nbsp;&nbsp;&nbsp;</td>
      <td><b>{intl-price}:</b><br />
        <input type="text" name="Price" value="{price}" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-valid_until}:</b><br />
        <input type="text" name="ValidUntil" value="{valid_until}" />
      </td>
      <td><br /><input type="checkbox" name="Valid" value="unlimited" {checked} />&nbsp;<b>{intl-unlimited}</b></td>
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
      <td><b>{intl-to_name}:</b><br />
        <input type="text" name="ToName" value="{to_name}" />
      </td>
      <td><b>{intl-to_email}:</b><br />
        <input type="text" name="ToEmail" value="{to_email}" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-description}:</b><br />
        <textarea cols="20" rows="5" wrap="soft" name="Description">{description}</textarea>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <hr height="1" noshade />
  <input type="Submit" name="Submit" value="OK" />
</form>
<!-- END form_tpl -->

<!-- BEGIN success_tpl -->
<h2>{intl-keys}</h2>
<!-- END success_tpl -->
<!-- BEGIN keys_tpl -->
{key_values}<br />
<!-- END keys_tpl -->