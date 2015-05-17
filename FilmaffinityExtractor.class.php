<?php
	/**
	 * @author OverStruck (Juanix.net, OverStruck.com, github.com/overstruck)
	 * @version 0.2
	 * @todo error checking?
	 * @license http://opensource.org/licenses/MIT
	 */
	class FilmaffinityExtractor {
		private $url;
		
		/**
		 * Sets languaje URL. Defaults to spanish
		 * TODO: check $lang param for correct values
		 */
		function __construct($lang = 'es') {
			$this->url = "http://www.filmaffinity.com/$lang/film{ID}.html";
		}
		
		/**
		 * Main function. Gets contents from filmaffinity.com
		 * Parses contents and returns or outputs result
		 * TODO: error checking in case web request fails
		 * @param type $return 
		 * @return object $response JSON encoded film info
		 */
		private function init($return) {
			$opts     = array(
				'http' => array(
					'header' => "User-Agent:$_SERVER[HTTP_USER_AGENT]"
				)
			);
			$context  = stream_context_create($opts);
			$web_page = file_get_contents($this->url, FALSE, $context);
			if ($web_page && !empty($web_page)) {
				$response = $this->parse_website($web_page);
			} else {
				$response = array(
					'error' => true,
					'msg' => 'ID invalido o Filmaffinity esta OFFLINE... Nose'
				);
				$response = json_encode($response);
			}
			
			if ($return) {
				return json_decode($response);
			}
			echo $response;
		}
		
		/**
		 * Parses html content from filmaffinity to extract infor
		 * @param string $website_content filmaffinity html source code
		 * @return object the extracted information
		 */
		private function parse_website($website_content) {
			$movie_info          = $this->get_movie_info($website_content);
			$movie_detailed_info = $this->get_detailed_info($movie_info);
			$json_responde       = json_encode($movie_detailed_info);
			
			return $json_responde;
		}
		
		/**
		 * Extracts general movie information from the filmaffinity html src code
		 * @param string $source filmaffinity html src code
		 * @return array containing matched information
		 */
		private function get_movie_info($source) {
			$movie_info_regexp = '/<dl class="movie-info">(.*?)<\/dl>/is';
			preg_match($movie_info_regexp, $source, $matches);
			return $matches[1];
		}
		
		/**
		 * Extracts the details of the film from filmaffinity
		 * @param string $source filmaffinity html src code
		 * @return array containing film details
		 */
		private function get_detailed_info($source) {
			$movie_detailed_info_regexp = '/(<dt>|<dd>)(.*?)(<\/dt>|<\/dd>)/is';
			preg_match_all($movie_detailed_info_regexp, $source, $matches);
			
			return $this->generate_detailed_info_array($matches[2]);
		}
		
		/**
		 * Generates array with detaled film information
		 * It also cleans up the result stripping unwanted html code
		 * @param array $source detailed film info
		 * @return array cleaned and formated film info
		 */
		private function generate_detailed_info_array($source) {
			$length    = count($source) - 1;
			$new_array = array();
			for ($i = 0; $i < $length; $i++) {
				$key             = $this->remove_accents($source[$i]);
				$value           = $this->clean_string($source[++$i]);
				$new_array[$key] = $value;
			}
			
			return $new_array;
		}
		
		/**
		 * Removes html tags and the words "aka" and (FILMAFFINITY)
		 * @param type $string 
		 * @return type
		 */
		private function clean_string($string) {
			//$string = $this->remove_accents($string);
			$string = strip_tags(trim($string));
			$string = preg_replace('/(aka|\(FILMAFFINITY\))/is', '', $string);
			return $string;
		}
		
		/**
		 * Removes spanish accents from $string
		 * This is a wordpress implementation
		 * @param string $string the string to remove accents from
		 * @return string $string the cleaned string
		 */
		private function remove_accents($string) {
			if (!preg_match('/[\x80-\xff]/', $string))
				return $string;
			
			$chars = array(
				// Decompositions for Latin-1 Supplement
				chr(195) . chr(128) => 'A',
				chr(195) . chr(129) => 'A',
				chr(195) . chr(130) => 'A',
				chr(195) . chr(131) => 'A',
				chr(195) . chr(132) => 'A',
				chr(195) . chr(133) => 'A',
				chr(195) . chr(135) => 'C',
				chr(195) . chr(136) => 'E',
				chr(195) . chr(137) => 'E',
				chr(195) . chr(138) => 'E',
				chr(195) . chr(139) => 'E',
				chr(195) . chr(140) => 'I',
				chr(195) . chr(141) => 'I',
				chr(195) . chr(142) => 'I',
				chr(195) . chr(143) => 'I',
				chr(195) . chr(145) => 'N',
				chr(195) . chr(146) => 'O',
				chr(195) . chr(147) => 'O',
				chr(195) . chr(148) => 'O',
				chr(195) . chr(149) => 'O',
				chr(195) . chr(150) => 'O',
				chr(195) . chr(153) => 'U',
				chr(195) . chr(154) => 'U',
				chr(195) . chr(155) => 'U',
				chr(195) . chr(156) => 'U',
				chr(195) . chr(157) => 'Y',
				chr(195) . chr(159) => 's',
				chr(195) . chr(160) => 'a',
				chr(195) . chr(161) => 'a',
				chr(195) . chr(162) => 'a',
				chr(195) . chr(163) => 'a',
				chr(195) . chr(164) => 'a',
				chr(195) . chr(165) => 'a',
				chr(195) . chr(167) => 'c',
				chr(195) . chr(168) => 'e',
				chr(195) . chr(169) => 'e',
				chr(195) . chr(170) => 'e',
				chr(195) . chr(171) => 'e',
				chr(195) . chr(172) => 'i',
				chr(195) . chr(173) => 'i',
				chr(195) . chr(174) => 'i',
				chr(195) . chr(175) => 'i',
				chr(195) . chr(177) => 'n',
				chr(195) . chr(178) => 'o',
				chr(195) . chr(179) => 'o',
				chr(195) . chr(180) => 'o',
				chr(195) . chr(181) => 'o',
				chr(195) . chr(182) => 'o',
				chr(195) . chr(182) => 'o',
				chr(195) . chr(185) => 'u',
				chr(195) . chr(186) => 'u',
				chr(195) . chr(187) => 'u',
				chr(195) . chr(188) => 'u',
				chr(195) . chr(189) => 'y',
				chr(195) . chr(191) => 'y',
				// Decompositions for Latin Extended-A
				chr(196) . chr(128) => 'A',
				chr(196) . chr(129) => 'a',
				chr(196) . chr(130) => 'A',
				chr(196) . chr(131) => 'a',
				chr(196) . chr(132) => 'A',
				chr(196) . chr(133) => 'a',
				chr(196) . chr(134) => 'C',
				chr(196) . chr(135) => 'c',
				chr(196) . chr(136) => 'C',
				chr(196) . chr(137) => 'c',
				chr(196) . chr(138) => 'C',
				chr(196) . chr(139) => 'c',
				chr(196) . chr(140) => 'C',
				chr(196) . chr(141) => 'c',
				chr(196) . chr(142) => 'D',
				chr(196) . chr(143) => 'd',
				chr(196) . chr(144) => 'D',
				chr(196) . chr(145) => 'd',
				chr(196) . chr(146) => 'E',
				chr(196) . chr(147) => 'e',
				chr(196) . chr(148) => 'E',
				chr(196) . chr(149) => 'e',
				chr(196) . chr(150) => 'E',
				chr(196) . chr(151) => 'e',
				chr(196) . chr(152) => 'E',
				chr(196) . chr(153) => 'e',
				chr(196) . chr(154) => 'E',
				chr(196) . chr(155) => 'e',
				chr(196) . chr(156) => 'G',
				chr(196) . chr(157) => 'g',
				chr(196) . chr(158) => 'G',
				chr(196) . chr(159) => 'g',
				chr(196) . chr(160) => 'G',
				chr(196) . chr(161) => 'g',
				chr(196) . chr(162) => 'G',
				chr(196) . chr(163) => 'g',
				chr(196) . chr(164) => 'H',
				chr(196) . chr(165) => 'h',
				chr(196) . chr(166) => 'H',
				chr(196) . chr(167) => 'h',
				chr(196) . chr(168) => 'I',
				chr(196) . chr(169) => 'i',
				chr(196) . chr(170) => 'I',
				chr(196) . chr(171) => 'i',
				chr(196) . chr(172) => 'I',
				chr(196) . chr(173) => 'i',
				chr(196) . chr(174) => 'I',
				chr(196) . chr(175) => 'i',
				chr(196) . chr(176) => 'I',
				chr(196) . chr(177) => 'i',
				chr(196) . chr(178) => 'IJ',
				chr(196) . chr(179) => 'ij',
				chr(196) . chr(180) => 'J',
				chr(196) . chr(181) => 'j',
				chr(196) . chr(182) => 'K',
				chr(196) . chr(183) => 'k',
				chr(196) . chr(184) => 'k',
				chr(196) . chr(185) => 'L',
				chr(196) . chr(186) => 'l',
				chr(196) . chr(187) => 'L',
				chr(196) . chr(188) => 'l',
				chr(196) . chr(189) => 'L',
				chr(196) . chr(190) => 'l',
				chr(196) . chr(191) => 'L',
				chr(197) . chr(128) => 'l',
				chr(197) . chr(129) => 'L',
				chr(197) . chr(130) => 'l',
				chr(197) . chr(131) => 'N',
				chr(197) . chr(132) => 'n',
				chr(197) . chr(133) => 'N',
				chr(197) . chr(134) => 'n',
				chr(197) . chr(135) => 'N',
				chr(197) . chr(136) => 'n',
				chr(197) . chr(137) => 'N',
				chr(197) . chr(138) => 'n',
				chr(197) . chr(139) => 'N',
				chr(197) . chr(140) => 'O',
				chr(197) . chr(141) => 'o',
				chr(197) . chr(142) => 'O',
				chr(197) . chr(143) => 'o',
				chr(197) . chr(144) => 'O',
				chr(197) . chr(145) => 'o',
				chr(197) . chr(146) => 'OE',
				chr(197) . chr(147) => 'oe',
				chr(197) . chr(148) => 'R',
				chr(197) . chr(149) => 'r',
				chr(197) . chr(150) => 'R',
				chr(197) . chr(151) => 'r',
				chr(197) . chr(152) => 'R',
				chr(197) . chr(153) => 'r',
				chr(197) . chr(154) => 'S',
				chr(197) . chr(155) => 's',
				chr(197) . chr(156) => 'S',
				chr(197) . chr(157) => 's',
				chr(197) . chr(158) => 'S',
				chr(197) . chr(159) => 's',
				chr(197) . chr(160) => 'S',
				chr(197) . chr(161) => 's',
				chr(197) . chr(162) => 'T',
				chr(197) . chr(163) => 't',
				chr(197) . chr(164) => 'T',
				chr(197) . chr(165) => 't',
				chr(197) . chr(166) => 'T',
				chr(197) . chr(167) => 't',
				chr(197) . chr(168) => 'U',
				chr(197) . chr(169) => 'u',
				chr(197) . chr(170) => 'U',
				chr(197) . chr(171) => 'u',
				chr(197) . chr(172) => 'U',
				chr(197) . chr(173) => 'u',
				chr(197) . chr(174) => 'U',
				chr(197) . chr(175) => 'u',
				chr(197) . chr(176) => 'U',
				chr(197) . chr(177) => 'u',
				chr(197) . chr(178) => 'U',
				chr(197) . chr(179) => 'u',
				chr(197) . chr(180) => 'W',
				chr(197) . chr(181) => 'w',
				chr(197) . chr(182) => 'Y',
				chr(197) . chr(183) => 'y',
				chr(197) . chr(184) => 'Y',
				chr(197) . chr(185) => 'Z',
				chr(197) . chr(186) => 'z',
				chr(197) . chr(187) => 'Z',
				chr(197) . chr(188) => 'z',
				chr(197) . chr(189) => 'Z',
				chr(197) . chr(190) => 'z',
				chr(197) . chr(191) => 's'
			);
			
			$string = strtr($string, $chars);
			
			return $string;
		}
		
		/**
		 * General getter, gets the information given an ID or a Full URL
		 * @param string $source the source ID or URL from Filmaffinity.com
		 * @param bool $return return the result?
		 */
		public function get($source = null, $return = false) {
			if ($source == null) {
				throw new Exception('Empty Source Parameter');
			}
			
			$film_id = $source;
			$is_url  = '/(https?):\/\/www\.filmaffinity\.com\/(es|en)\/film(\d+)\.html/i';
			if (preg_match($is_url, $source, $matches)) {
				$film_id = $matches[3];
			}
			
			$this->url = str_replace('{ID}', $film_id, $this->url);
			$this->init($return);
		}
	}
?>