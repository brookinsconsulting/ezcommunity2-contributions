<form method="post" action="index.php?page={document_root}todoedit.php">
<h1>{head_line}</h1>

<p>
{intl-title}<br>
<input type="text" name="Title" value="{title}">
</p>

<p>
{intl-desc}<br>
<textarea cols="25" rows="5" name="Text">{text}</textarea>
</p>

<p>
{intl-cat}<br>
<select name="CategoryID">
{category_select}
</select>
</p>


<p>
{intl-pri}<br>
<select name="PriorityID">
{priority_select}
</select>
</p>

{intl-date}<br>
<p>
Klokke:<input size="2" type="text" name="Hour" value="{hour}">:<input size="2" type="text" name="Minute" value="{hour}">
Dato:<input size="2" type="text" name="Mnd" value="{mnd}">-<input size="2" type="text" name="Day" value="{day}">
År:<input size="4" type="text" name="Year" value="2000">
</p>

<p>
{intl-user}<br>
<select name="UserID">
{user_select}
</select>
</p>

<p>
{intl-owner}<br>
<select name="OwnerID">
{owner_select}
</select>
</p>


{intl-public}<input type="checkbox" name="Permission" {permission}><br>
{intl-status}<input type="checkbox" name="Status" {status}><br>




<input type="hidden" name="TodoID" value="{todo_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
