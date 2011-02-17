/*
 *	jQuery Textile plug-in
 *
 *	Based on classTextile.php, part of the Textpattern CMS system
 *	http://textpattern.com
 *
 *	Ported for jQuery by Colin Viebrock
 *
 *	This plug-in only handles the glyph conversion part of Textile.
 */

(function( $ ){
  $.fn.textile = function( options ) {

    var opts = {
			'txt_quote_single_open'			: '\u2018',
			'txt_quote_single_close'		: '\u2019',
			'txt_quote_double_open'			: '\u201C',
			'txt_quote_double_close'		: '\u201D',
			'txt_apostrophe'						: '\u2019',
			'txt_prime'									: '\u2032',
			'txt_prime_double'					: '\u2033',
			'txt_ellipsis'							: '\u2026',
			'txt_emdash'								: '\u2014',
			'txt_endash'								: '\u2013',
			'txt_dimension'							: '\u00d7',
			'txt_trademark'							: '\u2122',
			'txt_registered'						: '\u00ae',
			'txt_copyright'							: '\u00a9',
			'txt_half'									: '\u00be',
			'txt_quarter'								: '\u00bc',
			'txt_threequarters'					: '\u00be',
			'txt_degrees'								: '\u00b0',
			'txt_plusminus'							: '\u00b1',
		}

    if ( options ) { 
      $.extend( opts, options );
    }



		var pnc = '[[:punct:]]';

		var re_search = [
			new RegExp( '(\\w)\'(\\w)',														'g' ),  // I'm an apostrophe
			new RegExp( '(\\s)\'(\\d+\\w?)\\b(?![.]?[\\w]*?\')',	'g' ),  // back in '88/the '90s but not in his '90s', '1', '1.' '10m' or '5.png'
			new RegExp( '(\\S)\'(?=\\s|'+pnc+'|<|$)',							'g' ),  // single closing
			new RegExp( '\'',																			'g' ),  // single opening
			new RegExp( '(\\S)\"(?=\\s|'+pnc+'|<|$)',							'g' ),  // double closing
			new RegExp( '"',																			'g' ),  // double opening
			new RegExp( '([^.]?)\\.{3}',													'g' ),  // ellipsis
			new RegExp( '(\\s?)--(\\s?)',													'g' ),  // em dash
			new RegExp( '\\s-(?:\\s|$)',													'g' ),  // en dash
			new RegExp( '(\\d+)( ?)x( ?)(?=\\d+)',								'g' ),  // dimension sign
			new RegExp( '(\\b ?|\\s|^)[\\(\\[]TM[\\]\)]',					'gi' ),  // trademark
      new RegExp( '(\\b ?|\\s|^)[\\(\\[]R[\\]\\)]',			 		'gi' ),  // registered
      new RegExp( '(\\b ?|\\s|^)[\\(\\[]C[\\]\\)]',			 		'gi' ),  // copyright
			new RegExp( '[\\(\\[]1\/4[\\]\\)]',										'g' ),  // 1/4
			new RegExp( '[\\(\\[]1\/2[\\]\\)]',										'g' ),  // 1/2
			new RegExp( '[\\(\\[]3\/4[\\]\\)]',										'g' ),  // 3/4
			new RegExp( '[\\(\\[]o[\\]\\)]',											'g' ),  // degrees -- that's a small 'oh'
			new RegExp( '[\\(\\[]\+\/-[\\]\\)]',									'g' ),  // plus minus
		];



		var re_replace = [
			'$1'+opts.txt_apostrophe+'$2',              // I'm an apostrophe     
			'$1'+opts.txt_apostrophe+'$2',              // back in '88           
			'$1'+opts.txt_quote_single_close,           // single closing        
			opts.txt_quote_single_open,                 // single opening        
			'$1'+opts.txt_quote_double_close,           // double closing        
			opts.txt_quote_double_open,                 // double opening        
			'$1'+opts.txt_ellipsis,                     // ellipsis              
			'$1'+opts.txt_emdash+'$2',                  // em dash               
			' '+opts.txt_endash+' ',                    // en dash               
			'$1$2'+opts.txt_dimension+'$3',             // dimension sign        
			'$1'+opts.txt_trademark,                    // trademark            
			'$1'+opts.txt_registered,                   // registered           
			'$1'+opts.txt_copyright,                    // copyright            
			opts.txt_quarter,                           // 1/4                   
			opts.txt_half,                              // 1/2                   
			opts.txt_threequarters,                     // 3/4                   
			opts.txt_degrees,                           // degrees               
			opts.txt_plusminus,                         // plus minus            
	 ];                                                                      



    return this.each(function() {
			traverse(0, this);
		});
		
		function traverse(i, node) {
			if (node.nodeType==3) {
				var text = textile_it(node.nodeValue);
				node.nodeValue = text;
			} else {
				$(node).contents().each( traverse );
			}
		}
		
		function textile_it(text) {
			var l = re_search.length;
			for (var i = 0; i<l; i++ ) {
				text = text.replace( re_search[i], re_replace[i] );
			}
			return text;
		}
		
		
		
  };
})( jQuery );
