<?php
/*
 * Plugin Name: Gulp Bundler
 * Version: 1.0
 * Description: Bundle CSS and JS in prod and dev modes
 * Author: Seva Denisov
 * Author URI: http://sevadenisov.ru/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if (!class_exists('GulpBundler')) {
    class GulpBundler {
        public function __construct() {}

        private function get_theme_json($json_path) {
            $json_src = get_template_directory() . $json_path;
            $json_file = file_get_contents($json_src);
            $json_to_array = json_decode($json_file, true);

            return $json_to_array;
        }

        public function render_bundle($bundle_name, $type) {
            if (SCRIPT_DEBUG || isset($_GET['vseva_debug'])) {
                $script_json = $this->get_theme_json('/gulp/bundle.develop.json');
                $template_dir = strstr(get_template_directory(), '/wp-content/') . '/gulp/';

                if ($type == 'styles') {
                    foreach($script_json['bundle'][$bundle_name][$type] as $stylesheet) {
                        echo '<link rel="stylesheet" href="' . $template_dir . $stylesheet . '" />' . "\n";
                    }
                } else if ($type == 'scripts') {
                    foreach($script_json['bundle'][$bundle_name][$type] as $script) {
                        echo '<script src="' . $template_dir. $script . '"></script>' . "\n";
                    }
                }
            } else {
                $production_json = $this->get_theme_json('/gulp/bundle.production.json');
                echo $production_json[$bundle_name][$type];
            }
        }
    }
}
