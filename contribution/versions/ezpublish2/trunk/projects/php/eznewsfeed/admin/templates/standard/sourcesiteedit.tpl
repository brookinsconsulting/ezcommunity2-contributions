<form method="post" action="/newsfeed/sourcesite/{action_value}/{source_site_id}">

<h1>{intl-sourcesite_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-source_site_name}:</p>
<input type="text" size="40" name="SourceSiteName" value="{source_site_name_value}" />

<p class="boxtext">{intl-source_site_url}:</p>
<input type="text" size="40" name="SourceSiteURL" value="{source_site_url_value}" />

<p class="boxtext">{intl-source_site_login}:</p>
<input type="text" size="40" name="SourceSiteLogin" value="{source_site_login_value}" />

<p class="boxtext">{intl-source_site_password}:</p>
<input type="text" size="40" name="SourceSitePassword" value="{source_site_password_value}" />

<p class="boxtext">{intl-source_site_decoder}:</p>
<input type="text" size="40" name="SourceSiteDecoder" value="{source_site_decoder_value}" />

<p class="boxtext">{intl-source_site_isactive}:</p>
<input {source_site_isactive_value} type="checkbox" name="SourceSiteIsActive" />


<p class="boxtext">{intl-source_site_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>
<br /><br />
<hr noshade="noshade" size="4" />
<br />
<input type="submit" class="okbutton" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>