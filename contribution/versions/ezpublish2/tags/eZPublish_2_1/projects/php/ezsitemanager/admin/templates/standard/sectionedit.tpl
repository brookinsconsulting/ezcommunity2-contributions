<form method="post" action="/sitemanager/section/edit/{section_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="30" name="Name" value="{section_name}" />
	</td>	
	<td>
	<p class="boxtext">{intl-sitedesign}:</p>
	<input type="text" size="30" name="SiteDesign" value="{section_sitedesign}" />
	</td>	
</tr>
<tr>
	<td>
	<br />
	<p class="boxtext">{intl-description}:</p>
	<textarea name="Description" wrap="soft" cols="30" rows="10">{section_description}</textarea>
	</td>	
</tr>
</table>

<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
