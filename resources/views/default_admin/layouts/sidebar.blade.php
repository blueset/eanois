<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">ADMIN</li>
            {!! AdminHelper::sidebar_item("Dashboard", "fa-dashboard", 'AdminController@index') !!}
            {!! AdminHelper::sidebar_item("Settings", "fa-cog", "AdminController@viewSettings") !!}
            {!! AdminHelper::sidebar_item("Themes", "fa-paint-brush", 'AdminController@themeIndex') !!}
            <li class="header">CONTENTS</li>
            {!! AdminHelper::sidebar_treeview("Pages", "fa-sticky-note-o", [
                ["name" => "New", "icon" => "fa-plus", "action" => 'Admin\PageController@create'],
                ["name" => "All pages", "icon" => "fa-list", "action" => ['Admin\PageController@index', 'Admin\PageController@edit']],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Posts", "fa-edit", [
                ["name" => "New", "icon" => "fa-plus", "action" => 'Admin\PostController@create'],
                ["name" => "All posts", "icon" => "fa-list", "action" => ['Admin\PostController@index', 'Admin\PostController@edit']],
                ["name" => "Categories", "icon" => "fa-bookmark", "action" => ['Admin\CategoryController@index', 'Admin\CategoryController@show']],
                ["name" => "Tags", "icon" => "fa-tags", "action" => ['Admin\TagController@index', 'Admin\TagController@show']],
            ]) !!}
            {!! AdminHelper::sidebar_treeview("Images", "fa-picture-o", [
                ["name" => "Library", "icon" => "fa-list", "action" => 'Admin\ImageController@index'],
            ]) !!}
            {{-- !! AdminHelper::sidebar_treeview("Files", "fa-file", [
                ["name" => "Upload", "icon" => "fa-upload", "action" => 'AdminController@index'],
                ["name" => "Library", "icon" => "fa-list", "action" => ['AdminController@index', 'AdminController@index']],
            ]) !! --}}
            {!! AdminHelper::sidebar_treeview("Links", "fa-link", [
                ["name" => "All links", "icon" => "fa-list", "action" => 'Admin\LinkController@index'],
            ]) !!}
        </ul>
    </section>
</aside>