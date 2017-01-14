<?php  if (! defined('BASEPATH')) {
     exit('No direct script access allowed');
 }

$autoload['packages']  = array();
$autoload['libraries'] = array('database','form_validation','sms');
$autoload['helper']    = array('funs','url','form','language','cookie','date','uisite','data','sms','api','gen');
$autoload['config']    = array();
$autoload['language']  = array('common');
$autoload['model']     = array();

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */
