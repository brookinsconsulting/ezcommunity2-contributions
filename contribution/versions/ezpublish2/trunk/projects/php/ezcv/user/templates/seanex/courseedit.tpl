<h1>{intl-headline_edit}</h1>
<form method="post" action="/cv/course/{action_value}/{course_id}">
<hr noshade="noshade" size="4" />

<input type="hidden" name="CVID" value="{cv_id}" />

<p class="boxtext">{intl-th_course_name}:</p>
<input size="40" name="Name" value="{course_name}" />

<p class="boxtext">{intl-th_course_place}:</p>
<input size="40" name="Place" value="{course_place}" />

<br /><br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<p class="boxtext">{intl-th_course_start}:</p>
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

<p class="boxtext">{intl-th_course_stop}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="StopYear" value="{stopyear}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-month}:</div>
        <input type="hidden" size="2" name="StopMonth" value="{stopmonth}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-day}:</div>
        <input type="hidden" size="2" name="StopDay" value="{stopday}"/>&nbsp;&nbsp;
    </td>
</tr>
</table>
</td>
</tr>
</table>

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
	<input class="okbutton" type="submit" name="back" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>

