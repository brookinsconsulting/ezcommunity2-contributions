<form method="post" action="/link/suggestlink/insert">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}</p>
<input type="text" name="title" size="40" value="{title}">

<p class="boxtext">{intl-category}</p>
<select name="linkgroup">
	<!-- BEGIN group_select_tpl -->
	<option {is_selected} value="{grouplink_id}">{grouplink_title}</option>
	<!-- END group_select_tpl -->
</select>

<p class="boxtext">{intl-url}</p>
<form method="post" action="/link/suggestlink/?Action=GetSite">
http://<input type="text" name="url" size="40" value="{url}"><input class="okbutton" type="submit" value="{intl-meta}">
</form>

<p class="boxtext">{intl-keywords}</p>
<textarea rows="5" cols="20" name="keywords">{keywords}</textarea>
<br />

<p class="boxtext">{intl-description}</p>
<textarea rows="5" cols="20" name="description">{description}</textarea>
<br />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}">

<form action="/link/group/">
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

</form>

