<?php
/*
Plugin name: Matraca Masonry
Plugin uri: 
Description: Plugin para exposição de Masonry
Version: 1.0
Author: Matraca Suporte
Author uri: 
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' )) {
	exit;
}

define( 'MATRACA_PLUGIN_URL', plugin_dir_url( __FILE__ ));


add_action( 'wp_enqueue_scripts', 'matraca_menu_online_load' );
function matraca_menu_online_load() {
	//CSS
    wp_enqueue_style( 'matraca', MATRACA_PLUGIN_URL . '/styles/matraca-main.min.css');	
	
	//JAVASCRIPT
    wp_enqueue_script('jquery');
	wp_enqueue_script( 'matraca.directional-hover', MATRACA_PLUGIN_URL . 'scripts/jquery.directional-hover.js', array(), false, false);	
	wp_enqueue_script( 'matraca-imagesloaded', MATRACA_PLUGIN_URL . 'scripts/imagesloaded.pkgd.min.js', array(), false, false);	
	wp_enqueue_script( 'matraca-isotope', MATRACA_PLUGIN_URL . 'scripts/isotope.pkgd.min.js', array(), false, false);	
	wp_enqueue_script( 'matraca-isotope', MATRACA_PLUGIN_URL . 'scripts/main.js', array(), false, false);	
}

add_action( 'init', 'matraca_masonry_custom_post' );
function matraca_masonry_custom_post() {

    $labels = array(
        'name'               => __( 'Masonry' ),
        'singular_name'      => __( 'Masonry' ),
        'add_new'            => __( 'Adicionar novo Masonry' ),
        'add_new_item'       => __( 'Adicionar novo Masonry' ),
        'edit_item'          => __( 'Editar Masonry' ),
        'new_item'           => __( 'Novo Masonry' ),
        'all_items'          => __( 'Todos Masonries' ),
        'view_item'          => __( 'Visualizar Masonries' ),
        'search_items'       => __( 'Localizar Masonries' )
    );

    $args = array(
        'labels'            => $labels,
        'description'       => 'Masonry online',
        'public'            => true,
        'menu_position'     => 6,
        'supports'          => array( 'title', 'thumbnail', 'custom-fields' ),
        'has_archive'       => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'has_archive'       => true,
        'menu_icon'         => 'dashicons-format-gallery',
    );

    register_post_type( 'masonry', $args);    
}  

add_shortcode('MATRACA_MASONRY', 'matraca_masonry_function');
function matraca_masonry_function($attr) {

    $html = '';

    $html .= '<div class="matraca-container">';
    $html .= '<div class="grid">';
    $html .= '<div class="grid-sizer"></div>';

    $query = array( 
        'post_type' => 'masonry',
        'orderby' => 'meta_value',
        'order'=> 'ASC',
        'posts_per_page' => -1,          
    );    

    $loop = new WP_Query($query);
    
    if($loop->have_posts()) {  
        while($loop->have_posts()) {   
            $loop->the_post();

            $title = get_the_title(get_the_ID());
            $imagem_thumbnail = get_field('imagem_thumbnail'); 
            $imagem_view = get_field('imagem_view');

            $html .= '<div class="grid-item">';
            $html .= '<div class="item-overlay-container">';
            $html .= '<div class="item-overlay-content">';
            $html .= '<div class="item-overlay">';
            $html .= '<strong>' . $title . '</strong>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<img src="' . $imagem_thumbnail . '" alt="' . $title . '" />';
            $html .= '<input class="input-view" type="hidden" name="hidden" value="'  . $imagem_view . '" />';
            $html .= '</div>';
        }
    }

    $html .= '</div>';
    $html .= '</div>';

    $html .= '<div class="matraca-overlay">';
    $html .= '<div class="matraca-overlay-content">';
    $html .= '<div class="header">';
    $html .= '<div>';
    $html .= '<img class="logo" src="' . MATRACA_PLUGIN_URL . '/images/unnamed.jpg" alt="logo" />';
    $html .= '<strong>Matraca Comunicação Criativa</strong>';
    $html .= '</div>';
    $html .= '<img id="iconClose" src="' . MATRACA_PLUGIN_URL . '/images/close.png" alt="close" />';
    $html .= '</div>';
    $html .= '<div class="body">';
    $html .= '<img class="image-view" src="images/1.jpg" />';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * SCRIPT RESTRICT LOGOUT
 */
add_action( 'wp_footer', 'matraca_masonry_script');
function matraca_masonry_script() { ?>
<script type="text/javascript">
(function($){  
    
    var itemOverlay = $('.matraca-container .grid-item');
    var iconClose = $('#iconClose');
    var matracaOverlay = $('.matraca-overlay');

    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        percentPosition: true,
        masonry: {
        columnWidth: '.grid-sizer',
        },
    });
  // layout Isotope after each image loads
    $grid.imagesLoaded().progress(function () {
        $grid.isotope('layout');
    });

    $('.item-overlay-content').directionalHover({
        overlay: 'item-overlay',
        easing: 'swing',
        speed: 400,
    });

    itemOverlay.click(function () {
      var _this = $(this);
      matracaOverlay.toggleClass('show');
      matracaOverlay.find('.image-view').attr('src', _this.find('.input-view').val());
      $('body').css('overflow', 'hidden');
    });

    iconClose.click(function () {
      matracaOverlay.toggleClass('show');
      $('body').css('overflow', 'auto');
    });

})(jQuery);
</script>    
<?php }

