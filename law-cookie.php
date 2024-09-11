<?php
/*
* Plugin Name: Law cookie
* Description: Displays a cookie notice with less than 1 KB and customizable text.
* GitHub Plugin URI: sveevs/law-cookie
* Version:     1.1
* Author:      -sv-
* Author URI:  https://sv-pt.ru/?p=1953
* Text Domain: law-cookie
* License: GPL v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   

$plugin_version = '1.1';

add_action('wp_footer', 'lawcookie_add');
add_action('admin_menu', 'lawcookie_lite_settings_page');
add_action('admin_init', 'lawcookie_settings_init');
add_filter('plugin_row_meta','lawcookie_setting_links' , 1, 2 );

function lawcookie_setting_links($links, $file) {
			if (false === strpos($file, basename(__FILE__)))
				return $links;
			$links[] = '<a href="' . add_query_arg(array('page' => 'Lawcookie'), admin_url('options-general.php')) . '">' . __('&#128736; Settings') . '</a>';
			$links[] = '<a href="https://sv-pt.ru/?p=1953" target="_blank">' . __('&#128293; Перейти к документации') . '</a>';
			$links[] = '<a target="_blank" href="https://wordpress.org/support/plugin/law-cookie/reviews/">' . __('Проголосовать ★★★★★','VideoADSHtml5') . '</a>';
				return $links;
		}

function lawcookie_add()
{	
		$plugin_url = plugins_url('', __FILE__);
		wp_enqueue_style( 'lawcookie_styles', plugins_url( 'css/cookie.css', __FILE__ ) );
		wp_enqueue_script( 'lawcookie_script_cookiemin', plugins_url( 'js/cookie.min.js', __FILE__ ), array(), time(), true );
		wp_enqueue_script( 'lawcookie_script_script', plugins_url( 'js/script.js', __FILE__ ), array(), time(), true );
		
    $url = get_privacy_policy_url();
    $link = '';
    if (get_privacy_policy_url() != '') {
        $link = '<a style="font-size: small; text-align: right" rel="nofollow" href="' . $url . '">' . esc_html__('More information', 'law-cookie') . '</a>';
    }

    $lawcookie_text = get_option('setting_textvivod');
	//$lawcookie_text = get_option('law-cookie');
	
    //if (empty($lawcookie_text)) {
     //   $lawcookie_text = esc_html__('Этот веб-сайт использует файлы cookie. Продолжая вы соглашаетесь на использование файлов cookie.', 'law-cookie');
    //}
	
	//расположение всплывающего сообщения
	$setting_poz = get_option('setting_poz');
	$setting_color = get_option('setting_color');
	$setting_text_color = get_option('setting_text_color');
				//echo $setting_poz;
				

	if ($setting_poz == 1 ) { printf(wp_kses_post('<div class="cookie-block-bot-lef"><div style="background-color:'.$setting_color.' !important;color:'.$setting_text_color.';" class="cookie-block">'.$lawcookie_text.'  <button class="ok">Да</button></div></div>', 'law-cookie')); }
	if ($setting_poz == 2 ) { printf(wp_kses_post('<div class="cookie-block-bot-rig"><div style="background-color:'.$setting_color.' !important;color:'.$setting_text_color.';" class="cookie-block">'.$lawcookie_text.'  <button class="ok">Да</button></div></div>', 'law-cookie')); }
	if ($setting_poz == 3 ) { printf(wp_kses_post('<div class="cookie-block-top-lef"><div style="background-color:'.$setting_color.' !important;color:'.$setting_text_color.';" class="cookie-block">'.$lawcookie_text.'  <button class="ok">Да</button></div></div>', 'law-cookie')); }
	if ($setting_poz == 4 ) { printf(wp_kses_post('<div class="cookie-block-top-rig"><div style="background-color:'.$setting_color.' !important;color:'.$setting_text_color.';" class="cookie-block">'.$lawcookie_text.'  <button class="ok">Да</button></div></div>', 'law-cookie')); }
	

}

function lawcookie_lite_settings_page()
{
    add_options_page('Law cookie Settings', 'Law cookie', 'manage_options', 'Lawcookie', 'lawcookie_settings_callback');
}


function lawcookie_settings_callback()
{
	$url = "https://sv-pt.ru/?p=1953";
    $link_text = sprintf(wp_kses(__('Для получения подробной документации посетите домашнюю страницу плагина <a target="_blank" href="%s">здесь</a>.', 'videhtml5ads'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));       
    ?>
	<div class="wrap">
	       
		<h1>Law cookie Settings <?php global $plugin_version; printf(esc_html__($plugin_version,'law-cookie'));?></h1>
		 <?php printf(wp_kses_post($link_text,'law-cookie')); ?>
		<form method="post" action="options.php">
			<?php
                settings_fields('lawcookie_lite_settings_group');
				do_settings_sections('lawcookie_lite_settings');
				submit_button();			
    ?>
		</form>
	</div>
	<?php	
	
}



//Загрузка в Settings
function lawcookie_settings_init()
{
    register_setting('lawcookie_lite_settings_group', 'setting_textvivod');
	register_setting('lawcookie_lite_settings_group', 'setting_poz');
	register_setting('lawcookie_lite_settings_group', 'setting_color');
	register_setting('lawcookie_lite_settings_group', 'setting_text_color');

    add_settings_section('cookie_notice_lite_settings_section', 'Настройки', null, 'lawcookie_lite_settings');

    add_settings_field('law-cookie', 'Текст на сообщении', 'lawcookie_text_callback', 'lawcookie_lite_settings', 'cookie_notice_lite_settings_section');
	
	add_settings_field('lawcookie_lite_pos', 'Расположение сообщения', 'lawcookie_pos_callback', 'lawcookie_lite_settings', 'cookie_notice_lite_settings_section');
	
	add_settings_field('lawcookie_lite_colorbottom', 'Цвет фона сообщения', 'lawcookie_colorbottom_callback', 'lawcookie_lite_settings', 'cookie_notice_lite_settings_section');
	
	add_settings_field('lawcookie_lite_text_colorbottom', 'Цвет текста сообщения', 'lawcookie_text_colorbottom_callback', 'lawcookie_lite_settings', 'cookie_notice_lite_settings_section');
}

//Вывод параметров в настройки Settings
function lawcookie_text_callback()
{

	//$lawcookie_setting_text = get_option('setting_textvivod');
	$lawcookie_text = get_option('setting_textvivod');

    if (empty($lawcookie_text)) {
        $lawcookie_text = esc_html__('Этот веб-сайт использует файлы cookie. Продолжая здесь, вы соглашаетесь на использование файлов cookie.', 'law-cookie');

    }
    ?>
<textarea id="setting_textvivod" name="setting_textvivod" rows="5" cols="50"><?php printf(esc_html(esc_textarea($lawcookie_text)));?></textarea>


<?php
}

function lawcookie_pos_callback()
{
	?>
	<p><select name="setting_poz">
	

	 <option value="1" <?php printf(esc_html__('Нижний левый угол','law-cookie'),esc_html(selected(1, get_option('setting_poz')))); ?>>Нижний левый угол</option>
	 <option value="2" <?php printf(esc_html__('Нижний правый угол','law-cookie'),esc_html(selected(2, get_option('setting_poz')))); ?>>Нижний правый угол</option>
	 <option value="3" <?php printf(esc_html__('Вверхний левый угол','law-cookie'),esc_html(selected(3, get_option('setting_poz')))); ?>>Вверхний левый угол</option>
	 <option value="4" <?php printf(esc_html__('Вверхний правый угол','law-cookie'),esc_html(selected(4, get_option('setting_poz')))); ?>>Вверхний правый угол</option>

   </select>
   <p><?php // _e('По умолчанию опция Оставить', 'lawcookie')?>
   
<?php
				$setting_poz = get_option('setting_poz');
				//echo $setting_poz;
}


function lawcookie_colorbottom_callback()
{
	?>
	<p>
	<input type="color" id="setting_color" name="setting_color" value="<?php printf(wp_kses_post(get_option('setting_color'))); ?>">
   <p><?php // _e('По умолчанию опция Оставить', 'lawcookie')?>
   
<?php
				$setting_poz = get_option('setting_poz');
				//echo $setting_poz;
}

function lawcookie_text_colorbottom_callback()
{
	?>
	<p>
	<input type="color" id="setting_text_color" name="setting_text_color" value="<?php printf(wp_kses_post(get_option('setting_text_color'))); ?>">
   <p><?php // _e('По умолчанию опция Оставить', 'lawcookie')?>
   
<?php
				$setting_poz = get_option('setting_poz');
				//echo $setting_poz;
}

?>