<h1>Opsjonsredigering</h1>

<form method="post" action="/trade/productedit/optionedit/{product_id}/">
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
    <input type="hidden" name="Action" value="{action_value}" />
    <input type="submit" value="OK" />
	</td>
</tr>
</table>
</form>


