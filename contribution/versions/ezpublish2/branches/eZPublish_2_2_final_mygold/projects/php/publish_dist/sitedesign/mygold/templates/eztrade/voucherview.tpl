<form method="post" action="{www_dir}{index}/trade/voucherview/">

<table  width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td><h1>{intl-voucher_edit}</h1></td>
    <td align="right">
      <input type="text" size="20" name="Key" value="{voucher_key}"/>&nbsp;
      <input class="okbutton" type="submit" name="ViewVoucher" value="{intl-view_voucher}" />
    </td>
  </tr>
</table>
<hr noshade="noshade" size="1" />


<!-- BEGIN error_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">  
  <tr>
    <td colspan="3"><p>{intl-error_message}</p></td>
  </tr>
</table>
<!-- END error_tpl -->

<!-- BEGIN view_voucher_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td colspan="2">
      <b>{intl-price}:</b><br />
      {voucher_price}
    </td>
    <td>
      <b>{intl-original_price}:</b><br />
      {voucher_original_price}
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>{intl-created}:</b><br />
      {voucher_created}
    </td>
    <td>
      <b>{intl-valid_until}:</b><br />
      {valid_until}
    </td>
  </tr>
  <!-- BEGIN email_information_tpl -->
  <tr>
    <td colspan="2">
      <b>{intl-receiver}:</b><br />
      {sent_name} ({sent_email})
    </td>
    <td>
      <b>{intl-sender}:</b><br />
      {from_name} ({from_email})    
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <b>{intl-description}:</b><br />
      {sent_description}<br /><br />
    </td>
  </tr>
</table>
<hr noshade="noshade" size="1" />  
<!-- END email_information_tpl -->

<!-- BEGIN smail_information_tpl -->
<table cellspacing="3" cellpadding="8" border="0">
  <tr>
     <td>
       <b>{intl-to_name}:</b><br />
       {to_name_value}
       <br />
       {to_street1_value}
       <br />
       {to_zip_value} {to_place_value}
       <br />
       {to_country_name}
     </td>
     <td>
       <b>{intl-from_name}:</b><br />
       {from_name_value}
       <br />
       {from_street1_value}
       <br />
       {from_zip_value} {from_place_value}
       <br />
       {from_country_name}
     </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>{intl-description}:</b><br />
      {sent_description}<br /><br />
    </td>
  </tr>  
</table>
<hr noshade="noshade" size="1" />
<!-- END smail_information_tpl -->


<!-- BEGIN used_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">  
  <tr align="left">
    <th>{intl-used}:</th>
    <th>{intl-used_price}:</th>
    <th>{intl-view_order}:</th>
  </tr>
  <!-- BEGIN used_item_tpl -->
  <tr>
    <td class="{td_class}">{used_used}</td>
    <td class="{td_class}">{used_price}</td>
    <td class="{td_class}">
      <a href="/trade/orderview/{voucher_order_id}/">{intl-invoice_nr} {voucher_order_id}</a>       
    </td>
  </tr>
  </table>
  <hr noshade="noshade" size="1" />
  <!-- END used_item_tpl -->
  <!-- END used_list_tpl -->
  <!-- END view_voucher_tpl -->


</form>
