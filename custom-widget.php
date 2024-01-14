<?php
class Custom_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'custom_widget', // Base ID
            'Custom Widget', // Name
            array('description' => __('A custom widget', 'text_domain'))
        );
    }

    public function widget($args, $instance)
    {
        // Front-end display of the widget
        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        echo __('Hello, World!', 'text_domain');
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        // Back-end widget form
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'text_domain');
        }
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        // Process widget options on save
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

// Register the custom widget
function register_custom_widget()
{
    register_widget('Custom_Widget');
}
add_action('widgets_init', 'register_custom_widget');
?>