<form method="post" action="/newsfeed/sourcesite/">

<h1>{intl-sourcesite_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-source_site_title}:</p>
<input type="text" size="40" name="SourceSiteTitle" value="{source_site_title_value}" />

<p class="boxtext">{intl-source_site_url}:</p>
<input type="text" size="40" name="SourceSiteURL" value="{source_site_url_value}" />

<p class="boxtext">{intl-source_site_login}:</p>
<input type="text" size="40" name="SourceSiteLogin" value="{source_site_login_value}" />

<p class="boxtext">{intl-source_site_password}:</p>
<input type="text" size="40" name="SourceSitePassword" value="{source_site_password_value}" />

<p class="boxtext">{intl-source_site_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-sourcesite_intro}:</p>
<textarea cols="40" rows="5" wrap="soft" name="SourcesiteIntro">{sourcesite_intro_value}</textarea>

<p class="boxtext">{intl-sourcesite_url}:</p>
<input type="text" size="40" name="SourcesiteURL" value="{sourcesite_url_value}"/>

<p class="boxtext">{intl-sourcesite_keywords}:</p>
<input type="text" size="40" name="SourcesiteKeywords" value="{sourcesite_keywords_value}"/>

<br />
<input type="checkbox" name="IsPublished" {sourcesite_is_published} />
<span class="boxtext">{intl-sourcesite_is_published}</span><br />

<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
<input type="hidden" value="{action_value}" name="Action" />
<input type="hidden" value="{sourcesite_id}" name="SourcesiteID" />

</form>