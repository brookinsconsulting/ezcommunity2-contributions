<h1>{head_line}</h1>


<form method="post" action="/poll/polledit/{action_value}/{poll_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{intl-name}
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td>
	{intl-desc}
	</td>
</tr>
<tr>
	<td>
    <textarea rows="5" cols="20" name="Description">{description_value}</textarea>
	</td>
</tr>

<tr>
	<td>
	<input type="checkbox" name="IsEnabled" {is_enabled}> Aktiv<br>
	<input type="checkbox" name="IsClosed" {is_closed}> Avsluttet<br>
	<input type="checkbox" name="ShowResult" {show_result}> Vis resultat<br>
	<br>
	<input type="checkbox" name="Anonymous" {anonymous}> Anonym avstemming<br>
	<input type="checkbox" name="UserEditRule" {user_edit_rule}> Bruker kan redigere egen stemme<br>
	<br>
	<input type="radio" value="And" name="And">Og<input value="Or" type="radio" name="And"> Eller<br>
	<br>
	Resultatvisning<br>
	<input type="checkbox" name="Number" {number}> Antall stemmer<br>
	<input type="checkbox" name="Percent" {percent}> Prosent<br>
	<br>
	</td>
</tr>
<tr>
	<td>
	Svaralternativer
	</td>
	<td>
	Antall stemmer
	</td>
	<td>
	Redigere
	</td>
	<td>
	Slette
	</td>
</tr>
{poll_choice_list}

<tr>
<td>
<br>


</td>
</tr>
<tr>
	<td>
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input type="submit" value="OK" />
	<input type="submit" name="Choice" value="Nytt svaralternativ">
	<form method="post" action="/poll/polllist/">
	<input type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
<td>
</table>
</form>

