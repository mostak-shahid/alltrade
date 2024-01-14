<?php
class Title_Image_Widget extends WP_Widget
{
    // Define the constructor
    public function __construct()
    {
        parent::__construct(
            'title_image_widget', // Base ID
            'Title and Image Widget', // Name
            array('description' => __('A widget to display a title and an image', 'text_domain'))
        );
    }

    // Define the widget output
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        if (!empty($instance['image'])) {
            echo '<img src="' . esc_url($instance['image']) . '" alt="' . esc_attr($instance['title']) . '">';
        }

        echo $args['after_widget'];
    }

    // Define the widget form in the admin area
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $image = !empty($instance['image']) ? $instance['image'] : '';
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image URL:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_url($image); ?>">
        </p>
<?php
    }

    // Update the widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['image'] = (!empty($new_instance['image'])) ? esc_url_raw($new_instance['image']) : '';

        return $instance;
    }
}
?>