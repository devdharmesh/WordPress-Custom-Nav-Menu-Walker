<?php

/**
 * WordPress Custom Nav Menu Walker
 */

class Custom_Nav_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1);
		$classes = array_filter ( array(
			'sub-menu',
			( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
			( $display_depth >=2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth
		) );
		$class_names = esc_attr( implode( ' ', $classes ) );

		// Build HTML for output.
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		parent::end_lvl($output, $depth, $args); // TODO: Change the autogenerated stub
	}

	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
	{
		global $wp_query;

		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
		$depth_classes = array_filter( array(
			'nav-item',
			( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >=2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		) );

		$classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
		$class_names = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $data_object );
		$menu_class_name = esc_attr( implode( ' ', array_merge( $depth_classes, $class_names ) ) );
		$output .= $indent . '<li id="nav-menu-item-'. $data_object->ID . '" class="' . $menu_class_name . '">';
		$link_class_name = array_filter( array(
			'menu-link nav-link',
			( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ),
			( $data_object->current ? 'active' : '' ),
			( $data_object->current_item_ancestor ? 'ancestor_active' : '' ),
			( $data_object->current_item_parent ? 'parent_active' : '' ),
		) );

		$attributes  = ! empty( $data_object->attr_title ) ? ' title="'  . esc_attr( $data_object->attr_title ) .'"' : '';
		$attributes .= ! empty( $data_object->target )     ? ' target="' . esc_attr( $data_object->target     ) .'"' : '';
		$attributes .= ! empty( $data_object->xfn )        ? ' rel="'    . esc_attr( $data_object->xfn        ) .'"' : '';
		$attributes .= ! empty( $data_object->url )        ? ' href="'   . esc_attr( $data_object->url        ) .'"' : '';
		$attributes .= ' class="'. esc_attr( implode( ' ', $link_class_name ) ) .'"';

		$args_before = $args->before;
		$args_after = $args->before;
		$args_link_before = $args->link_before;
		$args_link_after = $args->link_after;

		$args_link_after .= ( in_array( 'menu-item-has-children', $data_object->classes ) ) ? '<span class="item-dropdwon"></span>' : '';

		// Build HTML output and pass through the proper filter.
		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args_before,
			$attributes,
			$args_link_before,
			apply_filters( 'the_title', $data_object->title, $data_object->ID ),
			$args_link_after,
			$args_after
		);

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $data_object, $depth, $args );
	}

	public function end_el(&$output, $data_object, $depth = 0, $args = null)
	{
		parent::end_el($output, $data_object, $depth, $args); // TODO: Change the autogenerated stub
	}
}
