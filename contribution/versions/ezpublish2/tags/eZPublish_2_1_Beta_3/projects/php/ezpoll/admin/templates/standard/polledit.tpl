<form method="post" action="/poll/polledit/{action_value}/{poll_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>
<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<textarea wrap="soft" rows="3" cols="40" name="Name">{name_value}</textarea>
<br /><br />

<p class="boxtext">{intl-desc}:</p>
<textarea wrap="soft" rows="5" cols="40" name="Description">{description_value}</textarea>

<br /><br />

<div class="checkhead">{intl-settings}:</div>
<div class="check"><input type="checkbox" name="IsEnabled" {is_enabled}>&nbsp;{intl-active}</div>
<div class="check"><input type="checkbox" name="IsClosed" {is_closed}>&nbsp;{intl-finish}</div>
<div class="check"><input type="checkbox" name="ShowResult" {show_result}>&nbsp;{intl-show_result}</div>

<div class="check"><input type="checkbox" name="Anonymous" {anonymous}> {intl-anonymous}</div>


<br />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">{intl-answers}:</th>
	<th align="right"><span class="boxtext">{intl-total_votes}:</span></th>
	<th colspan="3">&nbsp;</th>
</tr>
<!-- BEGIN poll_choice_tpl -->
<tr>
	<td class="{td_class}">
	<input type="hidden" name="PollChoiceID[]" value="{choice_id}" />
	<input type="text" name="PollChoiceName[]" value="{poll_choice_name}" />
	</td>
	<td class="{td_class}" align="right">
	{poll_number}
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="PollArrayID[]" value="{index_nr}">
	</td>	
</tr>	
<!-- END poll_choice_tpl -->
</table>

<hr noshade size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
     <td>
     <input class="stdbutton" type="submit" name="Choice" value="{intl-newanswer}" />
     </td>
     <td>&nbsp;</td>
     <td>
     <input type="submit" class="stdbutton" Name="DeleteChoice" value="{intl-removeanswer}" />
     </td>
</tr>
</table>

<hr noshade size="4" />

<input type="hidden" name="PollID" value="{poll_id}" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" name="Ok" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</td>
</tr>
</table>

</form>

