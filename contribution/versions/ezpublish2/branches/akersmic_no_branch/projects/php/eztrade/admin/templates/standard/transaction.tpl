<h1>{intl-payment_confirmation}</h1>

<!-- BEGIN online_payment_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
     <th>{intl-amount}:</th>
     <th>{intl-moment_time}:</th>
  </tr>
  <!-- BEGIN online_payment_item_tpl -->
  <tr>
    <td class="{td_class}">{online_payment}</td>
    <td class="{td_class}">{day}.{month}.{year} - {hour}:{minute}:{second}</td>
  <tr>
  <!-- END online_payment_item_tpl -->
</table>
<!-- END online_payment_list_tpl -->

<!-- BEGIN new_transaction_tpl -->
<p class="boxtext">{intl-new_transaction}: {amount}</p>
<!-- END new_transaction_tpl -->
<!-- BEGIN refund_amount_tpl -->
<p class="boxtext">{intl-total_amount_paid}: {paid_amount}</p>
<p class="boxtext">{intl-refund_amount}: {amount}</p>
<!-- END refund_amount_tpl -->

<br />
<hr noshade="noshade" size="4" />


<form method="post" action="{www_dir}{index}/trade/transaction/{order_id}/">
<input type="hidden" name="PaymentAmount" value="payment_amount">
<table cellspacing="0" cellpadding="0" border="0">
<tr valign="center">
<td>
    <input class="okbutton" name="TransactionOK" type="submit" value="{intl-confirm}" />
</td>
<td>
&nbsp;
</td>
<td>
    <input class="okbutton" name="Cancel" type="submit" value="{intl-cancel}" />
</td>
</tr>
</table>
</form>