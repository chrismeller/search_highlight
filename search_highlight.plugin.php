<?php

	class Search_Highlight extends Plugin {
		
		public function filter_post_content_out ( $content ) {
			
			if ( isset( $_SERVER['HTTP_REFERER' ] ) ) {
				
				$ref = urldecode( $_SERVER['HTTP_REFERER'] );
				
				// parse out the query string from the url
				$query = parse_url( $ref, PHP_URL_QUERY );
				
				// if there is no query, return
				if ( $query == null ) {
					
					return $content;
					
				}
				else {
					
					// parse the values and store them in itself
					parse_str( $query, $query );
					
					// see if one of our accepted values is set
					if ( isset( $query['q'] ) ) {
						$search = $query['q'];
					}
					else if ( isset( $query['query'] ) ) {
						$search = $query['query'];
					}
					else if ( isset( $query['p'] ) ) {
						$search = $query['p'];
					}
					else if ( isset( $query['wd'] ) ) {
						$search = $query['wd'];
					}
					else {
						return $content;
					}
					
					// this is where it gets incredibly lazy - we don't care about specific phrases, just words
					
					// strip out any quotes
					$search = MultiByte::str_replace( '"', '', $search );
					
					// now split it up
					$search = explode( ' ', $search );
					
					foreach ( $search as $s ) {
						
						// find the position, doing a lowercase match
						$pos = MultiByte::strpos( MultiByte::strtolower( $content ), MultiByte::strtolower( $s ) );
						
						// pull out that phrase so we have a case-sensitive version
						$phrase = MultiByte::substr( $content, $pos, MultiByte::strlen( $s ) );
						
						// just replace it in the content with a span
						$content = MultiByte::str_replace( $phrase, '<span class="highlight">' . $phrase . '</span>', $content );
						
					}
					
					return $content;
					
				}
				
			}
			
			return $content;
			
		}
		
	}

?>