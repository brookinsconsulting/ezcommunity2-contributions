<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Jobbmarked</span> | {intl-headline_edit}</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<form method="post" action="/cv/education/{action_value}/{education_id}">

<input type="hidden" name="CVID" value="{cv_id}" />

<p class="boxtext">{intl-th_education_institution}:</p>
<input size="40" name="Institution" value="{education_institution}" />

<p class="boxtext">{intl-th_education_direction}:</p>
<input size="40" name="Direction" value="{education_direction}" />

<p class="boxtext">{intl-th_education_speciality}:</p>
<input size="40" name="Speciality" value="{education_speciality}" />

<br /><br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<p class="boxtext">{intl-th_education_start}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="bottom">
    <td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="StartYear" value="{startyear}" />&nbsp;&nbsp;
        <input type="hidden" size="2" name="StartMonth" value="08" />&nbsp;&nbsp;
        <input type="hidden" size="2" name="StartDay" value="01" />&nbsp;&nbsp;
    </td>
<!--
    <td>
        <div class="small">{intl-month}:</div>
        <input type="text" size="2" name="StartMonth" value="{startmonth}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-day}:</div>
        <input type="text" size="2" name="StartDay" value="{startday}"/>&nbsp;&nbsp;
    </td>
-->
</tr>
</table>
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>

<p class="boxtext">{intl-th_education_end}:</p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="EndYear" value="{endyear}" />&nbsp;&nbsp;
        <input type="hidden" size="2" name="EndMonth" value="06" />&nbsp;&nbsp;
        <input type="hidden" size="2" name="EndDay" value="01" />&nbsp;&nbsp;
    </td>
<!--
    <td>
        <div class="small">{intl-month}:</div>
        <input type="text" size="2" name="EndMonth" value="{endmonth}"/>&nbsp;&nbsp;
    </td>
    <td>
        <div class="small">{intl-day}:</div>
        <input type="text" size="2" name="EndDay" value="{endday}"/>&nbsp;&nbsp;
    </td>
-->
</tr>
</table>
</td>
</tr>
</table>

<p class="boxtext">{intl-th_education_comment}:</p>
<textarea rows="5" cols="40" name="Comment" wrap="soft">{education_comment}</textarea>
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
	<input class="okbutton" type="submit" name="back" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>

