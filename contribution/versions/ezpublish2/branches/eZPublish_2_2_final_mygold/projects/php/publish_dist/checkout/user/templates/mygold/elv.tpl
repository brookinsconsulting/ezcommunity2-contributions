<h1>{intl-elv_payment}</h1>
<hr noshade="noshade" size="1" />
<!-- BEGIN error_tpl -->
<h2 class="error">{intl-error}: {error_text}</h2>
<!-- END error_tpl -->

<form action="/trade/payment/{order_id}/{payment_type}/" method="post" >

<!-- BEGIN confirm_tpl -->

<h2>Ermächtigung zum Lastschrifteinzug</h2><br />

Hiermit ermächtige ich die Impetex GmbH - MyGold.com bzw. die beauftragte
InterCard den
Zahlungsbetrag in Höhe von <b class="blue">{charge_total}</b> von meinem durch <b class="blue">Konto-Nummer
{account_nr}</b> und <b class="blue">Bankleitzahl {blz_code}</b> bezeichneten Konto durch Lastschrift
einzuziehen.
<br />
Für den Fall der Nichteinlösung der Lastschrift stimme ich bereits
jetzt zu, dass mein Geldinstitut dem Unternehmen bzw. InterCard auf
Anforderung meinen Namen und meine Anschrift vollständig mitteilt.
Ich willige ein, dass die obengenannten Daten an die InterCard zum
Zweck der Lastschriftbearbeitung übermittelt und dort bis zur Erledigung
der Forderung gespeichert werden.
<input type="hidden" name="AccountNR" value="{account_nr}" />
<input type="hidden" name="BlzCode" value="{blz_code}" />
<input type="hidden" name="Ammount" value="{charge_total}" />
<hr noshade="noshade" size="1" />
<input class="okbutton" type="submit" name="ConfirmCharge" value="&nbsp;{intl-ok}&nbsp;" />

<!-- END confirm_tpl -->

<!-- BEGIN input_tpl -->
{intl-account_number}: <br />
<input type="text" name="AccountNR" /> <br />

{intl-blz_code}: <br />
<input type="text" size="8" maxlength="8" name="BlzCode" /><br />

Karten-Nr: <br />
<input type="text" size="8" maxlength="8" name="CardNr" /><br />
<br />
Zahlung per Bankeinzug (ELV) ist nur von einem deutschen Konto m&ouml;glich!<br />
<br />


<hr noshade="noshade" size="1" />
<input class="okbutton" type="submit" value="&nbsp;{intl-ok}&nbsp;" />

<!-- END input_tpl -->

<input type="hidden" name="Action" value="{action_value}" />
</form>