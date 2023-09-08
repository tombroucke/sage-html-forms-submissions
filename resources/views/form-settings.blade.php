<tr valign="top">
  <th scope="row"><label for="hf_form_redirect"><?php _e( 'Clear page cache', 'html-forms' ); ?></label></th>
  <td>
      <input type="text" class="widefat" name="form[settings][clear_cache]" id="hf_form_redirect" placeholder="<?php printf( __( 'Example: %s', 'html-forms' ), '42,42069' ); ?>" value="<?php echo esc_attr( $form->settings['clear_cache'] ); ?>" />
      <p class="description">
          <?php _e( 'Enter page ID\'s to clear cache after submission. Separated by comma\'s', 'html-forms' ); ?>
      </p>
  </td>
</tr>
