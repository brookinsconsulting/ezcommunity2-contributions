<h1>TODO LISTE   bruker: {user}</h1>
<form method="post" action="/todo/todolist/">
<p>
{intl-user}<br>
<select name="GetByUserID">
{user_select}
</select>

<input type="hidden" name="Action" value="ShowTodosByUser">
<input type="submit" value="Vis">
</form>
</p>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	Tittel:
	</td>

	<td>
	Kategori:
	</td>

	<td>
	Dato:
	</td>

	<td>
	Forfall:
	</td>

	<td>
	Prioritert:
	</td>

	<td>
	Private:
	</td>

	<td>
	Status:
	</td>

	<td>
	Rediger
	</td>

	<td>
	Slett
	</td>
</tr>
{todos}
</table>
<br>
<a href="/todo/todoedit/">Ny todo</a>
