<h1>Ermitteln Sie Ihre Ringgr&ouml;&szlig;e</h1>
<hr noshade="noshade" size="1" />
<table width="100%" cellpadding="4" cellspacing="0" border="0">
	<tr> 
		<td colspan="2"> Die einfachste Methode, Ihre Ringgr&ouml;&szlig;e zu 
			ermitteln, ist einen passenden Ring zu vermessen.<br />
			Messen Sie hierzu den Innendurchmesser Ihres Ringes (siehe Zeichnung) 
			und geben den ermittelten Wert in das Formular ein.<br />
			<br />
		</td>
	</tr>
	<tr> 
		<td width="25%"> 
			<table cellpadding="0" cellspacing="0" border="0">
				<tr> 
					<td align="center"><img src="/sitedesign/mygold/images/durchmesser.gif" height="77" width="130" alt="Skizze zur Ermittlung des Ringinnenmaßes" > 
					</td>
				</tr>
				<tr> 
					<td align="center" style="font-size: 10px"> Ermittlung des 
						Durchmessers</td>
				</tr>
			</table>
		</td>
		<td> 
			<form method="post" action="<? echo $HTTP_SERVER_VARS["REQUEST_URI"]; ?>">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr> 
						<td colspan="2"> 
							<hr nochade="noshade" size="1" />
						</td>
					</tr>
					<tr> 
						<td> Durchmesser:&nbsp; </td>
						<td> 
							<input style="font-size: 10px;" type="text" name="Durchmesser" value="<? echo $GLOBALS["Durchmesser"];?>" size="2" maxlength="2" />
						</td>
					</tr>
					<tr> 
						<td> Umfang: </td>
						<td> 
							<?
							if ( $GLOBALS["Send"] )
							{
				    			echo '<input style="font-size: 10px;" type="text" name="Out" value="' . round( $GLOBALS["Durchmesser"] * 3.14 ) . '" size="2" maxlength="2" />';
							}
						?>
						</td>
					</tr>
					<tr> 
						<td colspan="2"> 
							<hr nochade="noshade" size="1" />
						</td>
					</tr>
					<tr> 
						<td colspan="2"> 
							<input class="okbutton" type="submit" name="Send" value="Umrechnen" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
	    <td colspan="2">
		<br />
		Notieren Sie sich den so ermittelten Wert aus dem Feld Umfang. Er entspricht den bei uns angegebenen Maßen.<br />
		Sollten Sie leichte Abweichungen vom Ringmaß haben, so können Sie den Ring kostenlos in einer unserer
		<a href="/article/articlestatic/11/">Filialen</a> anpassen lassen. Falls sich keine Filiale in Ihrer Nähe befindet,
		können Sie den Ring auch gerne einschicken. Die Adresse hierzu finden Sie unter <a href="/feedback/">Kontakt</a>. <br />
		Sollte ein Ring nicht in Ihrer Größe verfügbar sein, setzten Sie sich mit uns in Verbindung. Unser geschultes Personal
		wird  prüfen, ob wir Ihren Wunsch durch Nachbestellung oder Änderung erfüllen können.
	    </td>
	</tr>
</table>
