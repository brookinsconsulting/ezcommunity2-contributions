<h1>{intl-testpay_payment}</h1>

<p class="boxtext">{intl-testpay_code}:</p>
<input type="text" name="VerifyData[CCNumber]" /> <br />
<input type="text" name="VerifyData[Year]" /> <br />
<input type="hidden" name="VerifyData[PaymentMethod]" value="{payment_method}" /> <br />

<input class="okbutton" type="submit" name="Verify" value="{intl-ok}" />
<input type="hidden" name="PaymentMethod" value="{payment_method}" />
<input type="hidden" name="Action" value="Verify" />