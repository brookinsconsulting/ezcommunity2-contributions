<h1>{intl-headline_edit}</h1>
<form method="post" action="/cv/experience/{action_value}/{experience_id}">
<hr noshade="noshade" size="4" />

<input type="hidden" name="CVID" value="{cv_id}" />

<p class="boxtext">{intl-th_experience_employer}:</p>
<input size="40" name="Employer" value="{experience_employer}" />

<p class="boxtext">{intl-th_experience_position}:</p>
<input size="40" name="Position" value="{experience_position}" />

<p class="boxtext">{intl-th_experience_was_full_time}:</p>
<input size="40" name="wasFullTime" value="{experience_was_full_time}" />

<br /><br />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<p class="boxtext">{intl-th_experience_start}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="bottom">
    <td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="StartYear" value="{startyear}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-month}:</div>
        <input type="text" size="2" name="StartMonth" value="{startmonth}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-day}:</div>
        <input type="text" size="2" name="StartDay" value="{startday}"/>&nbsp;&nbsp;
    </td>
</tr>
</table>
</td>
<td>&nbsp;&nbsp;</td>
<td>

<p class="boxtext">{intl-th_experience_end}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="EndYear" value="{endyear}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-month}:</div>
        <input type="text" size="2" name="EndMonth" value="{endmonth}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-day}:</div>
        <input type="text" size="2" name="EndDay" value="{endday}"/>&nbsp;&nbsp;
    </td>
</tr>
</table>
</td>
</tr>
</table>

<p class="boxtext">{intl-th_experience_tasks}:</p>
<textarea rows="5" cols="40" name="Tasks" wrap="soft">{experience_tasks}</textarea>

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="ok" value="{intl-button_ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/cv/cv/edit/{cv_id}/">
	<input class="okbutton" type="submit" name="{intl-command_back}" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>

