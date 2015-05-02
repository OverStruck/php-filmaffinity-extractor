<?php
	/**
	 * @author OverStruck (Juanix.net, OverStruck.com, github.com/overstruck)
	 * @version 0.1
	 * @todo error checking?
	 * @license http://opensource.org/licenses/MIT
	 * @example example-script.php simple example no how to use this class
	 * Basically just pass the id or url of the film you want and either
	 * output the result directly and use in your page with JS for example
	 * or return it and parse in php
	 * 
	 * Your shouldn't have to use a try catch block though
	 * You should check your user is giving you a valid source id or url
	 */
	$filmID = $_GET['id'];
	
	include 'FilmaffinityExtractor.class.php';
	
	$extractor = new FilmaffinityExtractor();
	
	try {
		$extractor->get($filmID);
	}
	catch (Exception $e) {
		//this should probably be a JSON encoded string
		echo 'Caught exception: ', $e->getMessage();
	}
?>