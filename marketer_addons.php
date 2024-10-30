<?php
/*
Plugin Name: Marketer Add Ons
Plugin URI: http://wordpress.org/plugins/marketer-add-ons/
Description: Added functionality for the marketer theme
Version: 1.0.1
Author: DigitalCourt
License: GPL2
*/
/*
Copyright 2014 DigitalCourt  

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function marketer_addons_enqueue_assets() {
	wp_enqueue_style( 'marketer-addons-css', plugins_url( 'css/marketer-addons.css', __FILE__ ));
}

add_action( 'after_setup_theme', 'marketer_addons_setup' );

function marketer_addons_setup() {
	add_action('wp_enqueue_scripts', 'marketer_addons_enqueue_assets' );
}

//allows shortcodes in widgets
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

//Removes auto <p> and <br/> from shortcodes
function clean_shortcode($content) { 
	$content = do_shortcode(shortcode_unautop( $content)); 
	$content = preg_replace('#^<\/p>|^<br\s?\/?>|<p>$|<p>\s*(&nbsp;)?\s*<\/p>#', '', $content);
	$content = preg_replace('|\n</p>$|', '</p>', $content);
	$content = preg_replace('#\<br*.?\/\>#is', '', $content);	
	return $content;
}

//Blockquote
add_shortcode('blockquote', 'blockquote_func');

function blockquote_func( $attr = null, $content = null ) {
    return '<blockquote><p>' . clean_shortcode($content) . '</p></blockquote>';
}

//Buttons
add_shortcode('button', 'button_func');

function button_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'size' => '',
		'color' => '',
		'style' => 'square',
		'title' => 'Read more',
		'url' => '#',
		'width' => '',
	), $attr));
	
	$output = '<a class="button ' . $size . ' ' . $style . ' '. $color .' " href="'. $url . '" style="width:' . $width . '; text-align:center;"><span>'. $title .'</span></a>'."\n";

    return $output;
}

//Columns
add_shortcode( 'columns', 'columns_func' );

add_shortcode( 'one_second', 'one_second_func' );
add_shortcode( 'one_half', 'one_second_func' );

add_shortcode( 'one_third', 'one_third_func' );
add_shortcode( 'two_third', 'two_third_func' );

add_shortcode( 'one_fourth', 'one_fourth_func' );
add_shortcode( 'two_fourth', 'two_fourth_func' );
add_shortcode( 'three_fourth', 'three_fourth_func' );

//row
function columns_func( $attr, $content = null ) {
	$output  = '<div class="columns clearfix">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

// 2 col
function one_second_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class . ' col1-2">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

// 3 col
function one_third_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class .' col1-3">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

function two_third_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class .' col2-3">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

//4 col
function one_fourth_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class .' col1-4">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

function two_fourth_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class .' col2-4">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

function three_fourth_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'class' => '',
	), $attr));
	
	$output  = '<div class="col'. $class .' col3-4">';
	$output .= clean_shortcode($content);
	$output .= '</div>'."\n";
	
    return $output;
}

//Highlights
add_shortcode( 'highlight', 'highlight_func' );

function highlight_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'color' => '',
	), $attr));
	
	$class = "highlight color-". $color; 
	
	$output  = '<span class="'. $class .'">';
	$output .= clean_shortcode($content);
	$output .= '</span>';
		
    return $output;
}

//Image frames
add_shortcode( 'imageframe', 'image_func' );

function image_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'width' => '',
		'height' => '',
		'src' => '',
		'alt' => '',
		'float' => 'center',
		'border' => 'true',
		'shadow' => 'true',
		'radius' => ''
	), $attr));
	
	if( $float ) 
		{ $float = ' image_frame_'. $float; }	
	
	if( $width ) 
		{ $width = ' width="'. $width .'"'; }
	
	if( $height ) 
		{ $height = ' height="'. $height .'"'; }
	
	$output = '<figure class="image_frame'. $float .'">';
	$output .= '<img src="'. $src .'"'. $width . $height .' alt="'. $alt .'" style="';
	$output .= 'border-radius:' . $radius . ';"';

	$output .= 'class="';

	//image classes
	if($shadow === 'true') { 
		$output .= 'shadow ';
	}

	if($border === 'true') { 
		$output .= 'border ';
	}

	$output .= '"/>';
	$output .= '</figure>'."\n";
	
    return $output;
}

//List
add_shortcode( 'list', 'list_func' );

function list_func( $attr, $content = null ) {
	extract(shortcode_atts(array(
		'style' => '',
		'color' => '',
		'size'  => '',
		'base' 	=> '',
		'basecolor' => ''
	), $attr));
	
	$output = clean_shortcode($content);

	if($style) {
		$icon = icon_func(array(
            'style'  => $style,
            'color' => $color,
            'size' => $size,
            'base' => $base,
            'basecolor' => $basecolor
            ));
	}
	else {
		$icon = icon_func(array('style' => 'fa fa-chevron-right'));
	}
	$output = preg_replace('#<p>#', '  <p>  ' . $icon . "\t \t", $output);	

	$output = '<div class="lists">' . $output . '</div>';

    return $output;
}

// Font awesome icons 
add_shortcode( 'icon', 'icon_func' );

function icon_func( $attr, $content = null ) {
	extract( shortcode_atts( array(
            'style'  => 'fa-wrench',
            'color' => '',
            'size' => '',
       		'url'	=> '',
            'base' => '',
            'basecolor' => ''
            ), $attr ) );
	
	$icon = '';

	if($url != '') {
		$icon .= '<a href="' . $url . '">';
	}	
	if($base == '') {
	    $icon  .= '<i class="fa '. $style .'" style="';
	    if ($color) {
	    	$icon .= 'color:' . $color . ';';
	    }
	    if ($size) {
	    	$icon .=  'font-size:' . $size . ';';
	    }
	    $icon .=  '"></i>';
	}
	else { 
		$icon .= '<span class="fa-stack" style="';
	    if ($basecolor) {
	    	$icon .= 'color:' . $basecolor . ';';
	    }
	    if ($size) {
	    	$icon .=  'font-size:' . $size . ';';
	    }
		$icon .= '"><i class="fa '. $base .' fa-stack-2x"></i>';
		$icon .= '<i class="fa fa-inverse fa-stack-1x '. $style;
		if ($color == '') {
			$icon .= ' "';
		}
		else {
			$icon .= '" style="color:' . $color . ';"';
		}
	    $icon .= '></i></span>';
	}
	if($url != '') {
		$icon .= '</a>';
	}

    return $icon;
}

//Tables 
//Fork of Free GNU based Easy Table by Takien
add_shortcode( 'table', 'table_func' );

function table_func( $atts = null, $content = null ) {

	$content = str_replace(array('<br />', '<br/>', '<br>'), array('', '', ''), $content);
	$content = str_replace('<p>', "\n", $content);
	$content = str_replace('</p>', '', $content);
	
	$content 		= str_replace('&nbsp;','',$content);
	$char_codes 	= array( '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8242;', '&#8243;' );
	$replacements 	= array( "'", "'", '"', '"', "'", '"' );
	$content = str_replace( $char_codes, $replacements, $content );

	$line = csv_to_array($content);

	$output = csv_to_table($line);

	//ticks and crosses
 	$output = preg_replace('/_tick\b/i','<span class="fa fa-check" style="color:green;"></span>',$output);
	$output = preg_replace('/_cross\b/i','<span class="fa fa-ban" style="color:firebrick;"></span>',$output);

return $output;

}

function csv_to_table($data) {

	$shortcodetag  = 'table';
	$attrtag       = 'attr';
	$tablewidget   = false;
	$scriptloadin  = Array('is_single','is_page');
	$class         = '';
	$caption       = false;
	$width         = '100%';
	$th            = true;
	$tf            = false;
	$border        = 0;
	$id            = false;
	$theme         = 'default';
	$tablesorter   = false;
	$loadcss       = true;
	$scriptinfooter= false;
	$delimiter     = ';';
	$file          = false;
	$trim          = false; /*trim; since 1.0*/
	$enclosure     = '&quot';
	$escape        = '\\';
	$nl            = '~~';
	$csvfile       = false;
	$terminator    = '\n'; /*row terminator, since 1.0*/
	$limit         = 0; /*max row to be included to table, 0 = unlimited, since 1.0*/



	if(empty($data)) return false;
	
	$max_cols 	= count(max($data));

	$r=0;
	
	/**
	* initialize inline sort, 
	* extract header sort if any, and equalize with max column number
	* @since 0.8
	*/
	if( $tablesorter ) {
		$inline_sort = Array();
		$header_sort = explode(',',$sort);
		$header_sort = array_pad($header_sort,$max_cols,NULL);
	}
	
	/**
	* tfoot position
	* @since 0.4
	*/
	$tfpos = ($tf == 'last') ? count($data) : ($th?2:1);

	$width = (stripos($width,'%') === false) ? (int)$width.'px' : (int)$width.'%';
		
	$output = '<table class="responsive">';
	$output .= $th ? '<thead>' : (($tf !== 'last') ? '' : '<tbody>');
	$output .= (!$th AND !$tf) ? '<tbody>':'';
	
	foreach($data as $k=>$cols){ $r++;
		//$cols = array_pad($cols,$max_cols,'');
		
		$output .= (($r==$tfpos) AND $tf) ? (($tf=='last')?'</tbody>':'').'<tfoot>': '';
		$output .= "\r\n".'<tr>';

		$thtd = ((($r==1) AND $th) OR (($r==$tfpos) AND $tf)) ? 'th' : 'td';
		$ai 		 = 0;
		$index       = explode('/',$ai);
		$indexnum    = ((int)$index[0])+$r;
		$indexnum    = $th ? $indexnum-2 : $indexnum-1;
		$indexnum    = ($tf AND ($tf !== 'last')) ? $indexnum-1 : $indexnum;
		$indexhead   = isset($index[1]) ? $index[1] : 'No.';
		$indexwidth  = isset($index[2]) ? (int)$index[2] : 30;
		$output .= ($ai AND ($thtd == 'td'))  ? '<'.$thtd.' style="width:'.$indexwidth.'px">'.$indexnum."</$thtd>" : ($ai ? "<$thtd>".$indexhead."</$thtd>" : '');
		
		foreach($cols as $c=>$cell) {
			/**
			* Add attribute for each cell
			* @since 0.5
			*/
			preg_match('/\['. $attrtag . ' ([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)/',$cell,$matchattr);
			$attr = isset($matchattr[1]) ? $matchattr[1] : '';
				/**
				* extract $attr value
				* @since 0.8
				* this is for inline sorting option, 
				* eg [attr sort="desc"],[attr sort="asc"] or [attr sort="none"]
				* only affect if it's TH and $tablesorter enabled
				* extract sort value and insert appropriate class value.
				*/ 
				
				if( ('th' == $thtd) AND $tablesorter ) {
					$attrs = $attr ? shortcode_parse_atts($attr) : Array();
					$attrs['sort']  =  isset($attrs['sort']) ? $attrs['sort'] : $header_sort[$c];
					$attrs['class'] =  isset($attrs['class']) ? $attrs['class'] : '';
					
					$inline_sort[$c] = $attrs['sort'];

					$attr = '';
					$sorter = in_array(strtolower($attrs['sort']),array('desc','asc')) ? '' : (!empty($attrs['sort']) ? 'false' : '');
					foreach($attrs as $katr => $vatr){
						if($katr == 'sort') {
						}
						else if(($katr == 'class')){
							$attr .= "$katr='$vatr ";
							$attr .= $sorter ? "{sorter: $sorter}":'';
							$attr .= "' ";
						}
						else {
							$attr .= "$katr='$vatr' ";
						}
					}
				}
	
			$cell     = str_replace($nl,'<br />',$cell);
			 /*trim cell content?
			 @since 1.0
			 */
			$cell  = $trim ? trim(str_replace('&nbsp;','',$cell)) : $cell;
			
			/*nl2br? only if terminator is not \n or \r*/
			if(( '\n' !== $terminator )  OR ( '\r' !== $terminator )) {
				$cell = nl2br($cell);
			}	
			/*colalign
			 @since 1.0
			 */
			if (isset($c_align[$c]) AND (stripos($attr,'text-align') === false)) {
				if(stripos($attr,'style') === false) {
				   $attr = $attr. ' style="text-align:'.$c_align[$c].'" ';
				}
				else {
					$attr = preg_replace('/style(\s+)?=(\s+)?("|\')(\s+)?/i','style=${3}text-align:'.$c_align[$c].';',$attr);
				}
			}
			/*colwidth
			 @since 1.0
			 */
			if (isset($c_width[$c]) AND (stripos($attr,'width') === false) AND ($r == 1)) {
				$c_width[$c] = (stripos($c_width[$c],'%') === false) ? (int)$c_width[$c].'px' : (int)$c_width[$c].'%';
				
				if(stripos($attr,'style') === false) {
				   $attr = $attr. ' style="width:'.$c_width[$c].'" ';
				}
				else {
					$attr = preg_replace('/style(\s+)?=(\s+)?("|\')(\s+)?/i','style=${3}width:'.$c_width[$c].';',$attr);
				}
			}
			
			$output .= "<$thtd $attr>".do_shortcode($cell)."</$thtd>\n";
		}
	
		$output .= '</tr>'."\n";
		$output .= (($r==1) AND $th) ? '</thead>'."\n".'<tbody>' : '';
		$output .= (($r==$tfpos) AND $tf) ? '</tfoot>'.((($tf==1) AND !$th) ? '<tbody>':''): '';
		
	}
	$output .= (($tf!=='last')?'</tbody>':'').'</table>';
	
	/** 
	* Build sortlist metadata and append it to the table class
	* @since 0.8
	* This intended to $tablesorter,
	* so don't bother if $tablesorter is false/disabled
	*/

	
	if( $tablesorter ) {
		$sortlist = '';
		$all_sort = array_replace($header_sort,$inline_sort);
		
		if(implode('',$all_sort)) {
			$sortlist = '{sortlist: [';
			foreach($all_sort as $k=>$v){
				$v = (($v == 'desc') ? 1 : (($v == 'asc') ? 0 : '' ));
				if($v !=='') {
					$sortlist .= '['.$k.','.$v.'], ';
				}
			}
			$sortlist .= ']}';
		}
		$output = str_replace('__sortlist__',$sortlist,$output);
	}
	return $output;
}


