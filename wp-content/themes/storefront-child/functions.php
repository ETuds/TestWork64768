<?php
// Include other widget city temperature
include('widget-city-temp.php');

//Enquee child and parent theme styles
function childEnqueueStyle() {
    wp_enqueue_style('storefront-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('storefront-child-style', get_stylesheet_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'childEnqueueStyle');

//Enquee child theme script
function childEnqueueScripts() {
    wp_enqueue_script('cities-search', get_stylesheet_directory_uri() . '/js/search-city.js', array('jquery'), null, true);
    wp_localize_script('cities-search', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'childEnqueueScripts');

//For checking debug
function dbug($var){
    echo '<pre>'.print_r($var, true).'</pre>';
}

//Register City Custom Post Type
function registerCitiesPostType() {
    $label = array(

        'name' => 'Cities',
        'singular_name' => 'City',
        'menu_name' => 'Cities',
        'name_admin_bar' => 'City',
        'add_new' => 'Add New City',
        'add_new_item' => 'Add New City',
        'edit_item' => 'Edit City',
        'new_item' => 'New City',
        'view_item' => 'View City',
        'all_items' => 'All Cities',
        'search_items' => 'Search Cities',
        'not_found' => 'No cities found',
        'not_found_in_trash' => 'No cities found in Trash'
    );
    $args = array(
        'labels' => $label,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-location-alt',
    );

    register_post_type('city', $args);
}
add_action('init', 'registerCitiesPostType');

// Register 'Countries' Taxonomy
function registerCountriesTaxonomy() {
    $labels = array(
        'name' => 'Countries',
        'singular_name' => 'Country',
        'search_items' => 'Search Countries',
        'all_items' => 'All Countries',
        'edit_item' => 'Edit Country',
        'add_new_item' => 'Add New Country',
        'new_item_name' => 'New Country Name',
        'menu_name' => 'Countries',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_in_rest' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'country'),
    );

    register_taxonomy('country', array('city'), $args);
}
add_action('init', 'registerCountriesTaxonomy');

// Add Longitude and Latitude Metaboxes in City
function cityLongitudeLatitudeMetabox() {
    add_meta_box(
        'city_longitude_latitude',
        __( 'City Coordinates', 'textdomain' ),
        'renderLongitudeLatitude',
        'city',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'cityLongitudeLatitudeMetabox' );

// Render Longitude and Latitude
function renderLongitudeLatitude( $post ) {
    $latitude = get_post_meta( $post->ID, '_city_latitude', true );
    $longitude = get_post_meta( $post->ID, '_city_longitude', true );

    wp_nonce_field( 'save_city_lat_long', 'city_longitude_latitude_nonce' );
    ?>
    <p>
        <label for="city_longitude"><?php _e( 'Longitude:', 'textdomain' ); ?></label><br>
        <input type="number" step="any" id="city_longitude" name="city_longitude" value="<?php echo esc_attr( $longitude ); ?>" />
    </p>
    <p>
        <label for="city_latitude"><?php _e( 'Latitude:', 'textdomain' ); ?></label><br>
        <input type="number" step="any" id="city_latitude" name="city_latitude" value="<?php echo esc_attr( $latitude ); ?>" />
    </p>
    <?php
}

// Save Longitude and Latitude to Post
function saveCityLongitudeLatitude( $post_id ) {
    if ( ! isset( $_POST['city_longitude_latitude_nonce'] ) || ! wp_verify_nonce( $_POST['city_longitude_latitude_nonce'], 'save_city_lat_long' ) ) {
        return;
    }

    //Update post metas for latitude and longitude
    if ( isset( $_POST['city_longitude'] ) ) {
        update_post_meta( $post_id, '_city_longitude', sanitize_text_field( $_POST['city_longitude'] ) );
    }
    if ( isset( $_POST['city_latitude'] ) ) {
        update_post_meta( $post_id, '_city_latitude', sanitize_text_field( $_POST['city_latitude'] ) );
    }
}
add_action( 'save_post', 'saveCityLongitudeLatitude' );

//Get cities table on search
function getCitiesTable() {
    global $wpdb;

    // Get the entered city name
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Get cities from database using the entered city name
    $query = " SELECT p.ID, p.post_title, t.name AS country
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'city' AND p.post_status = 'publish'
        AND p.post_title LIKE '%$search%'
        AND tt.taxonomy = 'country'";

    $results = $wpdb->get_results($query);

    // Display cities in table
    if ($results) {
        foreach ($results as $city) {
            $temperatures = getTemperature($city->ID);
            echo "<tr>
                    <td>".$city->country."</td>
                    <td>".$city->post_title."</td>
                    <td>".$temperatures->celcius."<br>".$temperatures->farenheit."</td>
                  </tr>";
        }
    } else {
        echo '<tr><td colspan="3">No cities found.</td></tr>';
    }
    wp_die();
}
add_action('wp_ajax_getCitiesTable', 'getCitiesTable');
add_action('wp_ajax_nopriv_getCitiesTable', 'getCitiesTable');


function getTemperature( $post_id){
    $latitude = get_post_meta($post_id, '_city_latitude', true);
    $longitude = get_post_meta($post_id, '_city_longitude', true);

    // Fetch temperature using OpenWeatherMap API
    $api_key = 'd58c0604dadcc8f146de7d32fc18ffa6';
    $api_url = "https://api.openweathermap.org/data/3.0/onecall?lat=$latitude&lon=$longitude&exclude=hourly,daily&appid=$api_key";
    $response = wp_remote_get($api_url);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if ($data && isset($data->current->temp)) {
        $temperature_celcius = $data->current->temp - 273.15; // Convert Kelvin to Celsius
        $temperature_farenheit = $temperature_celcius * 9/5 + 32; // Convert Celsius to Farenheit
    } else {
        $temperature_celcius = 'N/A';
        $temperature_farenheit = 'N/A';
    }

    //Create an object to return
    $temp = array('celcius' => $temperature_celcius,'farenheit' =>  $temperature_farenheit);
    $temp = (object) $temp;
    return $temp;
}

