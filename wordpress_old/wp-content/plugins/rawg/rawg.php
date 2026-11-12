<?php

/**
 * Plugin Name: RAWG Integration
 * Description: Integration mellan WordPress och RAWG.
 * Version: 1.0
 */

function rawg_register_post_types(): void
{
    register_post_type('review', [
        'labels' => [
            'name'          => 'Reviews',
            'singular_name' => 'Review',
            'add_new'       => 'Lägg till Review',
            'edit_item'     => 'Redigera Review',
        ],
        'public'      => true,
        'show_ui'     => true,
        'show_in_rest'=> true,
        'has_archive' => true,
        'rewrite'     => [
            'slug' => 'reviews',
        ],
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'author',
            'comments'
        ],
    ]);
}

add_action('init', 'rawg_register_post_types');

/**
 * Gör ACF-fält tillgängliga för WordPress Block Bindings.
 */
add_action('init', function () {

    register_block_bindings_source('rawg/acf', [
        'label'              => 'ACF',
        'get_value_callback' => function ($source_args, $block_instance) {

            if (empty($source_args['field'])) {
                return '';
            }

            $value = get_field($source_args['field']);

            if (is_array($value)) {
                return implode(', ', $value);
            }

            return $value ?? '';
        },
    ]);

});