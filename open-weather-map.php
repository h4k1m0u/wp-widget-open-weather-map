<?php
/*
Plugin Name: Open Weather Map
Plugin URI: http://plugin.url
Description: A plugin that gets weather forecast from openweathermap.org.
Version: 1.0
Author: Hakim Benoudjit
Author URI: http://author.url
*/

// helloworld widget class
class Open_Weather_Map_Widget extends WP_Widget {
    public function __construct() {
        // widget actual process
        parent::WP_Widget(
            'open-weather-map',
            'Open Weather Map', 
            array(
                'classname' => 'open-weather-map',
                'description' => 'A widget that gets weather forecast from openweathermap.org'
            )
        );
    }

    public function form($instance) {
        // Outputs the options form on admin
        $city = ($instance['city'] ? $instance['city'] : 'Algiers,dz');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city'); ?>">City</label>
            <input id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" value="<?php echo esc_attr($city); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instace) {
        // Processing widget options on save
        $instance = array();
        $instance['city'] = ($new_instance['city'] ? strip_tags($new_instance['city']) : 'Algiers,dz');

        return $instance;
    }

    public function widget($args, $instance) {
        // Outputs the content of the widget
        $city = $instance['city'];
        $resp = wp_remote_get("http://api.openweathermap.org/data/2.5/weather?q=$city&units=metric");
        $body = json_decode($resp['body']);

        // values retreived
        $message = $body->weather[0]->description;
        $temperature = $body->main->temp;
        $humidity = $body->main->humidity;
        echo $message . '<br>';
        echo $temperature . '<br>';
        echo $humidity . '<br>';
    }
}

// register the widget
add_action('widgets_init', function() {
    register_widget('Open_Weather_Map_Widget');
});
