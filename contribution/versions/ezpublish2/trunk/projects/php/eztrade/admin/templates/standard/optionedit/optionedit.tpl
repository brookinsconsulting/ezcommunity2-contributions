<h1>Opsjonsredigering - {product_name}</h1>

<form method="post" action="/trade/productedit/optionedit/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	Tittel:
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td>
	Beskrivelse:
	</td>
</tr>
<tr>
	<td>
	<textarea rows="5" cols="20" name="Description">{description_value}</textarea>
	</td>
</tr>
<tr>
	<td>
	Valgmuligheter:
	</td>
</tr>
<tr>
	<td>
	<textarea rows="5" cols="20" name="OptionValues">{option_values}</textarea>
	</td>
</tr>
<tr>
	<td>
    <input type="hidden" name="ProductID" value="{product_id}" />
    <input type="hidden" name="OptionID" value="{option_id}" />
    <input type="hidden" name="Action" value="{action_value}" />
    {hidden_fields}
    <input type="submit" value="OK" />
	</td>
</tr>
</table>
</form>


