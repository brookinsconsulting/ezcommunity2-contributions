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

  <!-- BEGIN from_street_empty_tpl -->
  <li><b>{intl-from_street}:</b> {intl-from_street_empty}</li>
  <!-- END from_street_empty_tpl -->

  <!-- BEGIN from_zip_empty_tpl -->
  <li><b>{intl-from_zip}:</b> {intl-from_zip_empty}</li>
  <!-- END from_zip_empty_tpl -->
  
  <!-- BEGIN from_place_empty_tpl -->
  <li><b>{intl-from_place}:</b> {intl-from_place_empty}</li>
  <!-- END from_place_empty_tpl -->  
  
  <!-- BEGIN to_name_empty_tpl -->
  <li><b>{intl-to_name}:</b> {intl-to_name_empty}</li>
  <!-- END to_name_empty_tpl -->

  <!-- BEGIN to_street_empty_tpl -->
  <li><b>{intl-to_street}:</b> {intl-to_street_empty}</li>
  <!-- END to_street_empty_tpl -->

  <!-- BEGIN to_zip_empty_tpl -->
  <li><b>{intl-to_zip}:</b> {intl-to_zip_empty}</li>
  <!-- END to_zip_empty_tpl -->

  <!-- BEGIN to_place_empty_tpl -->
  <li><b>{intl-to_place}:</b> {intl-to_place_empty}</li>
  <!-- END to_place_empty_tpl -->      

  <!-- BEGIN description_empty_tpl -->
  <li><b>{intl-description}:</b> {intl-description_empty}</li>
  <!-- END description_empty_tpl -->
</ul>
<hr height="1" noshade />
<!-- END error_tpl -->

<!-- BEGIN form_tpl -->
<form action="/trade/vouchercreatesnail/" method="post">
  <input type="hidden" name="Action" value="Create" />
  <table border="0" cellspacing="6" cellpadding="3">
    <tr> 
      <td><b>{intl-qty}:</b><br />
        <input type="text" name="Qty" value="{qty}" />
      </td>
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
      <td><b>{intl-to_name}:</b><br />
        <input type="text" name="ToName" value="{to_name}" />
      </td>
    </tr>  
    <tr>
      <td><b>{intl-from_street}:</b><br />
        <input type="text" name="FromStreet" value="{from_street}" />
      </td>
      <td><b>{intl-to_street}:</b><br />
        <input type="text" name="ToStreet" value="{to_street}" />
      </td>      
    </tr>
    <tr>
      <td><b>{intl-from_zip}:</b><br />
        <input type="text" name="FromZip" value="{from_zip}" />
      </td>
      <td><b>{intl-to_zip}:</b><br />
        <input type="text" name="ToZip" value="{to_zip}" />
      </td>      
    </tr>    
    <tr>
      <td><b>{intl-from_place}:</b><br />
        <input type="text" name="FromPlace" value="{from_place}" />
      </td>
      <td><b>{intl-to_place}:</b><br />
        <input type="text" name="ToPlace" value="{to_place}" />
      </td>      
    </tr>    
    <tr> 
      <td colspan="2"><b>{intl-description}:</b><br />
        <textarea cols="40" rows="8" wrap="soft" name="Description">{description}</textarea>
      </td<
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