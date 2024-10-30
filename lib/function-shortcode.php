<?php
if ( ! function_exists( 'bolt_iframe_shortcode' ) ){
	function bolt_iframe_shortcode( $atts ) {
		$defaults = array(
			'width' => '100%',
			'height' => '420',
			'scrolling' => 'yes',
			'class' => 'bolt-embed',
			'frameborder' => '0'
		);

		foreach ( $defaults as $default => $value ) { 
			if ( ! @array_key_exists( $default, $atts ) ) { 
				$atts[$default] = $value;
			}
		}

		//take out postion absolute and fixed from bolt api
		$style_arr = explode(';', $atts['style']);
		$atts['style'] = '';
		foreach ($style_arr as $k => $v) {
			if (trim($style_arr[$k]) !== 'position: absolute' || 'position: fixed') {
				$atts['style'] .= $style_arr[$k];
			}
		}

		$html = "";
		$html .= '<iframe';
		foreach( $atts as $attr => $value ) {
				if ( $value != '' ) { 
					$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
				} else { 
					$html .= ' ' . esc_attr( $attr );
				}
		}
		$html .= '></iframe>'."\n";

		return $html;
	}
add_shortcode( 'iframe', 'bolt_iframe_shortcode' );
}

if ( ! function_exists( 'bolt_iframe_filter' ) ){
	function bolt_iframe_filter($content){
		preg_match_all("#<div class='medium-insert-embed'>.*?<iframe .*? style='(.*?)'></iframe>#ims", $content, $matches);
		$mediumStyle = 'position: absolute;';
		if( !empty($matches) ) {
            foreach ($matches[1] as $k => $v) {
                $iframeStyle = $matches[1][$k];             
                $pos = strpos($iframeStyle,$mediumStyle);
                if ($pos !== false) {           
                    $content = str_replace($mediumStyle, 'min-height:320px;border:none;', $content);
                }
            }
		}
		return $content;
	}
add_filter('the_content','bolt_iframe_filter');
}


