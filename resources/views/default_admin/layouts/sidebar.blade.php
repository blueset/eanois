<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">ADMIN</li>
            {!! AdminHelper::sidebar_item("Dashboard", "fa-dashboard", "AdminController@index") !!}
            {!! AdminHelper::sidebar_item("Settings", "fa-cog", "AdminController@viewSettings") !!}
            {!! AdminHelper::sidebar_item("Themes", "fa-paint-brush", "AdminController@index") !!}
            <li class="header">CONTENTS</li>
            {!! AdminHelper::sidebar_treeview("Pages", "fa-sticky-note-o", [
                ["name" => "New", "icon" => "fa-plus", "action" => "AdminController@index"],
                ["name" => "All pages", "icon" => "fa-list", "action" => ["AdminController@index", "AdminController@index"]],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Posts", "fa-edit", [
                ["name" => "New", "icon" => "fa-plus", "action" => "AdminController@index"],
                ["name" => "All pages", "icon" => "fa-list", "action" => ["AdminController@index", "AdminController@index"]],
                ["name" => "Categories", "icon" => "fa-bookmark", "action" => "AdminController@index"],
                ["name" => "Tags", "icon" => "fa-tags", "action" => "AdminController@index"],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Images", "fa-picture-o", [
                ["name" => "Upload", "icon" => "fa-upload", "action" => "AdminController@index"],
                ["name" => "Library", "icon" => "fa-list", "action" => ["AdminController@index", "AdminController@index"]],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Files", "fa-file", [
                ["name" => "Upload", "icon" => "fa-upload", "action" => "AdminController@index"],
                ["name" => "Library", "icon" => "fa-list", "action" => ["AdminController@index", "AdminController@index"]],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Links", "fa-link", [
                ["name" => "New", "icon" => "fa-plus", "action" => "AdminController@index"],
                ["name" => "All links", "icon" => "fa-list", "action" => ["AdminController@index", "AdminController@index"]],
            ]) !!}
        </ul>
    </section>
</aside>