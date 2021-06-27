<?php

namespace thin;

class Comments {
	public $avatar_size = 45;

	public function __construct() {
		$this->boot();
	}

	public function boot() {
		add_filter( 'wp_list_comments_args', [ $this, 'filter_list_comments_args' ] );
		add_filter( 'comment_form_defaults', [ $this, 'set_comment_form_defaults' ] );
		add_filter( 'comment_form_default_fields', [ $this, 'filter_comment_fields' ] );
	}

	public function filter_list_comments_args( $args ) {
		$args['avatar_size'] = $this->avatar_size;

		return $args;
	}

	public function set_comment_form_defaults( $defaults ) {
		$defaults['comment_field'] = sprintf(
			'<label for="comment" class="screen-reader-text">%1$s</label><textarea id="comment" class="comment-form-comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>',
			esc_html__( 'Comment', 'vnh_textdomain' )
		);

		$defaults['comment_notes_before'] = null;
		$defaults['comment_notes_after']  = null;

		return $defaults;
	}

	public function filter_comment_fields( $fields ) {
		$commenter = wp_get_current_commenter();

		$fields['author'] = sprintf(
			'<label for="author" class="screen-reader-text">%1$s</label><input placeholder="%1$s *" id="author" name="author" type="text" value="%2$s" size="30" />',
			esc_html__( 'Name', 'vnh_textdomain' ),
			esc_attr( $commenter['comment_author'] )
		);

		$fields['email'] = sprintf(
			'<label for="email" class="screen-reader-text">%1$s</label><input placeholder="%1$s *" id="email" name="email" type="email" value="%2$s" size="30" />',
			esc_html__( 'Email', 'vnh_textdomain' ),
			esc_attr( $commenter['comment_author_email'] )
		);

		$fields['url'] = sprintf(
			'<label for="url" class="screen-reader-text">%1$s</label><input placeholder="%1$s" id="url" name="url" type="url" value="%2$s" size="30" />',
			esc_html__( 'Website', 'vnh_textdomain' ),
			esc_attr( $commenter['comment_author_url'] )
		);

		return $fields;
	}

	public static function nav() {
		the_comments_navigation(
			[
				'prev_text' => sprintf( '<span>&larr;</span>%s', esc_html__( 'Older comments', 'vnh_textdomain' ) ),
				'next_text' => sprintf( '%s<span>&rarr;</span>', esc_html__( 'Newer comments', 'vnh_textdomain' ) ),
			]
		);
	}

	public static function title() {
		$comments_number = get_comments_number();
		$comments_title  = sprintf(
			_nx( '%1$s comment', '%1$s comments', $comments_number, 'comments title', 'vnh_textdomain' ),
			number_format_i18n( $comments_number )
		);

		echo esc_html( $comments_title );
	}

	public static function closed() {
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
			printf( '<p class="no-comments">%s</p>', esc_html__( 'Comments are closed.', 'vnh_textdomain' ) );
		}
	}
}
