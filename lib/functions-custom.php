<?php

// Custom functions (like special queries, etc)

function render_gallery_slider($images) {
?>

<section class="grid-row justify-center margin-bottom-large">
  <div class="slick-container item-s-10">
  <?php
    foreach($images as $image_id => $image) {
  ?>
    <?php echo wp_get_attachment_image($image_id, 'gallery', false, array('class'=>'slick-slide margin-right-small', 'data-no-lazysizes' => '')); ?>
  <?php
    }
  ?>
  </div>
</section>

<?php

}

function render_filter_select($options, $name, $classes = '') {
  if (empty($options) || empty($name)) {
   return;
  }
    ?>
<div class="<?php echo $classes; ?>">
  <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/icon-select.svg'); ?>
  <select id="filter-<?php echo $name; ?>" name="filter-<?php echo $name; ?>" class="filter-select font-bold" data-filter="<?php echo $name; ?>">
    <option value=""><?php echo $name; ?></option>
<?php
  foreach($options as $option) {
?>
    <option value="<?php echo $option->slug; ?>"><?php echo $option->name; ?></option>
<?php
  }
?>
  </select>
</div>
<?php
}

// Return taxonomy values in a single array
function get_taxonomy_values($post,$taxonomy) {
  $terms = get_the_terms($post,$taxonomy);

  if(empty($terms)) {
    return null;
  }

  $values = array();

  foreach($terms as $term) {
    array_push($values, $term->slug);
  }

  return $values;
}

// Return an array constructed from the filters values
// eg. ["region-asia", "subject-death"]
function get_post_filters_data($post) {
  $filters = array(
    'region' => get_taxonomy_values($post,'region'),
    'subject' => get_taxonomy_values($post,'subject'),
    'location' => get_taxonomy_values($post,'location'),
  );

  $filters_data = array();

  foreach($filters as $filter_slug => $filter_data) {
    if(!empty($filter_data)) {
      foreach($filter_data as $filter_value) {
        array_push($filters_data, $filter_slug . "-" . $filter_value);
      }
    }
  }

  return $filters_data;
}

function get_locations_data() {
  // Get Locations taxonomy
  $locations = get_terms( array(
    'taxonomy' => 'location',
  ), true);

  if (!empty($locations)) {

    $data = array();

    foreach($locations as $location) {
      // Get coordinates
      $lat = get_term_meta($location->term_id, '_igv_location_lat', true);
      $lng = get_term_meta($location->term_id, '_igv_location_lng', true);

      if (!empty($lat) && !empty($lng)) {
        array_push($data, array(
          'name' => $location->name,
          'slug' => $location->slug,
          'lat' => $lat,
          'lng' => $lng,
        ));
      }
    }

    return $data;
  }
}

// Render front page splash image
function render_front_splash($front_id) {
  $splash_images = get_post_meta($front_id, '_igv_home_splash_images', true);

  if (!empty($splash_images)) {
    $splash_image_id = array_rand($splash_images);
    $splash_image_srcset = wp_get_attachment_image_srcset($splash_image_id, 'full-width');
?>

<section id="portraits-cover" class="grid-row">
  <div class="item-s-12 full-splash-image lazyload" data-bgset="<?php echo $splash_image_srcset; ?>" data-sizes="auto"></div>
</section>

<?php
  }
}

// Render Support page form
function render_support_form($form_options, $thanks) {
?>
<form id="support-form" class="grid-row" method="post">
  <div id="support-form-row" class="grid-item item-s-12 no-gutter grid-row align-items-end">
    <input type="hidden" name="nonce" id="nonce" value="<?php echo wp_create_nonce('enquiry'); ?>">
    <div id="i-want-to" class="grid-item">
      I want to
    </div>
    <div class="grid-item grid-column flex-grow">
      <input id="support-form-email" class="form-element margin-top-basic" type="email" placeholder="my email" name="email" required/>

      <select id="support-form-select" class="form-element margin-top-basic u-pointer" name="message" required>
        <?php
          foreach ($form_options as $option) {
            echo '<option>' . $option . '</option>';
          }
        ?>
      </select>
    </div>
    <div class="grid-item">
      <button id="support-submit" class="form-arrow u-pointer" type="submit"><?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-right.svg'); ?></button>
    </div>
  </div>

  <div class="grid-item item-s-12 text-align-center font-size-basic margin-top-basic" id="support-messages">
    <div class="message-thanks">
      <?php echo !empty($thanks) ? $thanks : 'Thank You! Your message has been sent.'; ?>
    </div>
    <div class="message-error">
    </div>
  </div>
</form>
<?php
}

add_action( 'wp_ajax_send_enquiry', 'send_enquiry' );
add_action( 'wp_ajax_nopriv_send_enquiry', 'send_enquiry' );

function send_enquiry() {
  check_ajax_referer('enquiry', 'nonce');

  $support_page = get_page_by_path('support');
  $support_page_id = $support_page->ID;

  $to = get_post_meta($support_page_id, '_igv_support_form_recipient', true);

  header('Content-Type: application/json');

  if (!empty($to)) {
    $from = sanitize_email($_POST['data']['email']);
    $message = sanitize_text_field($_POST['data']['message']);

    $title   = 'Support Form Inquiry';
    $headers = array('From: ' . $from . ' <' . $from . '>');
    $content = 'I want to ' . $message;

    //Send the email
    add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
    $email = wp_mail($to, $title, $message, $headers);
    remove_filter('wp_mail_content_type', 'set_html_content_type');

    if ($email) {
      echo json_encode(array('type' => 'success'));
    } else {
      echo json_encode(array('type' => 'error', 'error' => array('type' => 2, 'message' => 'Message not sent. Email sending failed.')));
    }

  } else {
    echo json_encode(array('type' => 'error', 'error' => array('type' => 1, 'message' => 'Contact form not configured. Message not sent.')));
  }

  wp_die();
}
