<h1>{intl-mastercard_payment}</h1>
<hr noshade="noshade" size="1" />
<!-- BEGIN error_tpl -->
<h2 class="error">{intl-error}: {error_text}</h2>
<!-- END error_tpl -->
<form action="/trade/payment/{payment_type}/" method="post" >

<b>{intl-credit_card_number}:</b><br />
<input type="text" name="CCNumber" size="10" value="{card_number}" />
<br /><br />

<b>{intl-cvc2_number}:<sup>1</sup></b><br />
<input type="text" name="CVC2Value" value="{cvc2_number}" size="3" maxlength="3" />
<br /><br />

<b>{intl-valid_thru}:</b> <br />
{intl-month}:
<select name="ExpireMonth" style="font-size: 12px">
  <option value="01">01</option>
  <option value="02">02</option>
  <option value="03">03</option>
  <option value="04">04</option>
  <option value="05">05</option>
  <option value="06">06</option>
  <option value="07">07</option>
  <option value="08">08</option>
  <option value="09">09</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
</select>

{intl-year}:
<select name="ExpireYear" style="font-size: 12px">
  <option value="01">2001</option>
  <option value="02">2002</option>
  <option value="03">2003</option>
  <option value="04">2004</option>
  <option value="05">2005</option>
  <option value="06">2006</option>
  <option value="07">2007</option>
</select>
<br /><br />

<hr noshade="noshade" size="1" />
<span class="small"><sup>1</sup>{intl-cvc2_explain} <a href="/article/articlestatic/26/#Geheimnummer">Weitere Informationen</a></span>
<br />
<hr noshade="noshade" size="1" />

<input class="okbutton" type="submit" value="&nbsp;{intl-ok}&nbsp;" />
<input type="hidden" name="Action" value="Verify" />
</form>