<h1>{product_name}</h1>
<hr noshade="noshade" size="1" />

<!-- BEGIN price_to_high_tpl -->
<p class="error">{intl-price_to_high}</p><br />
<!-- END price_to_high_tpl -->
<!-- BEGIN price_to_low_tpl -->
<p class="error">{intl-price_to_low}</p><br />
<!-- END price_to_low_tpl -->


<p>{intl-description}</p>
<br />

<form action="{www_dir}{index}/trade/voucherinformation/{product_id}/{mail_method}/{voucher_info_id}" method="post">


<table>
<tr>
    <td>
    <b>{intl-price_range}:</b><br />
    <input type="text" name="PriceRange" value="{price_range}" />
    </td>
</tr>
</table>


<!-- BEGIN email_tpl -->
<table width="1%">
  <tr>
    <td>
      <b>{intl-to_name}:</b><br />
      <input type="text" name="ToName" value="{to_name}" />
    </td>
    <td>
      <b>{intl-to_email}:</b><br />
      <input type="text" name="Email" value="{to_email}" />
    </td>
  </tr>
  <tr>
    <td>
      <b>{intl-from_name}:</b><br />
      <input type="text" name="FromName" value="{from_name}" />
    </td>
    <td>
      <b>{intl-from_email}:</b><br />
      <input type="text" name="FromEmail" value="{from_email}" />    
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>{intl-text}:</b><br />
      <textarea cols="60" name="Description" rows="8">{description}</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="small">{intl-attention}</td>
  </tr>
</table>
<!-- END email_tpl -->


<!-- BEGIN smail_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
     <td width="50%">
     <b>{intl-name}:</b><br />
     <input type="text" name="ToName" value="{to_name_value}" />
     <input type="hidden" name="ToAddressID" value="{to_address_id}" />
     <br /><br />

     <b>{intl-to_street}:</b><br />
     <input type="text" size="20" name="ToStreet1" value="{to_street1_value}"/>
     <br /><br />
     <b>{intl-to_street}:</b><br />
     <input type="text" size="20" name="ToStreet2" value="{to_street2_value}"/>
     <br /><br />
     <b>{intl-to_zip}:</b><br />
     <input type="text" size="20" name="ToZip" value="{to_zip_value}"/>
     <br /><br />
     <b>{intl-to_place}:</b><br />
     <input type="text" size="20" name="ToPlace" value="{to_place_value}"/>
     <br /><br />
     <!-- BEGIN to_country_tpl -->
     <b>{intl-to_country}:</b><br />
     <select name="ToCountryID" size="5">
     <!-- BEGIN to_country_option_tpl -->
     <option {is_selected} value="{country_id}">{country_name}</option>
     <!-- END to_country_option_tpl -->
     </select>
     <br /><br />
     <!-- END to_country_tpl -->
     </td>
     <td width="50%">
     <b>{intl-from_name}:</b><br />
     <input type="text" name="FromName" value="{from_name_value}" />
     <input type="hidden" name="FromAddressID" value="{from_address_id}" />
     <br /><br />

     <b>{intl-from_street}:</b><br />
     <input type="text" size="20" name="FromStreet1" value="{from_street1_value}"/>
     <br /><br />
     <b>{intl-from_street}:</b><br />
     <input type="text" size="20" name="FromStreet2" value="{from_street2_value}"/>
     <br /><br />
     <b>{intl-from_zip}:</b><br />
     <input type="text" size="20" name="FromZip" value="{from_zip_value}"/>
     <br /><br />
     <b>{intl-from_place}:</b><br />
     <input type="text" size="20" name="FromPlace" value="{from_place_value}"/>
     <br /><br />
     <!-- BEGIN from_country_tpl -->
     <b>{intl-from_country}:</b><br />
     <select name="FromCountryID" size="5">
     <!-- BEGIN from_country_option_tpl -->
     <option {is_selected} value="{country_id}">{country_name}</option>
     <!-- END from_country_option_tpl -->
     </select>
     <br /><br />
     <!-- END from_country_tpl -->
     </td>
</tr>
</table>

<b>{intl-text}:</b><br />
<textarea name="Description" cols="40" rows="8">{description}</textarea>
<br /><br />
<!-- END smail_tpl -->

<hr noshade="noshade" size="1" />

<input type="hidden" name="Mail" value="{mail_method}" />
<input type="hidden" name="ProductID" value="{product_id}" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />


</form>