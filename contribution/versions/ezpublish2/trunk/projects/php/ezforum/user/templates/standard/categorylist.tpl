<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
        <form action="/forum/search/" method="post">
           <input type="text" name="QueryString" size="12" />
           <input type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th>{intl-name}:</th>
   	<th>{intl-desc}:</th>
</tr>


<!-- BEGIN category_item_tpl -->
<tr bgcolor="{color}">
    <td class={td_class}>
        <a href="/forum/forumlist/{category_id}/">{category_name}</a>
    </td>
    <td class={td_class}>
        {category_description}
    </td>
</tr>
<!-- END category_item_tpl -->
</table>

