<form method="post" action="/article/categoryedit/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>
	</td>	
	<td>
	<input type="checkbox" name="ExcludeFromSearch" {exclude_checked} />
	<span class="boxtext">{intl-exclude_from_search}</span>
	</td>
</tr>
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

	<p class="boxtext">{intl-place}:</p>
	<select name="ParentID">
	<option value="0">{intl-categoryroot}</option>

	<!-- BEGIN value_tpl -->
	<option {selected} value="{option_value}">{option_level}{option_name}</option>
	<!-- END value_tpl -->

	</select>

	</td>
  	<td>
	<p class="boxtext">{intl-sort_mode}:</p>
	<select name="SortMode">

	<option value="1">{intl-publishing_date}</option>
	<option value="2">{intl-alphabetic_asc}</option>
	<option value="3">{intl-alphabetic_desc}</option>
	<option value="4">{intl-absolute_placement}</option>

	</select>

	</td>
</tr>
</table>


<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
       <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
	
