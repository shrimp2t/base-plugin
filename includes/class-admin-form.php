<?php
/**
 *
 * Simple form builder for admin settings
 *
 * Class SA_Admin_Form
 */
class SA_Admin_Form{
    var $fields;
    var $allow_tags =  array(
                                'a' => array(),
                                'br' => array(),
                                'em' => array(),
                                'i' => array(),
                                'span' => array(),
                                'code' => array(),
                                'u' => array(),
                                'strong' => array(),
                            );
    var $msg = '';
    function __construct( $fields ){
        $this->fields = $fields;
        $this->save();
    }

    function save(){
        // Your settings have been saved.
        //

        $nonce = wp_verify_nonce( $_POST['_wpnonce'] );

        $is_remove = isset( $_POST['reset'] )  && $_POST['reset'] != '' ? true : false;
        if( $nonce == 1 || $nonce == 2 ) {
            foreach ($this->fields as $field) {
                $field = wp_parse_args($field, array(
                    'name' => '',
                ));
                if( $field['name'] != '' ){
                    if( $is_remove ){
                        delete_option( $field['name'] );
                    }else{
                        update_option( $field['name'] , isset( $_POST[ $field['name'] ] ) ?  $_POST[ $field['name'] ] : '' );
                    }

                }
            }

            $this->msg = '<div class="updated fade" id="message"><p><strong>'.__( 'Your settings have been saved.', 'sa-plugin' ).'</strong></p></div>';

        }

    }

    function render(){
        ob_start();

        if( $this->msg ){
            echo $this->msg;
        }
        ?>
        <table class="form-table">
            <?php foreach(  $this->fields as $field ){ ?>
            <tr>
                <?php
                switch( $field['type'] ){
                    case 'textarea':
                        $this->textarea( $field );
                        break;
                    case 'radio':
                        $this->radio( $field );
                        break;
                    case 'checkbox':
                        $this->checkbox( $field );
                        break;
                    case 'select':
                        $this->select( $field );
                        break;
                    default:
                        $this->text($field);
                }
                ?>
            </tr>
            <?php } ?>
        </table>
        <p class="submit">
            <input type="submit" value="<?php echo esc_attr( __('Save Changes', 'sa-plugin') ) ?>" class="button button-primary"  name="submit">
            <input type="submit" value="<?php echo esc_attr( __('Reset', 'sa-plugin') ) ?>" class="button button-secondary"  name="reset">
        </p>
        <?php wp_nonce_field(); ?>
        <?php
        return ob_get_clean();
    }

    function get_val( $name ){
        return  get_option( $name, false ) ;
    }

    function radio( $setting  ){
        $setting = wp_parse_args( $setting, array(
            'type'=>'',
            'name' =>'',
            'title'=>'',
            'default' => '',
            'list' => array(),
            'desc' =>''
        ) );

        if(  $setting['name'] != '' ){
            $value =  $this->get_val( $setting['name'] );
        }else{
            $value = '';
        }

        $id = uniqid('text-');
        ?>
        <th scope="row"><label for="<?php echo $id; ?>"><?php echo wp_kses( $setting['title'] , $this->allow_tags ); ?></label></th>
        <td>
            <?php
            foreach( $setting['list'] as $k=> $label ){
                ?>
                <label><input type="radio" <?php  checked( $value, $k ); ?> value="<?php echo esc_attr( $k ); ?>" name="<?php echo $setting['name']; ?>"> <?php echo wp_kses( $label, $this->allow_tags ); ?></label><br/>
                <?php
            }
            ?>
            <?php if( !empty( $setting['desc'] ) ){ ?>
                <p class="description"><?php echo wp_kses( $setting['desc'] , $this->allow_tags );?></p>
            <?php } ?>
        </td>
    <?php
    }

    function checkbox( $setting  ){
        $setting = wp_parse_args( $setting, array(
            'type'=>'',
            'name' =>'',
            'title'=>'',
            'default' => '',
            'multiple' => false,
            'desc' =>''
        ) );

        if(  $setting['name'] != '' ){
            $value =  $this->get_val( $setting['name'] );
        }else{
            $value = '';
        }

        $id = uniqid('text-');
        ?>
        <th scope="row"><label for="<?php echo $id; ?>"><?php echo wp_kses( $setting['title'] , $this->allow_tags ); ?></label></th>
        <td>
            <?php
            if( ! $setting['multiple'] ){
                ?>
                <label><input type="checkbox" <?php  checked( $value, 1 ); ?> value="<?php echo esc_attr(1); ?>" name="<?php echo $setting['name']; ?>"> <?php echo wp_kses( $setting['label'], $this->allow_tags ); ?></label><br/>
                <?php
            }else{
                if( !array(  $value ) ){
                    $value = ( array )  $value;
                }
                foreach( $setting['list'] as $k=> $label ){
                    $v  =  isset( $value[$k] ) ?  $value[$k] :  0;
                    ?>
                    <label><input type="checkbox" <?php  checked( $v, 1 ); ?> value="<?php echo esc_attr( 1 ); ?>" name="<?php echo $setting['name'].'['.$k.']'; ?>"> <?php echo wp_kses( $label, $this->allow_tags ); ?></label><br/>
                <?php
                }
            }
            ?>
            <?php if( !empty( $setting['desc'] ) ){ ?>
                <p class="description"><?php echo wp_kses( $setting['desc'] , $this->allow_tags );?></p>
            <?php } ?>
        </td>
    <?php
    }

