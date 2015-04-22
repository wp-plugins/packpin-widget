<div class="packpin_widget_admin">
    <p>
        <em>Eases addition of Packping E-Commerce Widget to your Wordpress project.</em>
    </p>
    <h4>Widget appearance</h4>
    <p>
        <label for="<?php echo $this->get_field_id( 'position' ); ?>"><?php _e( 'Button position', $this->get_widget_slug() ); ?>:</label>
        <select class="widefat" id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>">
            <?php foreach ( $position as $val => $name): ?>
                <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $instance['position'], $val ); ?>><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Button width (px)', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" placeholder="Auto" value="<?php echo esc_attr( $instance['width'] ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Button height (px)', $this->get_widget_slug()); ?>:</label>
        <select class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>">
            <?php foreach ( $height as $val => $name): ?>
                <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $instance['height'], $val ); ?>><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e( 'Button text color (hex)', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat packpinColorPicker packpinColorPickerOff" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" value="<?php echo esc_attr( $instance['color'] ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'background' ); ?>"><?php _e( 'Button background color (hex)', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat packpinColorPicker packpinColorPickerOff" id="<?php echo $this->get_field_id( 'background' ); ?>" name="<?php echo $this->get_field_name( 'background' ); ?>" value="<?php echo esc_attr( $instance['background'] ); ?>" />
    </p>
    <h4>Other</h4>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget title', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
    </p>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Before text', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" value="<?php echo esc_attr( $instance['before_text'] ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'after_text' ); ?>"><?php _e( 'After text', $this->get_widget_slug()); ?>:</label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'after_text' ); ?>" name="<?php echo $this->get_field_name( 'after_text' ); ?>" value="<?php echo esc_attr( $instance['after_text'] ); ?>" />
    </p>
</div>