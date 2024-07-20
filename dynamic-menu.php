<?php

declare(strict_types=1);

namespace TAHITI;

class Dynamic_Menu {
    /**
     * Array of navigation menu items
     * @var array $menu_items
     */
    protected $menu_items;

    /**
     * Default constructor
     * @param string $menu_location Menu location
     */
    public function __construct($menu_location) {
        // Get navigation menu id
        $menu_id = get_nav_menu_locations()[$menu_location];
        // Get navigation menu items
        $this->menu_items = wp_get_nav_menu_items($menu_id);

        echo $this->render_menu();
    }

    /**
     * Get all child items for passed parent item
     * @param int $parent_id Id of parent item
     * @return array Sub-menu objects
     */
    private function get_child_menu_items($parent_id) {
        $child_menus = [];

        if (!empty($this->menu_items) && is_array($this->menu_items)) {
            foreach ($this->menu_items as $item) {
                if (intval($item->menu_item_parent) === $parent_id) {
                    $child_menus[] = $item;
                }
            }
        }

        return $child_menus;
    }

    /**
     * Render navigation menu
     * @param array $menu_items
     * @param int $parent_id
     * @return string
     */
    protected function render_menu($menu_items = null, $parent_id = 0) {
        if (!empty($this->menu_items) && is_array($this->menu_items)) {
            $menu_items = !empty($menu_items) ? $menu_items : $this->menu_items;
            $html = '';

            foreach ($this->menu_items as $item) {
                if (intval($item->menu_item_parent) === $parent_id) {
                    $child_menu_items = $this->get_child_menu_items($item->ID);

                    if (!empty($child_menu_items)) {
                        $html .= '<li class="nav-item dropdown">';
                        $html .= '<a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="nav-link dropdown-toggle" href="' . $item->url . '">' . $item->title . ' <div class="hover"></div></a>';
                        $html .= '<ul class="dropdown-menu">';
                        $html .= $this->render_menu($child_menu_items, $item->ID);
                        $html .= '</ul>';
                        $html .= '</li>';
                    } else {
                        $html .= '<li class="nav-item">';
                        $html .= '<a class="nav-link" href="' . $item->url . '">' . $item->title . ' <div class="hover"></div></a>';
                        $html .= '</li>';
                    }
                }
            }
        }

        return $html;
    }
}
