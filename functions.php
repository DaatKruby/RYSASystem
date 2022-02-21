<?php

/**
 * Neve functions.php file
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve
 */

define('NEVE_VERSION', '3.1.5');
define('NEVE_INC_DIR', trailingslashit(get_template_directory()) . 'inc/');
define('NEVE_ASSETS_URL', trailingslashit(get_template_directory_uri()) . 'assets/');
define('NEVE_MAIN_DIR', get_template_directory() . '/');

if (!defined('NEVE_DEBUG')) {
    define('NEVE_DEBUG', false);
}
define('NEVE_NEW_DYNAMIC_STYLE', true);
/**
 * Buffer which holds errors during theme inititalization.
 *
 * @var WP_Error $_neve_bootstrap_errors
 */
global $_neve_bootstrap_errors;

$_neve_bootstrap_errors = new WP_Error();

if (version_compare(PHP_VERSION, '7.0') < 0) {
    $_neve_bootstrap_errors->add(
        'minimum_php_version',
        sprintf(
            /* translators: %s message to upgrade PHP to the latest version */
            __("Hey, we've noticed that you're running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by %1\$s. Neve's minimal requirement is PHP%2\$s.", 'neve'),
            sprintf(
                /* translators: %s message to upgrade PHP to the latest version */
                '<a href="https://wordpress.org/support/upgrade-php/">%s</a>',
                __('upgrading PHP to the latest version', 'neve')
            ),
            '7.0'
        )
    );
}
/**
 * A list of files to check for existance before bootstraping.
 *
 * @var array Files to check for existance.
 */

$_files_to_check = defined('NEVE_IGNORE_SOURCE_CHECK') ? [] : [
    NEVE_MAIN_DIR . 'vendor/autoload.php',
    NEVE_MAIN_DIR . 'style-main-new.css',
    NEVE_MAIN_DIR . 'assets/js/build/modern/frontend.js',
    NEVE_MAIN_DIR . 'assets/apps/dashboard/build/dashboard.js',
    NEVE_MAIN_DIR . 'assets/apps/customizer-controls/build/controls.js',
];
foreach ($_files_to_check as $_file_to_check) {
    if (!is_file($_file_to_check)) {
        $_neve_bootstrap_errors->add(
            'build_missing',
            sprintf(
                /* translators: %s: commands to run the theme */
                __('You appear to be running the Neve theme from source code. Please finish installation by running %s.', 'neve'), // phpcs:ignore WordPress.Security.EscapeOutput
                '<code>composer install --no-dev &amp;&amp; yarn install --frozen-lockfile &amp;&amp; yarn run build</code>'
            )
        );
        break;
    }
}
/**
 * Adds notice bootstraping errors.
 *
 * @internal
 * @global WP_Error $_neve_bootstrap_errors
 */
