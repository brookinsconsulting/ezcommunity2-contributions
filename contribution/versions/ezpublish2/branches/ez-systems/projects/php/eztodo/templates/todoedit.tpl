<form method="post" action="index.php?page={document_root}todoedit.php">
<h1>{head_line}</h1>

<p>
{intl-title}<br>
<input type="text" name="Title" value="{title}">
</p>

<p>
{intl-desc}<br>
<textarea rows="5" name="Text">{text}</textarea>
</p>

<p>
{intl-cat}<br>
<select name="Category">
{category_select}
</select>
</p>


<p>
{intl-pri}<br>
<select name="Priority">
{priority_select}
</select>
</p>

<p>
{intl-date}<br>
Klokke: 
<input size="4" type="text" name="Hour" value="{hour}">
Dato:<input size="4" type="text" name="Mnd" value="{mnd}">
År:<input size="4" type="text" name="Year" value="2000">
</p>

<p>
{intl-user}<br>
<select name="User">
{user_select}
</select>
</p>

<p>
{intl-owner}<br>
<select name="Owner">
{user_select}
</select>
</p>

{intl-public}<input type="checkbox" name="Public"><br>




<input type="hidden" name="TodoID" value="{todo_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" value="{submit_text}">

</form>
