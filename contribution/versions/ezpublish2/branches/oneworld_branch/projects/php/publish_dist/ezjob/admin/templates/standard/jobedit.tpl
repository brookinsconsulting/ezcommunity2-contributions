<h1>{intl-headline}</h1>

<hr noshade size="4" />

<br />
<form method="post" action="{www_dir}{index}/job/{action_value}/{offset}">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="20%"><h2>{intl-job_status}:</h2></td><td width="80%">{job_status}</td>
</tr>
</table>

<br />

<table width="100%" cellpadding="0" cellspacing="4" border="0">
<tr>
  <th><p class="boxtext">*{intl-job_title}:</p></th>
  <th><p class="boxtext">*{intl-career_sector}:</p></th>
</tr>
<tr>
  <td><input type="text" class="halfbox" size="20" name="JobTitle" value="{job_title}" /></td>
  <td>
      <select name="CareerSectorID">
	    <option value="-1" {selected}></option>
	    <!-- BEGIN career_sector_tpl -->
	    <option value="{career_sector_id}" {selected}>{career_sector_name}</option>
	    <!-- END career_sector_tpl -->
      </select>
  </td>
</tr>
</table>

<p class="boxtext">*{intl-description_for_the_job}:</p>
<textarea class="box" cols="20" rows="8" name="Description">{description}</textarea>

<br />

<p class="boxtext">{intl-url_for_further_information}:</p>
<input type="text" class="box" size="20" name="FurtherInformation" value="{further_information}" />

<br />
<br />

<table width="100%" cellpadding="0" cellspacing="4" border="0">
<tr>
	<select name="PaidPosition">
          <option value="1" {1_paid_position_selected}>{intl-paid_position}</option>
	  <option value="2" {2_paid_position_selected}>{intl-unpaid_position}</option>
	</select>
<td>
<p class="boxtext">{intl-salary_or_benefits}:</p>
<input type="text" class="halfbox" size="20" name="SalaryOrBenefits" value="{salary_or_benefits}" />
</td>
</tr>
<tr>
<td>
<p class="boxtext">{intl-type_of_work}:</p>
{intl-you_can_indicate}
</td>
<td>
<p class="boxtext">{intl-sectors_the_work}:</p>
{intl-defaults_to_list}
</td>
</tr>
<tr>
<td>
<!-- BEGIN type_of_work_list_tpl -->
	<select multiple size="5" name="TypeOfWork[]">
	<!-- BEGIN type_of_work_tpl --> 
	<option value="{type_of_work_id}" {is_selected}>{type_of_work_name}</option>
	<!-- END type_of_work_tpl -->
	</select>
<!-- END type_of_work_list_tpl -->
</td>
<td>
	<!-- BEGIN related_sectors_list_tpl --> 
	<select multiple size="5" name="RelatedSectors[]">
	<!-- BEGIN related_sectors_tpl --> 
	<option value="{related_sectors_id}" {is_selected}>{related_sectors_name}</option>
	<!-- END related_sectors_tpl -->
	</select>
	<!-- END related_sectors_list_tpl --> 
</td>
</tr>
<tr>
<td><p class="boxtext">
<input type="checkbox" name="ShowClosingDate" value="{show_closing_date}" {is_checked}/>
{intl-show_closing_date}<p></td><td><p class="boxtext">{intl-languages_needed}:</p></td>
</tr>
<tr>
<td>
	<select name="ClosingDay">
	<!-- BEGIN closing_day_tpl --> 
	<option value="{closing_day_value}">{closing_day_value}</option>
	<!-- END closing_day_tpl --> 
	</select>
	&nbsp;
	<select name="ClosingMonth">
	<option value="1" {1_selected}>{intl-jan}</option>
	<option value="2" {2_selected}>{intl-feb}</option>
	<option value="3" {3_selected}>{intl-mar}</option>
	<option value="4" {4_selected}>{intl-apr}</option>
	<option value="5" {5_selected}>{intl-may}</option>
	<option value="6" {6_selected}>{intl-jun}</option>
	<option value="7" {7_selected}>{intl-jul}</option>
	<option value="8" {8_selected}>{intl-aug}</option>
	<option value="9" {9_selected}>{intl-sep}</option>
	<option value="10" {10_selected}>{intl-oct}</option>
	<option value="11" {11_selected}>{intl-nov}</option>
	<option value="12" {12_selected}>{intl-dec}</option>
	</select>
	&nbsp;
	<input type="text" size="4" maxlength="4" name="ClosingYear" value="{closing_year}" />

</td>
<td rowspan="2">
	<select multiple size="5" name="Languages">
	<!-- BEGIN languages_tpl --> 
	<option value="{language_id}">{language_name}</option>
	<!-- END languages_tpl --> 
	</select>
</td>
</tr>
<tr>
<td><p class="boxtext">{intl-earliest_date_advert}</p>
	<select name="EarliestAdvertDay">
	<!-- BEGIN earliest_advert_day_tpl --> 
	<option value="{advert_day_value}">{advert_day_value}</option>
	<!-- END earliest_advert_day_tpl --> 
	</select>
	&nbsp;
	<select name="EarliestAdvertMonth">
	<option value="1" {1_selected}>{intl-jan}</option>
	<option value="2" {2_selected}>{intl-feb}</option>
	<option value="3" {3_selected}>{intl-mar}</option>
	<option value="4" {4_selected}>{intl-apr}</option>
	<option value="5" {5_selected}>{intl-may}</option>
	<option value="6" {6_selected}>{intl-jun}</option>
	<option value="7" {7_selected}>{intl-jul}</option>
	<option value="8" {8_selected}>{intl-aug}</option>
	<option value="9" {9_selected}>{intl-sep}</option>
	<option value="10" {10_selected}>{intl-oct}</option>
	<option value="11" {11_selected}>{intl-nov}</option>
	<option value="12" {12_selected}>{intl-dec}</option>
	</select>
	&nbsp;
	<input type="text" size="4" maxlength="4" name="EarliestAdvertYear" value="{earliest_advert_year}" />

</td>
</tr>
<tr>
  <td><p class="boxtext">*{intl-country_job_based}:</p></td>
  <td><p class="boxtext">{intl-location_within_the_country}:</p></td>
</tr>
<tr>
  <td>
	<select name="CountryJobBased">
	<option value="-1"></option>
	<!-- BEGIN country_job_based_tpl --> 
	<option value="{language_id}">{language_name}</option>
	<!-- END country_job_based_tpl --> 
	</select>
  </td>
  <td>
    <input type="text" size="20" class="halfbox" name="LocationWithinCountry" value="{location_within_country}" />
  </td>
</tr>
<tr>
  <td colspan="2">
  <p class="boxtext">{intl-how_to_apply}</p>
  <textarea class="box" cols="20" rows="4" name="HowToApply">{how_to_apply}</textarea>

  </td>
</tr>
</table>

<br />
<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
</form>
<br />
<br />
*{intl-required_fields}