function _neve_bootstrap_errors()
{
    global $_neve_bootstrap_errors;
    printf('<div class="notice notice-error"><p>%1$s</p></div>', $_neve_bootstrap_errors->get_error_message()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ($_neve_bootstrap_errors->has_errors()) {
    /**
     * Add notice for PHP upgrade.
     */
    add_filter('template_include', '__return_null', 99);
    switch_theme(WP_DEFAULT_THEME);
    unset($_GET['activated']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    add_action('admin_notices', '_neve_bootstrap_errors');

    return;
}

/**
 * Themeisle SDK filter.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_filter_sdk($products)
{
    $products[] = get_template_directory() . '/style.css';

    return $products;
}

add_filter('themeisle_sdk_products', 'neve_filter_sdk');

require_once 'globals/migrations.php';
require_once 'globals/utilities.php';
require_once 'globals/hooks.php';
require_once 'globals/sanitize-functions.php';
require_once get_template_directory() . '/start.php';

/**
 * If the new widget editor is available,
 * we re-assign the widgets to hfg_footer
 */
if (neve_is_new_widget_editor()) {
    /**
     * Re-assign the widgets to hfg_footer
     *
     * @param array  $section_args The section arguments.
     * @param string $section_id The section ID.
     * @param string $sidebar_id The sidebar ID.
     *
     * @return mixed
     */
    function neve_customizer_custom_widget_areas($section_args, $section_id, $sidebar_id)
    {
        if (strpos($section_id, 'widgets-footer')) {
            $section_args['panel'] = 'hfg_footer';
        }
        return $section_args;
    }
    add_filter('customizer_widgets_section_args', 'neve_customizer_custom_widget_areas', 10, 3);
}

require_once get_template_directory() . '/header-footer-grid/loader.php';

/*NUESTRO CODIGO DE functions.php*/

function rdlx_add_global_variables_db()
{
    global $db_server_name;
    $db_server_name = "localhost";

    global $db_user_name;
    $db_user_name = "root";

    global $db_password;
    $db_password = "root";

    global $db_data_base_name;
    $db_data_base_name = "wp_staging";
}

function rdlx_add_rifas()
{
    $rifas = [];
    $prices =
        [
            100,
            100,
            100,
            100,
            100,
            100
        ];

    //RIFA 1
    //Esta es la configuracion de los forms, cantidad de boletos, tipo form, precio
    $input_forms = array(
        new Form_Config(1, 1, $prices[0]),
        new Form_Config(2, 1, $prices[1]),
        new Form_Config(3, 1, $prices[2]),
        new Form_Config(4, 1, $prices[3]),
        new Form_Config(5, 1, $prices[4]),
        new Form_Config(10, 2, $prices[5])
    );
    //Estas son las cantidades permitidas para seleccionar de la cuadricula
    $grid_cant_seleccion = array(
        1,
        2,
        10
    );
    //Esta es la cantidad de boletos regalados que se dan respecto a la cantidad elegida
    //En la rifa tipo 1 no aplica
    $randoms_por_num = array();
    //Esta es la cantidad que se valida en el end purchase, son los boletos totales (elegidos y regalados)
    $grupos_boleto = array(
        new Grupo_Boleto(1, $prices[0]),
        new Grupo_Boleto(2, $prices[1]),
        new Grupo_Boleto(3, $prices[2]),
        new Grupo_Boleto(4, $prices[3]),
        new Grupo_Boleto(5, $prices[4]),
        new Grupo_Boleto(10, $prices[5])
    );

    $rifa = new Rifa_Tipo_1(1, "1", true, 10001);
    $rifa->set_status_img_url("https://i.ibb.co/MZ0rZ9r/image0.jpg");
    $rifa->set_url_img_payment("https://via.placeholder.com/1000x1600");
    $rifa->set_grupos_boleto($grupos_boleto);
    $rifa->set_form_configs($input_forms);
    $rifa->set_grid_cant_seleccion($grid_cant_seleccion);
    $rifa->set_randoms_por_numero($randoms_por_num);
    $rifas[] = $rifa;

    //RIFA 2
    //Esta es la configuracion de los forms, cantidad de boletos, tipo form, precio
    $input_forms = array(
        new Form_Config(1, 1, $prices[0]),
        new Form_Config(2, 1, $prices[1]),
        new Form_Config(3, 1, $prices[2]),
        new Form_Config(4, 1, $prices[3]),
        new Form_Config(5, 1, $prices[4]),
        new Form_Config(10, 2, $prices[5])
    );
    //Estas son las cantidades permitidas para seleccionar de la cuadricula
    $grid_cant_seleccion = array(
        1,
        2,
        10
    );
    //Esta es la cantidad de boletos regalados que se dan respecto a la cantidad elegida
    //primero escogidos, despues regalados
    $randoms_por_num = array(
        new Random_por_Numero(1, 2),
        new Random_por_Numero(2, 3),
        new Random_por_Numero(10, 10)
    );
    //Esta es la cantidad que se valida en el end purchase, son los boletos totales (elegidos y regalados)
    $grupos_boleto = array(
        new Grupo_Boleto(1, $prices[0]),
        new Grupo_Boleto(2, $prices[1]),
        new Grupo_Boleto(3, $prices[2]),
        new Grupo_Boleto(4, $prices[3]),
        new Grupo_Boleto(5, $prices[4]),
        new Grupo_Boleto(10, $prices[5])
    );

    $rifa = new Rifa_Tipo_2(2, "2", true, 5000);
    $rifa->set_status_img_url("https://i.ibb.co/MZ0rZ9r/image0.jpg");
    $rifa->set_url_img_payment("https://via.placeholder.com/1000x1600");
    $rifa->set_grupos_boleto($grupos_boleto);
    $rifa->set_form_configs($input_forms);
    $rifa->set_grid_cant_seleccion($grid_cant_seleccion);
    $rifa->set_randoms_por_numero($randoms_por_num);
    $rifas[] = $rifa;

    //RIFA 3
    //Esta es la configuracion de los forms, cantidad de boletos, tipo form, precio
    $input_forms = array(
        new Form_Config(1, 1, $prices[0]),
        new Form_Config(2, 1, $prices[1]),
        new Form_Config(3, 1, $prices[2]),
        new Form_Config(4, 1, $prices[3]),
        new Form_Config(5, 1, $prices[4]),
        new Form_Config(10, 2, $prices[5])
    );
    //Estas son las cantidades permitidas para seleccionar de la cuadricula
    $grid_cant_seleccion = array(
        1,
        2,
        10
    );
    //Esta es la cantidad de boletos regalados que se dan respecto a la cantidad elegida
    //No aplica en rifas tipo 3
    $randoms_por_num = array();
    //Esta es la cantidad que se valida en el end purchase, son los boletos totales (elegidos y regalados)
    $grupos_boleto = array(
        new Grupo_Boleto(1, $prices[0]),
        new Grupo_Boleto(2, $prices[1]),
        new Grupo_Boleto(3, $prices[2]),
        new Grupo_Boleto(4, $prices[3]),
        new Grupo_Boleto(5, $prices[4]),
        new Grupo_Boleto(10, $prices[5])
    );

    $rifa = new Rifa_Tipo_3(3, "3", true, 5000);
    $rifa->set_status_img_url("https://i.ibb.co/MZ0rZ9r/image0.jpg");
    $rifa->set_url_img_payment("https://via.placeholder.com/1000x1600");
    $rifa->set_grupos_boleto($grupos_boleto);
    $rifa->set_form_configs($input_forms);
    $rifa->set_grid_cant_seleccion($grid_cant_seleccion);
    $rifa->set_randoms_por_numero($randoms_por_num);
    $rifa->set_cant_boletos_regalo_tipo_3(1);
    $rifas[] = $rifa;

    //NO MOVER
    global $rifas_data;
    $rifas_data = new Rifas($rifas);
}

function rdlx_add_global_variables()
{
    rdlx_add_global_variables_db();

    /*VARIABLES*/
    global $show_rifa_select_menu;
    $show_rifa_select_menu = false;
    global $show_phone_on_admin;
    $show_phone_on_admin = true;
    global $hide_numbers;
    $hide_numbers = "oculto";

    /*PAGINAS*/
    global $page_url;
    $page_url = get_home_url();

    global $page_number_select;
    $page_number_select = $page_url . "/number-select";
    global $page_user_info_form;
    $page_user_info_form = $page_url . "/user-form";
    global $page_end_purchase;
    $page_end_purchase = $page_url . "/end-purchase";
    global $page_client_ticket_status;
    $page_client_ticket_status = $page_url . "/client-ticket-status";
    global $page_notice;
    $page_notice = $page_url . "/notice";
    global $page_admin;
    $page_admin = $page_url . "/rifas-admin";
    global $page_admin_dev;
    $page_admin_dev = $page_url . "/rifas-admin-dev";
    global $proceso_pago;
    $proceso_pago = $page_url . "/proceso-pago";

    /*SERVICIOS*/
    global $theme_url_no_dash;
    $theme_url_no_dash = "wp-content/themes/neve";
    global $theme_url_dash;
    $theme_url_dash = "/" . $theme_url_no_dash;

    global $service_are_tickets_available;
    $service_are_tickets_available = $page_url . $theme_url_dash . "/services/check_number_status.php";
    global $service_random_numbers;
    $service_random_numbers = $page_url . $theme_url_dash . "/services/random_numbers.php";
    global $service_available_tickets_range;
    $service_available_tickets_range = $page_url . $theme_url_dash . "/services/tickets_by_range.php";
    global $service_create_payment;
    $service_create_payment = $page_url . $theme_url_dash . "/services/create_payment.php";

    /*FOLDERS*/
    global $img_folder;
    $img_folder = get_template_directory_uri() . "/other/img";
    global $styles_folder;
    $styles_folder = get_template_directory_uri() . "/other/styles";
    global $page_reports;
    $page_reports = $page_url . $theme_url_dash . "/templates/pdfReportes.php";
    global $reportes_folder;
    $reportes_folder = get_template_directory() . "/reportes";
    global $sections_folder;
    $sections_folder = get_template_directory() . "/sections";

    //CSS Y JS
    global $folder_js;
    $folder_js = $page_url . $theme_url_dash . "/other/js";
    global $folder_css;
    $folder_css = $page_url . $theme_url_dash . "/other/styles";

    //CLASES Y OTROS
    global $url_rifa_class;
    $url_rifa_class = get_template_directory() . "/other";
    require_once $url_rifa_class . "/clases.php";
    require_once $url_rifa_class . "/clase_rifa_tipo_1.php";
    require_once $url_rifa_class . "/clase_rifa_tipo_2.php";
    require_once $url_rifa_class . "/clase_rifa_tipo_3.php";
    require_once $url_rifa_class . "/database_actions.php";

    //STRIPE
    require_once(get_template_directory() . '/stripe-php/init.php');
    //require_once('vendor/autoload.php');

    global $url_img_logo_reports; //TIENE QUE SER PNG
    $url_img_logo_reports = "https://cdn.discordapp.com/attachments/690798146464251966/943597499652919376/Sorteos_el_Chato_Logo.png";
    rdlx_add_rifas();
}

add_action('after_setup_theme', 'rdlx_add_global_variables');

/*FIN*/
