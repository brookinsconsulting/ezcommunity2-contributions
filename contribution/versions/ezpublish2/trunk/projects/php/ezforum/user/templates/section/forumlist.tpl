<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">

	<img src="/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="/forum/categorylist/">{intl-forum-main}</a> 
	<img src="/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="/forum/forumlist/{category_id}/">{category_name}</a>

	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<!-- BEGIN view_forums_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <th colspan="2">{intl-forum}:</th>
        <th>{intl-forum_description}:</th>
        <th class="right">{intl-threads}/<br />{intl-messages}:</th>
    </tr>
    <!-- BEGIN forum_item_tpl -->
    <tr>
	<td class="{td_class}" width="1%">
	    <img src="/images/forum.gif" width="16" height="16" border="0" alt="Forum" />
	</td>
	<td class="{td_class}" width="50%">
	    <a href="/forum/messagelist/{forum_id}/">{name}</a>
        </td>
	<td class="{td_class}" width="48%">
	    <span class="small">{description}</span>
        </td>
	<td class="{td_class}" align="right" width="1%">
	    {threads} / {messages}
        </td>
    </tr>
    <!-- END forum_item_tpl -->

</table>
<!-- END view_forums_tpl -->
<br />

