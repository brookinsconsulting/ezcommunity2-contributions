<form method="post" action="{www_dir}{index}/mail/folderedit/{current_folder_id}">

<h1>{intl-headline}</h1>

<hr noshade size="4" />

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Name" value="{folder_name}">

<p class="boxtext">{intl-folder}:</p>
<select name="ParentID">
<option value="0">{intl-topfolder}</option>
<!-- BEGIN top_imap_item_tpl -->
<option value="{account_id}-">{imap_topfolder} {intl-topfolder}</option>
<!-- END top_imap_item_tpl -->
<!-- BEGIN folder_item_tpl -->
<option value="{folder_parent_id}" {is_selected}>{folder_parent_name}</option>
<!-- END folder_item_tpl -->
</select>
<br />
<br />

<hr noshade size="4" />

<input class="okbutton" name="Ok" type="submit" value="{intl-ok}">
&nbsp;
<input class="okbutton" name="Cancel" type="submit" value="{intl-cancel}">
</form>
