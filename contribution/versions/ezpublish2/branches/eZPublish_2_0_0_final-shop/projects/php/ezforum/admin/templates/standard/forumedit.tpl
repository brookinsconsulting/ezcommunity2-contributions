<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form action="/forum/forumedit/{action_value}/{forum_id}/" method="post">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-forumname}:</p>
<input type="text" size="20" name="Name" value="{forum_name}">

<p class="boxtext">{intl-description}:</p>
<input type="description" size="40" name="Description" value="{forum_description}">

<p class="boxtext">{intl-category}:</p>
<select name="CategorySelectID">

	<!-- BEGIN category_item_tpl -->
	<option {is_selected} value="{category_id}">{category_name}</option>
	<!-- END category_item_tpl -->
</select>


<br />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
        <p class="boxtext">{intl-forum_is_moderated}:</p>
        <input type="checkbox" name="IsModerated" {forum_is_moderated} />
    </td>
    <td>
        &nbsp;
    </td>
    <td>
        <p class="boxtext">{intl-forum_is_anonymous}:</p>
        <input type="checkbox" name="IsAnonymous" {forum_is_anonymous} />
    </td>
</tr>
</table>

<br />
<p class="boxtext">{intl-moderator}:</p>
<select name="ModeratorID">

	<!-- BEGIN moderator_item_tpl -->
	<option {is_selected} value="{user_id}">{user_name}</option>
	<!-- END moderator_item_tpl -->
</select>

<br />
<p class="boxtext">{intl-read_group}:</p>
<select name="GroupID">
        <option value="0">{intl-everybody}</option>
	<!-- BEGIN group_item_tpl -->
	<option {is_selected} value="{group_id}">{group_name}</option>
	<!-- END group_item_tpl -->
</select>

<br /><br />
	
<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="modify" value="{intl-ok}">
	</form>
	</td>
	<td>
	&nbsp;
	</td>
	<td>
	<form method="post" action="/forum/categorylist">
	<input class="okbutton" type="submit" value="{intl-cancel}">
	</form>
	</td>
</tr>
</table>
