<?php

namespace SourceBans\Core;

class CTabsMenu
{
    var $menuItems = array();

    function addMenuItem($title, $id, $description="", $url="", $external=false)
    {
        $curItem = array();
        $curItem['title'] = $title;
        $curItem['desc'] = $description;
        $curItem['url'] = $url;
        $curItem['external'] = $external;
        $curItem['id'] = $id;
        array_push($this->menuItems, $curItem);
    }

    function outputMenu()
    {
        $var = $this->menuItems;
        include TEMPLATES_PATH . "/admin.detail.navbar.php";
    }

    function getMenuArray()
    {
        return $this->menuItems;
    }
}