function csv_to_array($csv, $delimiter = ',', $enclosure = '"', $escape = '\\', $terminator = "\n", $limit = 0 ) {
$r = array();

$terminator = ($terminator == '\n') ? "\n" : $terminator;
$terminator = ($terminator == '\r') ? "\r" : $terminator;
$terminator = ($terminator == '\t') ? "\t" : $terminator;

$rows = str_getcsv($csv, $terminator,$enclosure,$escape); 
$rows = array_diff($rows,Array(''));
/*
* limit how many rows will be included?
* default 0, means ulimited.
* @since 1.0
*/
if($limit > 0) {
	$rows = array_slice($rows, 0, $limit); 
}

foreach($rows as &$row) {
	$r[] = str_getcsv($row,$delimiter);
}
return $r;
}

//create a widget title
add_shortcode( 'tc_title', 'tc_title_func' );

function tc_title_func( $atts ) {
    extract( shortcode_atts( array(
            'title' => ''
            ), $atts ) );

    if($title==='') {
    	return;
    }
    else {
		return '<h2 class="widget-title">'. $title .'</h2>';
    }
}

//Child page list (formatted)
add_shortcode('childpages', 'childpages');

function childpages() {
	global $post;
	
	//query subpages
	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'page'
	);
	$subpages = new WP_query($args);
	
	// create output
	if ($subpages->have_posts()) {
		$output = '<div class="childpages">';
		while ($subpages->have_posts()) { 
			$subpages->the_post();
			$output .= '<div class="childpreview image"><h1><a href="'.get_permalink().'">'.get_the_title().'</a></h1>
						<p class="clearfix">'.get_the_excerpt().'</p><hr></div>';
			}
		$output .= '</div>';
	}
	
	// reset the query
	wp_reset_postdata();
	
	// return something
	return $output;
}

