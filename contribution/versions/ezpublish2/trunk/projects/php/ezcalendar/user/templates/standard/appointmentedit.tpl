
<form method="post" action="/calendar/appointmentedit/">

<h1>{intl-appointment_edit}</h1>

<hr noshade="noshade" size="4" />

<br />

<p class="boxtext">{intl-appointment_title}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>
	
<p class="boxtext">{intl-appointment_description}:</p>
<textarea name="Description" cols="40" rows="5" wrap="soft">{description_value}</textarea>

<p class="boxtext">{intl-private_appointment}:</p>
<input type="checkbox" name="IsPrivate" value="{private_checked}" />


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-appointment_month}:</p>

	<select name="Month">
	<!-- BEGIN month_tpl -->
		<option value="{month_id}" {selected}>{month_name}</option>
	<!-- END month_tpl -->
	</select>

	</td>
	<td valign="top">
	<p class="boxtext">{intl-appointment_day}:</p>

	<select name="Day">
	<!-- BEGIN day_tpl -->
		<option value="{day_id}" {selected}>{day_name}</option>
	<!-- END day_tpl -->
	</select>

	</td>
	<td valign="top">
	<p class="boxtext">{intl-appointment_year}:</p>
	<input type="text" name="Year" value="{year_value}" />
	</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-appointment_hour}:</p>

	<input type="text" size="2" name="Hour" value="{hour_value}" />

	</td>
	<td valign="top">
	<p class="boxtext">{intl-appointment_minute}:</p>

	<input type="text"  size="2" name="Minute" value="{minute_value}" />
	</td>
	<td>
	<p class="boxtext">{intl-appointment_duration}:</p>
	<input type="text" size="4" name="Duration" value="{duration_value}" />

	</td>
</tr>
</table>





	
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="FileID" value="{file_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />

	</td>
	<td>&nbsp;</td>
	<td>

	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

	</td>

</tr>
</table>

</form>


