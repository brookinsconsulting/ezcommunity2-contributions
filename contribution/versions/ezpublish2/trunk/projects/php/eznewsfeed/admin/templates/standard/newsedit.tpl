<h1>{intl-news_edit}</h1>
<form method="post" action="/newsfeed/news/">
<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-news_title}:</p>
<input type="text" size="40" name="NewsTitle" value="{news_title_value}"/>

<p class="boxtext">{intl-news_source}:</p>
<input type="text" size="40" name="NewsSource" value="{news_source_value}"/>

<p class="boxtext">{intl-news_date}:</p>
<input type="text" size="40" name="NewsDate" value="{news_date_value}"/>

<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>


<p class="boxtext">{intl-news_intro}:</p>
<textarea cols="40" rows="5" wrap="soft" name="NewsIntro">{news_intro_value}</textarea>

<p class="boxtext">{intl-news_url}:</p>
<input type="text" size="40" name="NewsURL" value="{news_url_value}"/>

<p class="boxtext">{intl-news_keywords}:</p>
<input type="text" size="40" name="NewsKeywords" value="{news_keywords_value}"/>


<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" value="{intl-ok}">
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
<input type="hidden" value="{action_value}" name="Action" />

</form>