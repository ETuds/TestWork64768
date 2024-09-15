<?php
/* Template Name: Cities List */
get_header();

$args = array(
    'post_type' => 'city',
    'posts_per_page' => -1
);
$cities = get_posts($args);
?>
<form id="city-search-form">
    <input type="text" id="city-search" name="city-search" placeholder="Search Cities">
</form>
<table id="cities-table">
    <thead>
        <tr>
            <th>Country</th>
            <th>City</th>
            <th>Temperature</th>
        </tr>
    </thead>
    <tbody>
        <!-- City populated via Ajax -->
        <?php foreach ($cities as $city) : ?>
            <?php
                $temperatures = getTemperature($city->ID);
                $country = wp_get_post_terms($city->ID, 'country', array('fields' => 'names'));
                ?>
            <tr>
                <td><?php echo $country[0] ?></td>
                <td><?php echo $city->post_title; ?></td>
                <td><?php echo $temperatures->celcius.'°C'; ?><br><?php echo $temperatures->farenheit.'°F'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php get_footer(); ?>