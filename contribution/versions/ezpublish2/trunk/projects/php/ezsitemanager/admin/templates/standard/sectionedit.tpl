<form method="post" action="{www_dir}{index}/sitemanager/section/edit/{section_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{section_name}" />
<br />

<p class="boxtext">{intl-sitedesign}:</p>
<input type="text" class="box" size="40" name="SiteDesign" value="{section_sitedesign}" />
<br />

<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" wrap="soft" cols="40" rows="10">{section_description}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
