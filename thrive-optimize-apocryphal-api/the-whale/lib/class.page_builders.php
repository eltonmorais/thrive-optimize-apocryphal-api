<?php

namespace apt\thewhale;

class page_builders
{

    static function is_page_builder()
    {
        $thrive_themes_builder = strpos($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], "tve=true");
        $elementor_builder = false;
        $beaver_builder = false;

        if ($thrive_themes_builder !== false && $elementor_builder !== false && $beaver_builder !== false) {
            return true;
        }
        return false;
    }
}
