<h1>{intl-page_title}</h1>

<!-- BEGIN error_template -->
{intl-admin_error}
<ol>
    <!-- BEGIN error_row_template -->
    <li>{error_text}
    <!-- END error_row_template -->
</ol>

<!-- END error_template -->

    <!-- BEGIN orphans_template -->
    <h1>{orphans_count} {orphans_string} {intl-found}{intl-sorted_by} {orphans_direction}</h1>
    <table>
        <tr bgcolor="lightblue">
            <td>{intl-item_name} [ <a href="?{query_string}orphan=sortby+alphabetical">{intl-sort_alphabetical_verb}</a> | <a href="?{query_string}orphan=sortby+alphabetical+reverse">{intl-sort_alphabetical_reverse_verb}</a> ]</td>
            <td>{intl-item_createdat} [ <a href="?{query_string}orphan=sortby+date">{intl-sort_date_verb}</a> | <a href="?{query_string}orphan=sortby+date+reverse">{intl-sort_date_reverse_verb}</a> ]</td>
            <td>{intl-item_view}</td>
            <td>{intl-item_edit}</td>
            <td>{intl-item_delete}</td>
        </tr>
    
        <!-- BEGIN orphan_item_template -->
        <tr>
            <td>{orphan_name}</td>
            <td>{orphan_createdat}</td>
            <td><a href="{orphan_id}">View</A></td>
            <td><a href="{orphan_id}?edit">Edit</A></td>
            <td><a href="{orphan_id}?delete">Delete</A></td>
        </tr>
        <!-- END orphan_item_template -->

    </table>
    <!-- END orphans_template -->

    <!-- BEGIN navigate_template -->
    <h1>{navigate_count} {navigate_string} {intl-found}{intl-sorted_by} {navigate_direction}</h1>
    <table>
        <tr bgcolor="lightblue">
            <td>{intl-item_name} [ <a href="?{query_string}navigate=sortby+alphabetical">{intl-sort_alphabetical_verb}</a> | <a href="?{query_string}navigate=sortby+alphabetical+reverse">{intl-sort_alphabetical_reverse_verb}</a> ]</td>
            <td>{intl-item_createdat} [ <a href="?{query_string}navigate=sortby+date">{intl-sort_date_verb}</a> | <a href="?{query_string}navigate=sortby+date+reverse">{intl-sort_date_reverse_verb}</a> ]</td>
            <td>{intl-item_view}</td>
            <td>{intl-item_edit}</td>
            <td>{intl-item_delete}</td>
        </tr>
    
        <!-- BEGIN navigate_item_template -->
        <tr>
            <td>{navigate_name}</td>
            <td>{navigate_createdat}</td>
            <td><a href="{navigate_id}">View</A></td>
            <td><a href="{navigate_id}?edit">Edit</A></td>
            <td><a href="{navigate_id}?delete">Delete</A></td>
        </tr>
        <!-- END navigate_item_template -->

    </table>
    
    <a href="?add">{add sub item}</a>
    <!-- END navigate_template -->