    function select( $setting  ){
        $setting = wp_parse_args( $setting, array(
            'type'=>'',
            'name' =>'',
            'title'=>'',
            'default' => '',
            'options' => array(),
            'multiple' => false,
            'desc' =>''
        ) );

        if(  $setting['name'] != '' ){
            $value =  $this->get_val( $setting['name'] );
        }else{
            $value = '';
        }

        $name =  $setting['name'];

        if( $setting['multiple'] ){
            $new_v = array();
            $value =  is_array(  $value ) ? $value :  (array)  $value;
            foreach(  $value as $k => $v ){
                $new_v[$v] = $v;
            }
            $value =  $new_v;
            unset(  $new_v );
            $name .='[]';
        }
        
        $id = uniqid('text-');
        ?>
        <th scope="row"><label for="<?php echo $id; ?>"><?php echo wp_kses( $setting['title'] , $this->allow_tags ); ?></label></th>
        <td>
            <select <?php echo $setting['multiple'] ? ' multiple="multiple" ' : ''; ?> name="<?php echo esc_attr( $name ); ?>">
                <?php
                foreach(  $setting['options'] as $k => $label ){
                    if( $setting['multiple'] ){
                        $v =  isset(  $value[$k] ) ?  $value[$k] : false;
                    }else{
                        $v =  $value;
                    }
                    ?>
                    <option <?php selected($v, $k); ?> value="<?php  echo esc_attr( $k ); ?>"><?php echo esc_html(  $label ); ?></option>
                <?php } ?>
            </select>
            <?php if( !empty( $setting['desc'] ) ){ ?>
                <p class="description"><?php echo wp_kses( $setting['desc'] , $this->allow_tags );?></p>
            <?php } ?>
        </td>
    <?php
    }

    function text( $setting  ){
        $setting = wp_parse_args( $setting, array(
            'type'=>'',
            'name' =>'',
            'placeholder' =>'',
            'title'=>'',
            'default' => '',
            'desc' =>''
        ) );

        if(  $setting['name'] != '' ){
            $value =  $this->get_val( $setting['name'] );
        }else{
            $value = '';
        }

        $id = uniqid('text-');
        ?>
        <th scope="row"><label for="<?php echo $id; ?>"><?php echo wp_kses( $setting['title'] , $this->allow_tags ); ?></label></th>
        <td>
            <input type="text" class="regular-text" placeholder="<?php echo esc_attr( $setting['placeholder'] ); ?>" value="<?php echo esc_attr($value); ?>" id="<?php echo $id; ?>" name="<?php echo esc_attr( $setting['name'] ); ?>">
            <?php if( !empty( $setting['desc'] ) ){ ?>
            <p class="description"><?php echo wp_kses( $setting['desc'] , $this->allow_tags );?></p>
            <?php } ?>
        </td>
        <?php
    }

    function textarea( $setting  ){
        $setting = wp_parse_args( $setting, array(
            'type'=>'',
            'name' =>'',
            'placeholder' =>'',
            'title'=>'',
            'default' => '',
            'cols' => 40,
            'rows' => 5,
            'desc' =>''
        ) );

        if(  $setting['name'] != '' ){
            $value =  $this->get_val( $setting['name'] );
        }else{
            $value = '';
        }

        $id = uniqid('text-');
        ?>
        <th scope="row"><label for="<?php echo $id; ?>"><?php echo wp_kses( $setting['title'] , $this->allow_tags ); ?></label></th>
        <td>
            <textarea cols="<?php echo esc_attr(  $setting['cols'] ); ?>" rows="<?php echo esc_attr(  $setting['rows'] ); ?>" class="regular-text" placeholder="<?php echo esc_attr( $setting['placeholder'] ); ?>"  id="<?php echo $id; ?>" name="<?php echo esc_attr( $setting['name'] ); ?>"><?php echo esc_textarea($value); ?></textarea>
            <?php if( !empty( $setting['desc'] ) ){ ?>
                <p class="description"><?php echo wp_kses( $setting['desc'] , $this->allow_tags );?></p>
            <?php } ?>
        </td>
    <?php
    }

}