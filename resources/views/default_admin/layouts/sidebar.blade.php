<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">ADMIN</li>
            {!! AdminHelper::sidebar_item("Dashboard", "fa-dashboard", "AdminController@index") !!}
            {!! AdminHelper::sidebar_item("Settings", "fa-cog", "AdminController@index") !!}
            {!! AdminHelper::sidebar_item("Themes", "fa-paint-brush", "AdminController@index") !!}
            <li class="header">CONTENTS</li>
            {!! AdminHelper::sidebar_item("Page", "fa-sticky-note-o", "AdminController@index") !!}
            {!! AdminHelper::sidebar_item("Post", "fa-edit", "AdminController@index") !!}
            <li class="treeview">
                <a href=""><i class="fa fa-link"></i> <span>Test 3</span></a>
                <ul class="treeview-menu">
                    <li><a href="">3.1</a></li>
                    <li><a href="">3.2</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>