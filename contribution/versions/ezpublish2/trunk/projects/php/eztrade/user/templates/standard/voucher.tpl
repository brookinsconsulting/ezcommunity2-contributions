<h1>{intl-voucher_payment}</h1>

<hr noshade="noshade" size="4" />


<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<p class="boxtext">{intl-key_number}:</p>
<input type="text" name="KeyNumber" /> <br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}" />

<input type="hidden" name="Action" value="Verify" />

</form> 

