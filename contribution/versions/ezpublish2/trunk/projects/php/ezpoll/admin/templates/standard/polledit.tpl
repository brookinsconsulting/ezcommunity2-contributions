<form method="post" action="/poll/polledit/{action_value}/{poll_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>
<p class="error">{error_msg}</p>
<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-desc}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>

<br /><br />

<div class="checkhead">{intl-settings}:</div>
<div class="check"><input type="checkbox" name="IsEnabled" {is_enabled}>&nbsp;{intl-active}</div>
<div class="check"><input type="checkbox" name="IsClosed" {is_closed}>&nbsp;{intl-finish}</div>
<div class="check"><input type="checkbox" name="ShowResult" {show_result}>&nbsp;{intl-show_result}</div>

<div class="check"><input type="checkbox" name="Anonymous" {anonymous}> {intl-anonymous}</div>
<!-- <div class="check"><input type="checkbox" name="UserEditRule" {user_edit_rule}> Bruker kan redigere egen stemme</div> -->

<!-- <p class="checkhead">{intl-choices}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<div class="check"><input type="radio" value="And" name="And">Og</div>
	</td>
	<td>
	<input value="Or" type="radio" name="And"> Eller</div>
	</td>
</tr> 
</table> -->

<!-- <p class="checkhead">{intl-show}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<div class="check"><input type="checkbox" name="Number" {number}> Antall stemmer</div>
	</td>
	<td>
	<div class="check"><input type="checkbox" name="Percent" {percent}> Prosent</div>
	</td>
</tr>
</table> -->

<br />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">{intl-answers}:</th>
<!--
	<th>{intl-adjust}</th>
-->
	<td align="right"><span class="boxtext">{intl-total_votes}:</span></td>
	<th colspan="3">&nbsp;</th>
</tr>
<tr>
	<td>
	{nopolls}
	</td>
	<!-- BEGIN poll_choice_tpl -->
	<tr>
		<td class="{td_class}">
		<input type="hidden" name="PollChoiceID[]" value="{choice_id}" />
		<input type="text" name="PollChoiceName[]" value="{poll_choice_name}" />

		</td>
<!--
		<td class="{td_class}">&nbsp;</td>
-->
		<td class="{td_class}" align="right">
		{poll_number}
		</td>
		<td class="{td_class}" width="1%">
		<input type="checkbox" name="PollArrayID[]" value="{index_nr}">
		</td>	
	</tr>	
	<!-- END poll_choice_tpl -->
</tr>
</table>

<hr noshade size="4"/>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
     <td>
     <input class="stdbutton" type="submit" name="Choice" value="{intl-newanswer}">
     </td>
     <td>&nbsp;</td>
     <td>
     <input type="submit" class="stdbutton" Name="DeleteChoice" value="{intl-removeanswer}">
     </td>
</tr>
</table>

<hr noshade size="4"/>

<input type="hidden" name="PollID" value="{poll_id}" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</td>
</tr>
</table>
</form>

