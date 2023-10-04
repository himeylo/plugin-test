<?php
/**
 * Advanced Custom Fields registration file.
 *
 * @package WordPress_Plugin_Name
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :
	acf_add_local_field_group(
		array(
			'key'                   => 'group_622025aceca44',
			'title'                 => 'Advanced Custom Fields',
			'fields'                => array(
				array(
					'key'               => 'field_622025efc5aa7',
					'label'             => 'Field 1',
					'name'              => 'post_field_1',
					'type'              => 'text',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => 'field_622026b1c5aa8',
					'label'             => 'Field 2',
					'name'              => 'post_field_2',
					'type'              => 'radio',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'Option A' => 'Option A',
						'Option B' => 'Option B',
						'Option C' => 'Option C',
					),
					'allow_null'        => 0,
					'other_choice'      => 1,
					'save_other_choice' => 0,
					'default_value'     => false,
					'layout'            => 'vertical',
					'return_format'     => 'value',
				),
				array(
					'key'               => 'field_62202795c5aa9',
					'label'             => 'Field 3',
					'name'              => 'post_field_3',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'Option A' => 'Option A',
						'Option B' => 'Option B',
						'Option C' => 'Option C',
					),
					'default_value'     => false,
					'allow_null'        => 1,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'new_post_type',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
			'show_in_rest'          => 1,
		)
	);
endif;
