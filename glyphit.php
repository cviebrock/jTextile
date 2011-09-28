<?php

@define('GLYPHIT_HAS_UNICODE', @preg_match('/\pL/u', 'a')); // Detect if Unicode is compiled into PCRE

class GlyphIt
{

	private		$glyph_search, $glyph_replace;
	public		$glyphs;

// -------------------------------------------------------------
	public function __construct()
	{
		
		$this->glyphs = array(
			'apos' 		=> '&rsquo;',				// &apos; ?  But doesn't work in IE?
			'lsquo'		=> '&lsquo;',
			'rsquo'		=> '&rsquo;',
			'ldquo'		=> '&ldquo;',
			'rdquo'		=> '&rdquo;',
			'hellip'	=> '&hellip;',
			'emdash'	=> '&emdash;',
			'endash'	=> '&endash;',
			'times'		=> '&times;',
			'trade'		=> '&trade;',
			'reg'			=> '&reg;',
			'copy'		=> '&copy;',
			'deg'			=> '&deg;',
			'plusmn'	=> '&plusmn;',
			'frac14'	=> '&frac14;',
			'frac12'	=> '&frac12;',
			'frac34'	=> '&frac34;',
		);

		if (GLYPHIT_HAS_UNICODE) {
			$acr = '\p{Lu}\p{Nd}';
			$abr = '\p{Lu}';
			$nab = '\p{Ll}';
			$wrd = '(?:\p{L}|\p{M}|\p{N}|\p{Pc})';
			$mod = 'u'; # Make sure to mark the unicode patterns as such, Some servers seem to need this.
		} else {
			$acr = 'A-Z0-9';
			$abr = 'A-Z';
			$nab = 'a-z';
			$wrd = '\w';
			$mod = '';
		}
		$pnc = '[[:punct:]]';

		$this->glyph_search = array(
			'/('.$wrd.')\'('.$wrd.')/'.$mod,        // I'm an apostrophe
			'/(\s)\'(\d+'.$wrd.'?)\b(?![.]?['.$wrd.']*?\')/'.$mod,	// back in '88/the '90s but not in his '90s', '1', '1.' '10m' or '5.png'
			'/(\S)\'(?=\s|'.$pnc.'|<|$)/',          // single closing
			'/\'/',                                 // single opening
			'/(\S)\"(?=\s|'.$pnc.'|<|$)/',          // double closing
			'/"/',                                  // double opening
			'/([^.]?)\s*\.{3}\s*/',                 // ellipsis
			'/(\s?)-{2,3}(\s?)/',                   // em dash
			'/(\d+)-(\d+)/',                        // en dash for number ranges
			'/\s-(?:\s|$)/',                        // en dash
			'/(\d+)( ?)x( ?)(?=\d+)/',              // dimension sign
			'/(\b ?|\s|^)[([]TM[])]/i',             // trademark
			'/(\b ?|\s|^)[([]R[])]/i',              // registered
			'/(\b ?|\s|^)[([]C[])]/i',              // copyright
			'/[([]1\/4[])]/',                       // 1/4
			'/[([]1\/2[])]/',                       // 1/2
			'/[([]3\/4[])]/',                       // 3/4
			'/[([]o[])]/',                          // degrees -- that's a small 'oh'
			'/[([]\+\/-[])]/',                      // plus minus
		);

		$this->glyph_replace = array(
			'$1'.$this->glyphs['apos'].'$2',				// I'm an apostrophe             
			'$1'.$this->glyphs['apos'].'$2',				// back in '88                   
			'$1'.$this->glyphs['rsquo'],						// single closing                      
			$this->glyphs['lsquo'],									// single opening                      
			'$1'.$this->glyphs['rdquo'],						// double closing                      
			$this->glyphs['ldquo'],									// double opening                      
			'$1 '.$this->glyphs['hellip'].' ',			// ellipsis                  
			'$1'.$this->glyphs['emdash'].'$2',			// em dash                 
			'$1'.$this->glyphs['endash'].'$2',			// en dash for number ranges            
			' '.$this->glyphs['endash'].' ',				// en dash                 
			'$1$2'.$this->glyphs['times'].'$3',			// dimension sign              
			'$1'.$this->glyphs['trade'],						// trademark                   
			'$1'.$this->glyphs['reg'],							// registered                     
			'$1'.$this->glyphs['copy'],							// copyright                    
			$this->glyphs['frac14'],								// 1/4                      
			$this->glyphs['frac12'],								// 1/2                   
			$this->glyphs['frac34'],								// 3/4                            
			$this->glyphs['deg'],										// degrees                     
			$this->glyphs['plusmn'],								// plus minus                 
		);

	}




	public function fix($text, $enc_entities = true)
	{
		$newstring = '';
		$tag = '>';
		if (preg_match_all('/([<>])/', $text, $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER)) {
			$start = 0;
			foreach($matches[0] as $m) {
				$tag = $m[0];
				$end = $m[1];
				$string = substr($text,$start,$end-$start);	// match up to tag
				if ($string!=='') {													// if we have matching text ...
					if ($tag=='>') {													// ... and it's inside a tag, ...
						$newstring .= $string . $tag;						// ... add it to new string
					} else {																	// else convert it
						$newstring .= $this->replaceGlyphs($string, $enc_entities). $tag;
					}
					$start = $end+1;													// move pointer
				}
			}
		}
		$remainder = substr($text,$start);
		if ($tag=='>') {
			$remainder = $this->replaceGlyphs($remainder, $enc_entities);
		}
		$newstring .= $remainder;
		return $newstring;
	}
	
	private function replaceGlyphs($text, $enc_entities = true)
	{
		$text = preg_replace($this->glyph_search, $this->glyph_replace, $text);
		if ($enc_entities) {
			$text = htmlentities($text, ENT_QUOTES, null, false);
		}
		return $text;
	}
	
	
}