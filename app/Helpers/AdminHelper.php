<?php
/**
 * Created by PhpStorm.
 * User: blueset
 * Date: 28/3/16
 * Time: 10:08
 */

namespace App\Helpers;


use App\Http\Controllers\AdminController;

class AdminHelper
{

    /**
     * Check if the current url is under a certain action
     *
     * @param string $action Name of controller method
     * @param bool $css return string if true
     * @return string|boolean "active" or "" | T/F
     */
    static function sidebar_active($action, $css = true) {
        if ($css) {
            return starts_with(url()->current(), action($action)) ? "active" : "";
        } else {
            return starts_with(url()->current(), action($action));
        }

    }


    /**
     * Generate an AdminLTE 2 Sidebar item
     *
     * @param string $name
     * @param string|array $icon Font Awesome icon class name
     * @param string $action
     * @return string
     */
    public static function sidebar_item($name, $icon, $action) {
        $result = <<<EOT
<li class="%s">
    <a href="%s">
        <i class="fa %s"></i>
        <span>%s</span>
    </a>
</li>
EOT;
        $fa_classes = implode(' ', (array) $icon);
        return sprintf($result, self::sidebar_active($action), action($action), $fa_classes, $name);
    }

    public static function sidebar_treeview($name, $icon, $children = []) {
//        <li class="treeview">
//                <a href=""><i class="fa fa-link"></i> <span>Test 3</span></a>
//                <ul class="treeview-menu">
//                    <li><a href="">3.1</a></li>
//                    <li><a href="">3.2</a></li>
//                </ul>
//            </li>
        $treeview = '';
        $is_active = false;
        $a = [
            ["name" => "New", "icon" => "fa-plus", "action" => "Admin/PostController@add"],
            ["name" => "All posts", "icon" => "fa-list", "action" => "Admin/PostController@show"],
        ];
        foreach ($children as $i) {
            $this_active = self::sidebar_active($i['action']);
            $is_active |= $this_active;
            $active_class = $this_active ? ' class="active"' : '';
            $fa_classes = implode(' ', (array) $i['icon']);
            $treeview .= "<li".$active_class."><a href=\"".action($i['action'])."\"><i class=\"fa ".$fa_classes."\"></i> ".$i['name']."</a></li>";
        }
        $format = <<<EOT
<li class="treeview%s">
    <a href="%s"><i class="fa %s"></i> <span>%s</span></a>
    <ul class="treeview-menu">
        %s
    </ul>
</li>
EOT;
        $active_class = $is_active ? ' active' : '';
        $main_action = action($children[0]['action']);
        $fa_classes = implode(' ', (array) $icon);
        $result = sprintf($format, $active_class, $main_action, $fa_classes, $name, $treeview);
        return $result;
    }

}