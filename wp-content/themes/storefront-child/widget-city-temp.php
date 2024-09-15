<?php
class CityTemperatureWidget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'city_temperature_widget', // Base ID
            'City Temperature', // Name
            array('description' => 'Displays the city name and temperature')
        );
    }

    public function widget($args, $instance) {
        $city_id = $instance['city_id'];
        $city_name = get_the_title($city_id);
        $temperatures = getTemperature($city_id);

        echo $args['before_widget'];
        echo $args['before_title'] . $city_name . $args['after_title'];
        echo 'Temperature: ' . $temperatures->celcius . '°C<br>';
        echo 'Temperature: ' . $temperatures->farenheit . '°F';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>">City:</label>
            <select name="<?php echo $this->get_field_name('city_id'); ?>" id="<?php echo $this->get_field_id('city_id'); ?>">
                <?php
                $cities = get_posts(array('post_type' => 'city', 'numberposts' => -1));

                foreach ($cities as $city) {
                    echo '<option value="' . $city->ID . '" ' . selected($city->ID, $city_id, false) . '>' . $city->post_title . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? sanitize_text_field($new_instance['city_id']) : '';
        return $instance;
    }
}
function registerCityTemperatureWidget() {
    register_widget('CityTemperatureWidget');
}
add_action('widgets_init', 'registerCityTemperatureWidget');
