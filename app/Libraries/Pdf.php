<?php
namespace App\Libraries;
use TCPDF;


class Pdf extends TCPDF {
	
	function __construct(){
		parent::__construct();
	}
	
}