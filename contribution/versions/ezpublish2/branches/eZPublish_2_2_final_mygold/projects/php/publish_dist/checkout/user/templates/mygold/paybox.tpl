<h1>{intl-paybox_payment}</h1>
<hr noshade="noshade" size="1" />
<!-- BEGIN error_tpl -->
<!-- BEGIN error_transfer_tpl -->
<h2 class="error">{intl-error_text}: {error_text}</h2>
<h2 class="error">{intl-error_code}: {error_code}</h2>
<!-- END error_transfer_tpl -->
<!-- BEGIN error_country_code_tpl -->
<h2 class="error">{intl-error_country_code}</h2>
<!-- END error_country_code_tpl -->
<!-- BEGIN error_mobile_number_tpl -->
<h2 class="error">{intl-error_mobile_number}</h2>
<!-- END error_mobile_number_tpl -->
<!-- END error_tpl -->

<form action="/trade/payment/{payment_type}/" method="post" >



<table cellspacing="0" cellpadding="4" border="0" width="1%">
  <tr>
    <td colspan="2"><b>{intl-amount}:</b> {amount}</td>
  </tr>
  <tr>
	<td>
          <b>{intl-country_code}:</b>
          <select name="CountryCode">
	    <!-- BEGIN country_code_tpl -->
	    <option value="{country_code_number}" {country_code_selected}>
	      {country_code_text}
	    </option>
	    <!-- END country_code_tpl -->
	  </select>
	</td>
	<td>
          <b>{intl-mobile_number}:</b>
          <input type="text" size="10" maxlength="10" name="MobileNumber" value="{mobile_number}" />
  	</td>
  </tr>
</table>
&sup1; <span class="small">{intl-no_leading_0}</span>

<hr noshade="noshade" size="1" />
<input class="okbutton" type="submit" value="&nbsp;{intl-ok}&nbsp;" />

<p>{intl-paybox_information}</p>

<input type="hidden" name="Action" value="Verify" />

</form>