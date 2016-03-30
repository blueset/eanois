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
     * @param string|array $actions Name of controller method
     * @param bool $css return string if true
     * @return string|boolean "active" or "" | T/F
     */
    static function sidebar_active($actions, $css = true) {
        $result = false;
        foreach ((array) $actions as $i) {
            $result |= url()->current() == action($i);
        }
        if ($css) {
            return $result ? "active" : "";
        } else {
            return $result;
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


    /**
     * Generate a `treeview` item for sidebar
     *
     * Format for `$children`:
     * <code>
     * $children = [
     *     ["name" => "New", "icon" => "fa-plus", "action" => "AdminController@index"],
     *     ["name" => "All pages", "icon" => ["fa-list", "fa-flip-vertical"], "action" => ["AdminController@index", "AdminController@index"]],
     * ];
     * </code>
     *
     * <pre>
     * string "name" => Name of child element
     * string|array "icon" => FontAwesome icon class (fa is added by default)
     * string|array "action" => actions for this element
     *      first element is used for linking
     *      all elements are used for detecting "active" class
     * </pre>
     *
     * @param string $name name of the parent element
     * @param string|array $icon FontAwesome icon class
     * @param array $children Children elements
     * @return string
     */
    public static function sidebar_treeview($name, $icon, $children = []) {

        $treeview = '';
        $is_active = false;

        // treeview list

        foreach ($children as $i) {
            $this_active = self::sidebar_active($i['action']);
            $is_active = $is_active || $this_active;
            $active_class = $this_active ? ' class="active"' : '';
            $fa_classes = implode(' ', (array) $i['icon']);
            $this_action = (array) $i['action'];
            $this_action = action($this_action[0]);
            $treeview .= "<li".$active_class."><a href=\"".$this_action."\"><i class=\"fa ".$fa_classes."\"></i> ".$i['name']."</a></li>";
        }

        // framework

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


    public static function textField($name, $field, $value = "", $errors = null) {
        if (!is_null($errors)){
            $error_class = $errors->has('site_name') ? ' has-error' : '';
            $error_msg = $errors->has('site_name') ? "<span class=\"help-block\"><strong>".$errors->first($field)."</strong></span>" : "";
        } else {
            $error_class = '';
            $error_msg = '';
        }
        $value = old($field, $value);
        $format = <<<FRM
<div class="form-group$error_class">
    <label for="$field">$name</label>
    <input type="text" class="form-control" name="$field" value="$value">
    $error_msg
</div>
FRM;
        return $format;
    }

}