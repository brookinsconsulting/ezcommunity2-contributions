<h1>Sicherheitsmitteilung</h1>
<hr noshade="noshade" size="1" />
<p>Sie verwenden den Microsoft Internet Explorer.</p>

<p>Wir haben festgestellt, dass es bei manchen Exportversionen des Microsoft Internet Explorers 
zu Probleme bei der gesicherten &Uuml;bertragung kommen kann.<br /> 
Durch die Exportbeschränkung auf 40-Bit, die noch bis vor Kurzem galt, zeigen manche Versionen des
Internetexplorers keine Seiten an, da Sie mit unserem 128-Bit-Schl&uuml;ssel nicht zurecht kommen.</p>

<p>Da dieser hohe Verschl&uuml;sselungsstandard Ihrer Sicherheit dient, empfehlen wir Ihnen Ihren Browser
auf den neuesten Stand zu bringen, auch, wenn Sie keine Problem beim Anzeigen unserer verschl&uuml;sselten Seiten haben.</p>

<p>Sie k&ouml;nnen die Verschl&uuml;sselungsstärke Ihres Internet Explorers herausfinden, indem Sie in der Men&uuml;leiste
auf das "?" klicken und den Untermen&uuml;punkt "Info" auswählen. Indem sich &ouml;ffnenden Fenster k&ouml;nnen Sie unter 
der Version die Verschl&uuml;sselungsstärke ablesen. Sollte diese kleiner als 128-Bit sein, empfehlen wir Ihnen ein Update.</p>

<p>Um Ihren Browser upzudaten klicken Sie entweder neben der Anzeige der Verschl&uuml;sselungsstärke aus der oben
beschriebenen Prozedur auf den Link Updateinformation oder gehen Sie von hier aus direkt zu 
<href="http://www.microsoft.com/windows/ie_intl/de/download/128bit/intro.htm">Microsoft</a></p>

<p>Sie werden entweder automatisch weitergeleitet oder k&ouml;nnen 
<?
$localURL = $GLOBALS["HTTP_HOST"];
print (" <a href=\"https://$localURL/trade/checkout\">hier</a>. " );
?>
klicken um zur Kasse zu gelangen.</p>

<p>Vielen Dank f&uuml;r Ihr Verst&auml;ndnis.</p>


<?
$localURL = $GLOBALS["HTTP_HOST"];
print (" <a href=\"https://$localURL/trade/checkout/1\">checkout with ssl</a>. " );
?>
<br />

<?
$localURL = $GLOBALS["HTTP_HOST"];
print (" <a href=\"http://$localURL/trade/checkout/0\">checkout without ssl</a>. " );
?>
<br />

<?
$localURL = $GLOBALS["HTTP_HOST"];
print (" <a href=\"https://$localURL/article/articlestatic/40\" target=\"newwindow\" >test ssl</a>. " );
?>
