<form method="post" action="/poll/polledit/{action_value}/{poll_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}</p>
<input type="text" size="20" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-desc}</p>
<textarea rows="5" cols="20" name="Description">{description_value}</textarea>

<br></br>

<div class="check"><input type="checkbox" name="IsEnabled" {is_enabled}>&nbsp;Aktiv</div>
<div class="check"><input type="checkbox" name="IsClosed" {is_closed}>&nbsp;Avsluttet</div>
<div class="check"><input type="checkbox" name="ShowResult" {show_result}>&nbsp;Vis resultat</div>

<div class="check"><input type="checkbox" name="Anonymous" {anonymous}> Anonym avstemming</div>
<div class="check"><input type="checkbox" name="UserEditRule" {user_edit_rule}> Bruker kan redigere egen stemme</div>

<p class="checkhead">Valgmuligheter</p>
<div class="check"><input type="radio" value="And" name="And">Og&nbsp;&nbsp;<input value="Or" type="radio" name="And"> Eller</div>

<p class="checkhead">Resultatvisning</p>
<div class="check"><input type="checkbox" name="Number" {number}> Antall stemmer</div
<div class="check"><input type="checkbox" name="Percent" {percent}> Prosent</div>

<br></br>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Svaralternativer</th>
	<th>Antall stemmer</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<tr>
	<td>
	{nopolls}
	</td>
	<!-- BEGIN poll_choice_tpl -->
	<tr>
		<td>
			<a href="/poll/polledit/{choice_id}/">{poll_choice_name}</a>
		</td>
		<td>
			{poll_number}
		</td>
		<td>
			<a href="/poll/choiceedit/edit/{poll_id}/{choice_id}/">Rediger></a>
		</td>
		<td>
			<a href="/poll/choiceedit/delete/{poll_id}/{choice_id}/">Slett></a>
		</td>
	</tr>	
	<!-- END poll_choice_tpl -->
</tr>
</table>

<br></br>

<hr noshade size="4"/>

<input class="stdbutton" type="submit" name="Choice" value="Nytt svaralternativ">

<hr noshade size="4"/>

<input type="hidden" name="PollID" value="{poll_id}" />
<input class="okbutton" type="submit" value="OK" />

<form method="post" action="/poll/pollist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>

