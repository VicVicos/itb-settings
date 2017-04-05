<?php
/*
Plugin Name: Itb Custom Settings from WordPress
Plugin URI: http://itb-company.com
Description: Плагин с настройками для создаваемых тем.
Author: Vicos
Author URI: http://vocis.ru
*/

// Активация и деактивация плагина
include_once dirname( __FILE__ ) . '/ItbSettingsAction.php';
include_once dirname( __FILE__ ) . '/helper.php';

register_activation_hook(__FILE__, array( 'ItbSettingsAction', 'itbSettingsActivate' ));
register_deactivation_hook(__FILE__, array('ItbSettingsAction', 'itbSettingsDeactivate'));

add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
	add_options_page( 'Настройки Темы', 'Настройки Темы', 'manage_options', 'itb_settings', 'itb_options_page_output' );
}

function get_option_theme ($option) {
    $theme_option = get_option('option_theme');
    return html_entity_decode($theme_option[$option]);
}

function itb_options_page_output(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'option_group' );     // скрытые защитные поля
				do_settings_sections( 'itb_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'option_group', 'option_theme', 'sanitize_callback' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'section_id', 'Основные настройки', '', 'itb_page' );

	// параметры: $id, $title, $callback, $page, $section, $args
    //add_settings_field('phones_rez', 'Телефоны', 'fill_primer_field2', 'itb_page', 'section_id' );

	add_settings_field('address', 'Адрес', 'fill_address', 'itb_page', 'section_id' );
    add_settings_field('phones', 'Телефоны', 'fill_phones', 'itb_page', 'section_id' );
    add_settings_field('phone', 'Телефон для формы', 'fill_phone', 'itb_page', 'section_id' );
    add_settings_field('timejob', 'Время работы', 'fill_timejob', 'itb_page', 'section_id' );

    add_settings_field('name', 'Настройки товара', 'fill_name', 'itb_page', 'section_id' );
    add_settings_field('price', 'Цена товара', 'fill_price', 'itb_page', 'section_id' );
    add_settings_field('readmore_title', 'Подробнее о товаре', 'fill_readmore_title', 'itb_page', 'section_id' );
}

function fill_address () {
	$val = get_option('option_theme');
	$val = $val['address'];

	?>
	<input type="text" name="option_theme[address]" value="<?= html_entity_decode($val) ?>" />
	<?php
}

function fill_phone () {
	$val = get_option('option_theme');
	$val = $val['phone'];

	?>
	<input type="text" name="option_theme[phone]" value="<?= html_entity_decode($val) ?>" />
	<?php
}

function fill_phones () {
	$val = get_option('option_theme');
    $val = $val['phones'];
	?>
	<textarea name="option_theme[phones]" style="width:550px;height:105px;"><?= html_entity_decode($val); ?></textarea>
	<?php
}

function fill_timejob () {
    $val = get_option('option_theme');
    $val = $val['timejob'];
	?>
	<textarea name="option_theme[timejob]" style="width:550px;height:105px;"><?= html_entity_decode($val); ?></textarea>
	<?php
}

function fill_name () {
	$val = get_option('option_theme');
	$val = $val['name'];

	?>
    <hr>
    <h2>Заголовок Товара</h2>
	<input type="text" name="option_theme[name]" value="<?= html_entity_decode($val) ?>" />
	<?php
}

function fill_price () {
	$val = get_option('option_theme');
	$val = $val['price'];

	?>
	<input type="text" name="option_theme[price]" value="<?= html_entity_decode($val) ?>" />
	<?php
}

function fill_readmore_title () {
	$val = get_option('option_theme');
    $val = $val['readmore_title'];
	?>
	<textarea name="option_theme[readmore_title]" style="width:550px;height:105px;"><?= html_entity_decode($val); ?></textarea>
	<?php
}

## Очистка данных
// TODO: Переделать в switch
function sanitize_callback( $options ){
	// очищаем
	foreach( $options as $name => & $val ){
        $val = htmlentities($val);
	}

	//die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

	return $options;
}
