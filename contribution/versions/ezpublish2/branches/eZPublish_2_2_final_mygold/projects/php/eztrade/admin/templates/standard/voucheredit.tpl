<form method="post" action="{www_dir}{index}/trade/voucheredit/">

<h1>{intl-voucher_edit}</h1>

<hr noshade="noshade" size="4" />

<table cellspacing="4" cellpadding="4" border="0" width="60%">
  <tr>
    <td>
      <p class="boxtext">{intl-price}:</p>
      <input type="text" size="8" name="Price" value="{voucher_price}"/>
    </td>
    <td>
      <p class="boxtext">{intl-voucher_original_price}:</p>
      {voucher_original_price}
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p class="boxtext">{intl-available}:</p>
      <input type="checkbox"  name="Available"  {is_checked} />
    </td>
  </tr>
  <tr>
    <td>
      <p class="boxtext">{intl-created}:</p>
      {voucher_created}
    </td>
    <td>
      <p class="boxtext">{intl-valid_until}:</p>
      {valid_until}
    </td>
  </tr>

  <!-- BEGIN email_information_tpl -->
  <tr>
    <td>
      <p class="boxtext">{intl-receiver}:</p>
      {sent_name} (<a href="mailto:{sent_email}">{sent_email}</a>)
    </td>
    <td>
      <p class="boxtext">{intl-sender}:</p>
      {from_name} (<a href="mailto:{from_email}">{from_email}</a>)
    </td>
  </tr>
  <!-- END email_information_tpl -->

  <!-- BEGIN smail_information_tpl -->
  <tr>
    <td>
      <p class="boxtext">{intl-receiver}:</p>
      {to_name_value}<br />
      {to_street1_value}<br />
      {to_zip_value} {to_place_value}<br />
      {to_country_name}
    </td>
    <td>
      <p class="boxtext">{intl-sender}:</p>    
      {from_name_value}<br />
      {from_street1_value}<br />
      {from_zip_value} {from_place_value}<br />
      {from_country_name}
    </td>
  </tr>
  <!-- END smail_information_tpl -->
  
  <tr>
    <td colspan="2">
      <p class="boxtext">{intl-receiver_description}:</p>
      {sent_description}
    </td>
  </tr>
</table>
<br />

<!-- BEGIN used_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-used}:
	</th>
	<th>
	{intl-used_by}:
	</th>
	<th>
	{intl-used_price}:
	</th>
	<th>
	{intl-order}:
	</th>
</tr>
<!-- BEGIN used_item_tpl -->
<tr>
       <td class="{td_class}">
       {used_used}       
       </td>
       <td class="{td_class}">
       <a href="{www_dir}{index}/trade/customerview/{user_id}/">{user_name}</a>
       </td>
       <td class="{td_class}">
       {used_price}       
       </td>
       <td class="{td_class}">
       <a href="{www_dir}{index}/trade/orderedit/{voucher_order_id}/">{voucher_order_id}</a>       
       </td>
</tr>
<!-- END used_item_tpl -->
</table>
<!-- END used_list_tpl -->

<br />
<hr noshade="noshade" size="4" />


<input type="hidden" name="VoucherID" value="{voucher_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
