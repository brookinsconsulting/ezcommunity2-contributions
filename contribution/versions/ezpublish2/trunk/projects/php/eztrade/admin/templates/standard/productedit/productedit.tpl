<h1>Produktredigering</h1>

<form method="post" action="/trade/productedit/{action_value}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="2">
	Navn:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	Nøkkelord:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="Keywords" value="{keywords_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	Bestillingsnummer:
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="text" size="20" name="ProductNumber" value="{product_nr_value}"/>
	</td>
</tr>
<tr>
	<td colspan="2">
	Plassering:
	</td>
</tr>
<tr>
	<td colspan="2">
	<select name="CategoryID">
        {option_values}
	</select>
	</td>
</tr>
<tr>
	<td colspan="2">
	Ingress:
	</td>
</tr>
<tr>
	<td colspan="2">
        <textarea rows="5" cols="20" name="Brief">{brief_value}</textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
	Beskrivelse:
	</td>
</tr>
<tr>
	<td colspan="2">
        <textarea rows="5" cols="20" name="Description">{description_value}</textarea>
	</td>
</tr>
<tr>
	<td>
	Priset:
	</td>
	<td>
	Pris:
	</td>
</tr>
<tr>
	<td width="20%">
	<input type="checkbox" name="ShowPrice" />
	</td>
	<td>
	<input type="text" size="10" name="Price" value="{price_value}" />
	</td>
</tr>
<tr>
	<td>
	Aktiv:
	</td>
	<td>
	Arve opsjoner:
	</td>
</tr>
<tr>
	<td width="20%">
	<input type="checkbox" name="Active" />
	</td>
	<td>
	<input type="checkbox" name="InheritOptions" />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="submit" name="" value="Bilder" />
	<input type="submit" name="" value="Opsjoner" />
	<input type="submit" name="" value="Forhåndsvisning" />
	</td>
</tr>
<tr>
	<td colspan="2">
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="submit" value="OK" />
	</td>
</tr>
</table>
</form>


