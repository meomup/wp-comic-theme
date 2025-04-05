<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package WP-Bootstrap-Navwalker
 */

if (!class_exists('WP_Bootstrap_Navwalker')) {
    /**
     * WP_Bootstrap_Navwalker class.
     *
     * @extends Walker_Nav_Menu
     */
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
        /**
         * Starts the element output.
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param WP_Post  $item   Menu item data object.
         * @param int      $depth  Depth of menu item.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         * @param int      $id     Current item ID.
         */
        public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = ($depth) ? str_repeat($t, $depth) : '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            
            // Initialize some holder variables to store specially handled item
            // wrappers and icons.
            $linkmod_classes = array();
            $icon_classes    = array();

            // Get an updated $classes array without linkmod or icon classes.
            $classes = self::separate_linkmods_and_icons_from_classes($classes, $linkmod_classes, $icon_classes);
            
            // Join any icon classes plucked from $classes into a string.
            $icon_class_string = join(' ', $icon_classes);

            /**
             * Filters the arguments for a single nav menu item.
             */
            $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

            // Add .dropdown for parent elements
            if ($args->has_children) {
                $classes[] = 'dropdown';
            }

            // Add .active classes
            if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
                $classes[] = 'active';
            }

            // Add some additional default classes to the item.
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'nav-item';

            // Allow filtering the classes.
            $classes = apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth);

            // Form a string of classes in format: class="class_names".
            $class_names = join(' ', $classes);
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            /**
             * Filters the ID applied to a menu item's list item element.
             */
            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';

            // Initialize array for holding the $atts for the link item.
            $atts = array();

            // Set title from item to the $atts array
            $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';

            // If the item has children, add atts to a.
            if ($args->has_children && 0 === $depth) {
                $atts['href']          = '#';
                $atts['data-bs-toggle'] = 'dropdown';
                $atts['class']         = 'nav-link dropdown-toggle';
                $atts['id']            = 'navbarDropdown';
                $atts['role']          = 'button';
                $atts['aria-expanded']  = 'false';
            } else {
                if (true === $args->has_children) {
                    $atts['href'] = '#';
                    $atts['data-bs-toggle'] = 'dropdown';
                    $atts['class'] = 'dropdown-item dropdown-toggle';
                } else {
                    $atts['href'] = !empty($item->url) ? $item->url : '#';
                    // For items in dropdowns use .dropdown-item instead of .nav-link.
                    if ($depth > 0) {
                        $atts['class'] = 'dropdown-item';
                    } else {
                        $atts['class'] = 'nav-link';
                    }
                }
            }

            $atts = self::update_atts_for_linkmod_type($atts, $linkmod_classes);

            // Allow filtering of the $atts array before using it.
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

            // Build a string of html containing all the atts for the item.
            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value       = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            // Initialize the empty variable that will be used to indicate if linkmod_type flags were set.
            $linkmod_type = '';

            // Set the default HTML for output.
            $item_output = isset($args->before) ? $args->before : '';

            // This is the start of the internal nav item. Depending on what
            // kind of linkmod we have we may need different wrapper elements.
            if ('mega-menu' === $linkmod_type) {
                $item_output .= '<div class="mega-menu" ' . $attributes . '>';
            } else {
                // With no link mod type set this must be a standard <a> tag.
                $item_output .= '<a' . $attributes . '>';
            }

            // Initiate empty icon var, then if we have a string containing any
            // icon classes form the icon markup with an <i> element. This is
            // output inside of the item before the $title (the link text).
            $icon_html = '';
            if (!empty($icon_class_string)) {
                // Append an <i> with the icon classes to what is output before links.
                $icon_html = '<i class="' . esc_attr($icon_class_string) . '"></i> ';
            }

            /** This filter is documented in wp-includes/post-template.php */
            $title = apply_filters('the_title', $item->title, $item->ID);

            /**
             * Filters a menu item's title.
             */
            $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

            // If the .sr-only class was set apply to the nav items text only.
            if (in_array('sr-only', $linkmod_classes, true)) {
                $title         = self::wrap_for_screen_reader($title);
                $keys_to_unset = array_keys($linkmod_classes, 'sr-only', true);
                foreach ($keys_to_unset as $k) {
                    unset($linkmod_classes[$k]);
                }
            }

            // Put the item contents into $output.
            $item_output .= isset($args->link_before) ? $args->link_before . $icon_html . $title . $args->link_after : '';

            if ('mega-menu' === $linkmod_type) {
                // For a mega-menu, close the item with a div.
                $item_output .= '</div>';
            } else {
                // With no link mod type set this must be a standard <a> tag.
                $item_output .= '</a>';
            }

            $item_output .= isset($args->after) ? $args->after : '';

            // Add the caret if menu item has children.
            if ($args->has_children && 0 === $depth) {
                $item_output .= '<span class="caret"></span>';
            }

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        /**
         * Menu fallback for bootstrap menu
         *
         * @param array $args passed from the wp_nav_menu function.
         * @return string
         */
        public static function fallback($args) {
            if (current_user_can('edit_theme_options')) {
                $container       = $args['container'];
                $container_id    = $args['container_id'];
                $container_class = $args['container_class'];
                $menu_class      = $args['menu_class'];
                $menu_id         = $args['menu_id'];

                if ($container) {
                    echo '<' . esc_attr($container);
                    if ($container_id) {
                        echo ' id="' . esc_attr($container_id) . '"';
                    }
                    if ($container_class) {
                        echo ' class="' . esc_attr($container_class) . '"';
                    }
                    echo '>';
                }
                echo '<ul';
                if ($menu_id) {
                    echo ' id="' . esc_attr($menu_id) . '"';
                }
                if ($menu_class) {
                    echo ' class="' . esc_attr($menu_class) . '"';
                }
                echo '>
                    <li class="nav-item"><a href="' . esc_url(admin_url('nav-menus.php')) . '" class="nav-link">Thêm menu</a></li>
                </ul>';
                if ($container) {
                    echo '</' . esc_attr($container) . '>';
                }
            }
        }

        /**
         * Ends the list of after the elements are added.
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param WP_Post  $item   Menu item data object.
         * @param int      $depth  Depth of menu item.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function end_el(&$output, $item, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $output .= "</li>{$n}";
        }

        /**
         * Traverse elements to create list from elements.
         *
         * Display one element if the element doesn't have any children otherwise,
         * display the element and its children. Will only traverse up to the max
         * depth and no ignore elements under that depth. It is possible to set the
         * max depth to include all depths, see walk() method.
         *
         * This method should not be called directly, use the walk() method instead.
         *
         * @param object $element           Data object.
         * @param array  $children_elements List of elements to continue traversing.
         * @param int    $max_depth         Max depth to traverse.
         * @param int    $depth             Depth of current element.
         * @param array  $args              An array of arguments.
         * @param string $output            Used to append additional content (passed by reference).
         */
        public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
            if (!$element) {
                return;
            }
            $id_field = $this->db_fields['id'];
            // Display this element.
            if (is_object($args[0])) {
                $args[0]->has_children = !empty($children_elements[$element->$id_field]);
            }
            parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }

        /**
         * Find any custom linkmod or icon classes and store in their holder
         * arrays then remove them from the main classes array.
         *
         * Supported linkmods: .disabled, .dropdown-header, .dropdown-divider, .sr-only
         * Supported iconsets: Font Awesome 4/5, Glypicons
         *
         * @param array   $classes         an array of classes currently assigned to the item.
         * @param array   $linkmod_classes an array to hold linkmod classes.
         * @param array   $icon_classes    an array to hold icon classes.
         * @param integer $depth           an integer holding current depth level.
         *
         * @return array  $classes         a maybe modified array of classnames.
         */
        private function separate_linkmods_and_icons_from_classes($classes, &$linkmod_classes, &$icon_classes) {
            // Loop through $classes array to find linkmod or icon classes.
            foreach ($classes as $key => $class) {
                // If any special classes are found, store the class in it's
                // holder array and and unset the item from $classes.
                if (preg_match('/^fa-|^fas-|^fab-|^far-|^fal-|^fad-|^fa-solid|^fa-regular|^fa-brands|^fa-light|^fa-duotone|^fa-thin/i', $class)) {
                    // Font Awesome.
                    $icon_classes[] = $class;
                    unset($classes[$key]);
                } elseif (preg_match('/^glyphicon-/', $class)) {
                    // Glyphicons.
                    $icon_classes[] = $class;
                    unset($classes[$key]);
                } elseif (preg_match('/^(fa|fas|fab|far|fal|fad)$/i', $class)) {
                    // Font Awesome type classes.
                    $icon_classes[] = $class;
                    unset($classes[$key]);
                } elseif (preg_match('/^(disabled|sr-only|dropdown-header|dropdown-divider|mega-menu)/', $class)) {
                    // Handle link mods.
                    $linkmod_classes[] = $class;
                    unset($classes[$key]);
                }
            }

            return $classes;
        }

        /**
         * Return a string containing a linkmod type and update $atts array
         * accordingly depending on the decided.
         *
         * @param array $atts            array of atts for the current link in nav item.
         * @param array $linkmod_classes array of classes that indicate link modification type.
         *
         * @return string                empty for default, a linkmod type string otherwise.
         */
        private function update_atts_for_linkmod_type($atts = array(), $linkmod_classes = array()) {
            $linkmod_type = '';
            if (!empty($linkmod_classes)) {
                foreach ($linkmod_classes as $link_class) {
                    if (!empty($link_class)) {
                        // Check for special class types and set a flag for them.
                        if ('dropdown-header' === $link_class) {
                            $linkmod_type = 'dropdown-header';
                        } elseif ('dropdown-divider' === $link_class) {
                            $linkmod_type = 'dropdown-divider';
                        } elseif ('mega-menu' === $link_class) {
                            $linkmod_type = 'mega-menu';
                        }
                    }
                }
            }

            // Set a class on the anchor based on link mod type.
            if ('dropdown-header' === $linkmod_type) {
                // .dropdown-header anchors shouldn't get the .dropdown-toggle class.
                // Also remove .nav-link as this is not a true link.
                foreach (array('data-bs-toggle', 'role', 'class', 'aria-expanded') as $att) {
                    unset($atts[$att]);
                }
                $atts['class'] = 'dropdown-header';
            } elseif ('dropdown-divider' === $linkmod_type) {
                // .dropdown-divider anchors shouldn't get the .dropdown-toggle or .nav-link classes.
                foreach (array('data-bs-toggle', 'role', 'class', 'aria-expanded') as $att) {
                    unset($atts[$att]);
                }
                $atts['class'] = 'dropdown-divider';
            }

            return $atts;
        }

        /**
         * Wraps the passed text in a screen reader only class.
         *
         * @param string $text the string of text to be wrapped in a screen reader class.
         * @return string      the string wrapped in a span with the class.
         */
        private function wrap_for_screen_reader($text = '') {
            if ($text) {
                $text = '<span class="sr-only">' . $text . '</span>';
            }
            return $text;
        }
    }
}