//show child pages shortcodes
add_shortcode('child-pages', 'child_pages_list'); 

function child_pages_list() { 

	global $post; 
	
	//if pages
	if ( is_page() && $post->post_parent ) {
		$childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
	}
	else { //if ids
		$childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
	}

	if ( $childpages ) {
		$string = '<ul class="pages-list">' . $childpages . '</ul>';
	}

	return $string; 
}

//sibling pages page list
add_shortcode('sibling-pages', 'sibling_pages'); 

function sibling_pages() {

    //GET CHILD PAGES IF THERE ARE ANY
    $children = get_pages('child_of='.$post->ID);
 
    //GET PARENT PAGE IF THERE IS ONE
    $parent = $post->post_parent;
 
    //DO WE HAVE SIBLINGS?
    $siblings =  get_pages('child_of='.$parent);
 
    if( count($children) != 0) {
       $args = array(
         'title_li' => '',
         'echo' => '0',
         'child_of' => $post->ID
       );
 
    } elseif($parent != 0) {
        $args = array(
         'title_li' => '',
         'echo' => '0',
         'exclude' => $post->ID,
         'child_of' => $parent
        );
    }

    //Show pages if this page has more than one sibling 
    // and if it has children 
    if(count($siblings) > 1 && !is_null($args)) {
		return '<ul class="pages-list">' . wp_list_pages($args) . '</ul>';
	}
} 

//list pages page list
add_shortcode('list-pages', 'list_pages'); 

function list_pages() {

	$args = array(
	 'title_li' => '',
	 'echo' => '0',
	);
 
    //Show pages if this page has more than one list 
    // and if it has children 
    if(!is_null($args)) {
		return '<ul class="pages-list">' . wp_list_pages($args) . '</ul>';
	}
}
?>