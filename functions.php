<?php

/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */


/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain('buddyboss-theme', get_stylesheet_directory() . '/languages');

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action('after_setup_theme', 'buddyboss_theme_child_languages');

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style('buddyboss-child-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', '', '1.0.0');

  // Javascript
  wp_enqueue_script('buddyboss-child-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', '', '1.0.0');

  if(is_user_logged_in()) {
      wp_enqueue_style('login-css', get_stylesheet_directory_uri().'/assets/css/login.css','','1.0.0');
  } else {
      wp_enqueue_style('logout-css', get_stylesheet_directory_uri().'/assets/css/logout.css','','1.0.0');
  }
}
add_action('wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999);


/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

//send a welcome email when a user account is activated
add_action('bp_core_activated_user', 'bpdev_welcome_user_notification', 10, 3);

function bpdev_welcome_user_notification($user_id, $key = false, $user = false)
{

  if (is_multisite()) {
    return; // we don't need it for multisite
  }
  //send the welcome mail to user
  //welcome message

  $welcome_email = __('¡Bienvenido USER_DISPLAY_NAME!,
 
Se ha creado tu nueva cuenta.
 
Puedes hacer login con la siguiente información:
Nombre de usuario: USERNAME
LOGINLINK
 
¡Gracias!
 
--El equipo de SITE_NAME');

  //get user details
  $user = get_userdata($user_id);
  //get site name
  $site_name = get_bloginfo('name');
  //update the details in the welcome email
  $welcome_email = str_replace('USER_DISPLAY_NAME', $user->first_name, $welcome_email);
  $welcome_email = str_replace('SITE_NAME', $site_name, $welcome_email);
  $welcome_email = str_replace('USERNAME', $user->user_login, $welcome_email);
  $welcome_email = str_replace('LOGINLINK', wp_login_url(), $welcome_email);

  //from email
  $admin_email = get_site_option('admin_email');

  if (empty($admin_email)) {
    $admin_email = 'support@' . $_SERVER['SERVER_NAME'];
  }

  $from_name = $site_name . "<$admin_email>"; //from
  $message_headers =  array(
    'from'          => $from_name,
    'content-type'  => 'text/plain; charset=' . get_option('blog_charset')
  );

  //EMAIL SUBJECT
  $subject = sprintf(__('Welcome to   %1$s '), $site_name);
  //SEND THE EMAIL
  wp_mail($user->user_email, $subject, $welcome_email, $message_headers);

  return true;
}

// Email not sending
add_filter('bp_email_use_wp_mail', '__return_true');
// Set messages to HTML for BP sent emails.
add_filter('wp_mail_content_type', function ($default) {
  if (did_action('bp_send_email')) {
    return 'text/html';
  }
  return $default;
});
// Use HTML template
add_filter(
  'bp_email_get_content_plaintext',
  function ($content, $property, $transform, $bp_email) {
    if (!did_action('bp_send_email')) {
      return $content;
    }
    return $bp_email->get_template('add-content');
  },
  10,
  4
);

function admin_stylesheet()
{
  if (is_user_logged_in()) { ?>

    <style>
      .elementor-hidden-phone {

        display: none !important;

      }
    </style>
  <?php
  }
}

add_action('wp_enqueue_scripts', 'admin_stylesheet');

/* hide quantity */

# add code in functions.php 
function wdo_remove_quantity_column_from_cart($return, $product)
{
  if (is_cart()) return true;
}
add_filter('woocommerce_is_sold_individually', 'wdo_remove_quantity_column_from_cart', 10, 2);

/**
 * @snippet       WooCommerce Max 1 Product @ Cart
 */

add_filter('woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart', 9999, 2);

function bbloomer_only_one_in_cart($passed, $added_product_id)
{
  wc_empty_cart();
  return $passed;
}



/* añadir USD to $ */

function patricks_currency_symbol($currency_symbol, $currency)
{

  switch ($currency) {
    case 'USD':
      $currency_symbol = 'USD $';
      break;
  }

  return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'patricks_currency_symbol', 30, 2);


// Add Custom Credit Card Icons to WooCommerce Checkout Page 

add_filter('woocommerce_gateway_icon', 'njengah_custom_woocommerce_icons');

function njengah_custom_woocommerce_icons()
{

  $icon  = '<img src="https://ismart.worldvision.cr/wp-content/uploads/2021/05/major-credit-card-logos-reducido.png" alt="Pago seguro con tarjeta de crédito" />';

  return $icon;
}




/**
 * Auto Complete all WooCommerce orders.
 */
add_action('woocommerce_thankyou', 'custom_woocommerce_auto_complete_order');
function custom_woocommerce_auto_complete_order($order_id)
{
  if (!$order_id) {
    return;
  }

  $order = wc_get_order($order_id);
  $order->update_status('completed');
}

/* Limitar busqueda */

function SearchFilter($query)
{
  if ($query->is_search) {
    $query->set('post_type', array('courses'));
  }
  return $query;
}
add_filter('pre_get_posts', 'SearchFilter');

/*Añadir imágenes a categoría */
add_theme_support('category-thumbnails');



// Menu personalizado para moviles

add_shortcode('boton_menu_movil', function () {
  ?>
  <button class="btn_mobile_menu" onclick="activar_menu()"><i class="fas fa-bars"></i></button>
<?php
});

add_shortcode('menu_movil', function ($atts) {
  // valores, favor; NO cambiar valor de variable redir
  extract(shortcode_atts(array(
    'log' => '/wp-login.php',
    'reg' => '/suscribete',
    'redir' => '?redirect_to='
  ), $atts));

  if (is_user_logged_in()) {
    $result =
      '
    <form class="elementor-search-form" role="search" action="https://ismart360.com" method="get" data-hs-cf-bound="true">
      <div class="elementor-search-form__container">
        <input placeholder="Buscá contenidos" class="elementor-search-form__input" type="search" name="s" title="Buscar" value="">
        <button class="elementor-search-form__submit" type="submit" title="Buscar" aria-label="Buscar">
          <i aria-hidden="true" class="fas fa-search"></i><span class="elementor-screen-only">Buscar</span>
        </button>
      </div>
    </form>
    <ul class="mobile-menu">
    <!-- <li class="mobile-menu-item">
    <a>Actividades</a><button class="btn_mobile_sub_menu" onclick="activar_submenu()"><i class="fas fa-angle-down"></i></button>
      <ul class="mobile-menu sub-menu">
        <li class="mobile-sub-menu-item">
          <a href="https://ismart360.com/kids/">iSmart360 Kids</a>
        </li>
        <li class="mobile-sub-menu-item">
          <a href="https://ismart360.com/teens/">iSmart360 Teens</a>
        </li> 
      </ul>
      </li> -->
      <li class="mobile-menu-item">
        <a href="https://ismart360.com/nosotros/">Nosotros</a>
      </li>
      <li class="mobile-menu-item">
        <a href="https://ismart360.com/planes/">Planes</a>
      </li>
      <li class="mobile-menu-item">
        <a href="https://ismart360.com/kids/">Kids</a>
      </li>
      <li class="mobile-menu-item">
        <a href="https://ismart360.com/teens/">Teens</a>
      </li>
      <li class="mobile-menu-item">
        <a href="https://blog.ismart360.com">Blog</a>
      </li>
      <li class="menu-item btn-logout">
        <a href="' . wp_logout_url(get_permalink()) . '"><span>Cerrar sesión</span><i class="_mi _after buddyboss_legacy bb-icon-log-out"></i></a>
      </li>
    </ul>
            ';
  } else {
    $result = '
    <form class="elementor-search-form" role="search" action="https://ismart360.com" method="get" data-hs-cf-bound="true">
      <div class="elementor-search-form__container">
        <input placeholder="Buscá contenidos" class="elementor-search-form__input" type="search" name="s" title="Buscar" value="">
        <button class="elementor-search-form__submit" type="submit" title="Buscar" aria-label="Buscar">
          <i aria-hidden="true" class="fas fa-search"></i><span class="elementor-screen-only">Buscar</span>
        </button>
      </div>
    </form>
    <ul class="mobile-menu">
        <!-- <li class="mobile-menu-item">
        <a>Actividades</a><button class="btn_mobile_sub_menu" onclick="activar_submenu()"><i class="fas fa-angle-down"></i></button>
          <ul class="mobile-menu sub-menu">
            <li class="mobile-sub-menu-item">
              <a href="https://ismart360.com/kids/">iSmart360 Kids</a>
            </li>
            <li class="mobile-sub-menu-item">
              <a href="https://ismart360.com/teens/">iSmart360 Teens</a>
            </li> 
          </ul>
        </li> -->
        <li class="mobile-menu-item">
          <a href="https://ismart360.com/nosotros/">Nosotros</a>
        </li>
        <li class="mobile-menu-item">
          <a href="https://ismart360.com/planes/">Planes</a>
        </li>
        <li class="mobile-menu-item">
          <a href="https://ismart360.com/kids/">Kids</a>
        </li>
        <li class="mobile-menu-item">
          <a href="https://ismart360.com/teens/">Teens</a>
        </li>
        <li class="mobile-menu-item">
          <a href="https://blog.ismart360.com">Blog</a>
        </li>
      <li class="menu-item btn-login">
        <a href="https://ismart360.com/login">iniciar sesión</a>
      </li>
    </ul>
    <a class="menu-item btn-register" href="https://ismart360.com/suscribete/">Suscríbete <i class="fas fa-arrow-right"></i></a>';
  }

  return $result;
});

function script_menu_movil()
{
?>
  <script>
    function activar_menu() {
      var mobil_btn = document.querySelector("div section.elementor-section.elementor-top-section.elementor-element.elementor-element-55c21955.elementor-section-height-min-height.header-page.elementor-section-boxed.elementor-section-height-default.elementor-section-items-middle");
      mobil_btn.classList.toggle("active");
      var mobil_btn = document.querySelector("ul.mobile-menu ul.sub-menu");
      var mobil_bton = document.querySelector("div section.elementor-section.elementor-top-section.elementor-element.elementor-element-55c21955.elementor-section-height-min-height.header-page.elementor-section-boxed.elementor-section-height-default.elementor-section-items-middle.active");
      mobil_btn.classList.remove("touched");
      mobil_bton.classList.remove("sub_menu");
    }

    function activar_submenu() {
      var mobil_btn = document.querySelector("ul.mobile-menu ul.sub-menu");
      mobil_btn.classList.toggle("touched");
      var mobil_btn = document.querySelector("div section.elementor-section.elementor-top-section.elementor-element.elementor-element-55c21955.elementor-section-height-min-height.header-page.elementor-section-boxed.elementor-section-height-default.elementor-section-items-middle");
      mobil_btn.classList.toggle("sub_menu");
    }
  </script>
  <?php

}

add_action('wp_footer', 'script_menu_movil');




// end Menu personalizado para moviles
// 
// 

// MIGUEL
add_action('wp_footer', 'prefill_checkout_fields');
function prefill_checkout_fields()
{
  if (is_checkout()) { // Verificar si el usuario está en la página de checkout
  ?>
    <script>
      // Obtener datos del LocalStorage
      const cartItem = JSON.parse(localStorage.getItem('cartItem'));

      // Verificar si hay datos en el LocalStorage
      if (cartItem) {
        const form = document.querySelector('form.checkout');

        // Iterar sobre los campos del formulario
        Array.from(form.elements).forEach(function(field) {
          const fieldName = field.name;

          // Verificar si el campo existe en los datos del LocalStorage
          if (cartItem.hasOwnProperty(fieldName)) {

            // Si el campo es un select
            if (field.tagName === 'SELECT') {
              // Iterar sobre las opciones del select
              Array.from(field.options).forEach(function(option) {
                if (option.text === cartItem[fieldName]) {
                  console.log(`${option.text} | ${cartItem[fieldName]}`)
                  option.selected = 'selected';
                }
              });
            } else {
              field.value = cartItem[fieldName];
            }
          }
        });
      }

      // Limpiar los datos del LocalStorage después de usarlos
      localStorage.removeItem('cartItem');
    </script>
<?php
  }
}




























// Añadir la opción de cancel override a la tabla de pagos de MemberPress y moverla a la última posición
function add_mepr_cancel_override_option_header($headers) {
  ?><th><?php _ex('Cancel Override', 'ui', 'memberpress'); ?></th>
  <?php
}
add_action('mepr_account_payments_table_header', 'add_mepr_cancel_override_option_header');



function get_user_subscription_id( $user_id ) {
  $membership = new MeprUser($user_id);
  $subscriptions = $membership->active_product_subscriptions();

  if ( !empty($subscriptions) ) {
    return $subscriptions[0]->subscription_id;
  }
  return false;
}
function get_order_id_from_user_and_product($user_id, $product_id = false) {
  // Obtener todas las órdenes del usuario
  $orders = wc_get_orders(array(
      'customer' => $user_id,
      'status' => array('completed', 'processing')
  ));
  $resp = [];
  // Buscar en las órdenes el producto en particular
  foreach ($orders as $order) {
      foreach ($order->get_items() as $item) {
          $flag = isset($product_id) && ($item->get_product_id() == intval($product_id));
          if ($flag) {
              return $order->get_id(); // Retorna el ID de la orden que contiene el producto
          } else {
            array_push($resp, $order->get_id());
          }
      }
  }
  if(!isset($resp)) return false;
  return $resp; 
}

function mepr_account_payments_table_row($txn) {
  $user_id = $txn->user_id;
  $product_id = intval($txn->product_id);
  $order_id = get_order_id_from_user_and_product($user_id);
  $order = wc_get_order($order_id[0]);
  
  if (!wcs_user_has_subscription(get_current_user_id(), '', 'active')) {
    ?><th id="mp_table_cancel_element"><?php _ex('Inactivo', 'ui', 'memberpress'); ?></th><?php
  } else{
    ?><th id="mp_table_cancel_element"><a href="#!"><?php _ex('Cancel', 'ui', 'memberpress'); ?></a></th><?php
  }

  wp_enqueue_script('cancel_subscription_wpapi',get_stylesheet_directory_uri() . '/assets/js/mp_table_cancel_function.js', array('jquery'), '1.0', true);
  wp_localize_script('cancel_subscription_wpapi', 'inbound_localize_script', array(
    "rest_url" => rest_url('inboundlabs').'/cancel_subscription/',
    "actual_url" => wc_get_account_endpoint_url('subscriptions'),
    "current_user_id" => get_current_user_id(),
    "order_user_id" => $order->get_user_id(),
    "order_id" => $order_id[0],
    "is_order_active" => wcs_user_has_subscription($order->get_user_id(), '', 'active')
  ));
}
add_action('mepr_account_payments_table_row', 'mepr_account_payments_table_row');

function cancel_subscription_wpapi()
{
  $namespace = 'inboundlabs';
  $route = 'cancel_subscription';
  $args = array(
    'methods' => 'POST',
    'callback' => 'cancel_subscription_wpapi_callback'
  );
  register_rest_route($namespace, $route, $args);
}
add_action('rest_api_init', 'cancel_subscription_wpapi');

function cancel_subscription_wpapi_callback($res)
{
  $order_id = $res->get_param('order_id');
  $order = wc_get_order( $order_id );
  $order_user_id = $res->get_param('order_user_id');
  $current_user_id = $res->get_param('current_user_id');

  if ( $order_user_id != $current_user_id ) return false;

  $resp = $order->update_status( 'cancelled' );
  return $resp;
}
