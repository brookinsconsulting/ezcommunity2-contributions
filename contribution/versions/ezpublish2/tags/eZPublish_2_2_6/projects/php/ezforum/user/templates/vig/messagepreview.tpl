<h1>{intl-preview_headline}</h1>

{message_path_file}

<br />

{message_body_file}

<form method="post" action="{www_dir}{index}/forum/messageedit/{action_value}/{message_id}">
{message_hidden_form_file}

    <input class="stdbutton" type="submit" name="EditButton" value="{intl-edit}" />

	<hr noshade="noshade" size="4" />
    
	<input class="okbutton" type="submit" name="PostButton" value="{intl-post}" />
    &nbsp;
	<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
