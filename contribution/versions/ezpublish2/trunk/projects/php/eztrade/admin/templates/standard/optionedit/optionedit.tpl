<form method="post" action="/trade/productedit/optionedit/">

<h1>Opsjonsredigering: {product_name}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">Tittel:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>
	
<p class="boxtext">Beskrivelse:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />
	
<p class="boxtext">Valgmuligheter:</p>
<textarea rows="5" cols="40" name="OptionValues">{option_values}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="hidden" name="OptionID" value="{option_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	{hidden_fields}
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/trade/productedit/optionlist/{product_id}/">
	<input class="okbutton" type="submit" value="{intl-abort}" />	
	</form>		    
	</td>
</tr>
</table>



