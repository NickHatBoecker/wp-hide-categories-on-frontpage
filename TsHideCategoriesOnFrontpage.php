<?php
/*
Plugin Name: Hide Categories On Frontpage
Version: 1.0
Description: Choose categories you want to hide on front page.
Author: Nick Böcker
Author URI: http://www.nick-hat-boecker.de
Text Domain: ts-hide-categories-on-frontpage
*/
class TsHideCategoriesOnFrontpage
{
    public static $textDomain = 'ts-hide-categories-on-frontpage';

    public static function loadTextdomain()
    {
        load_plugin_textdomain(self::$textDomain, false, dirname(plugin_basename(__FILE__)).'/languages');
    }

    public static function hideCategories($query)
    {
        // Only hide categories on front page in main query
        if (!$query->is_home() || !$query->is_main_query()) {
            return;
        }

        // Get selected categories from theme options
        $categoriesToHide = get_theme_mod('ts_hide_categories_on_frontpage');
        if (empty($categoriesToHide)) {
            return;
        }

        // Negotiate category ids
        foreach ($categoriesToHide as &$category) {
            $category = '-'.$category;
        }

        $query->set('cat', implode(',', $categoriesToHide));

        return;
    }

    /**
     * Register multiple selection field for categories
     *
     * @param WP_Customize_Manager $customizer
     */
    public static function registerSettings($customizer)
    {
        // Add custom class for multiple select fields
        include dirname(__FILE__).'/TsCustomizeMultipleSelect.php';

        $customizer->add_section(
            'ts_hide_categories_on_frontpage_section',
            array(
                'title' => __('Hide Categories on Frontpage', self::$textDomain),
                'description' => __('Choose which categories to hide on front page.', self::$textDomain),
                'priority' => 35,
            )
        );

        $customizer->add_setting('ts_hide_categories_on_frontpage');

        $availableCategories = array();

        // Get categories for select field
        $categories = get_categories();
        foreach ($categories as &$category) {
            $availableCategories[$category->term_id] = $category->name;
        }

        $customizer->add_control(
            new TsCustomizeMultipleSelect(
                $customizer,
                'ts_hide_categories_on_frontpage',
                array(
                    'label' => __('Categories'),
                    'section' => 'ts_hide_categories_on_frontpage_section',
                    'settings' => 'ts_hide_categories_on_frontpage',
                    'type' => 'ts-multiple-select',
                    'choices' => $availableCategories,
                )
            )
        );
    }
}

add_action('customize_register', array('TsHideCategoriesOnFrontpage', 'registerSettings'));
add_action('pre_get_posts', array('TsHideCategoriesOnFrontpage','hideCategories'));
add_action('plugins_loaded', array('TsHideCategoriesOnFrontpage', 'loadTextdomain'));
