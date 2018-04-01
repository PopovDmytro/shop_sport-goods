<?php

/**
 * Class XPingator
 *
 * pingator plugin
 *
 * @author   Fatal
 * @access   public
 * @package  XPingator.class.php
 * @created  Mon Sep 10 12:53:03 EEST 2007
 */
class XPingator extends XPlugin 
{
	var $pingators;
	var $urls;

	/**
	 * Constructor of XPingator
	 *
	 * @access  public
	 */
	function XPingator($aURLs) 
	{
		$this->urls = $aURLs;
		parent::XPlugin('pingator');
		
		$this->pingators = array(
			array('host'=>'rpc.pingomatic.com', 		'file'=>'/'),
			array('host'=>'api.moreover.com', 			'file'=>'/ping'),
			array('host'=>'blogsearch.google.com', 		'file'=>'/ping/RPC2'),
			array('host'=>'ping.feedburner.com', 		'file'=>'/'),
			array('host'=>'ping.syndic8.com', 			'file'=>'/xmlrpc.php'),
			//array('host'=>'ping.weblogalot.com', 		'file'=>'/rpc.php'),
			array('host'=>'rpc.blogbuzzmachine.com', 	'file'=>'/RPC2'),
			array('host'=>'rpc.blogrolling.com', 		'file'=>'/pinger/'),
			array('host'=>'rpc.icerocket.com:10080', 	'file'=>'/'),
			array('host'=>'rpc.technorati.com', 		'file'=>'/rpc/ping'),
			array('host'=>'rpc.weblogs.com', 			'file'=>'/RPC2'),
			array('host'=>'www.popdex.com', 			'file'=>'/addsite.php'),
			array('host'=>'www.wasalive.com', 			'file'=>'/ping/'),
			array('host'=>'www.xianguo.com', 			'file'=>'/xmlrpc/ping.php'),
			array('host'=>'www.feedsky.com', 			'file'=>'/api/RPC2'),
			array('host'=>'ping.blogs.yandex.ru', 		'file'=>'/RPC2'),
//			array('host'=>'blogsearch.google.at', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.cl', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.co.cr', 	'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.co.hu', 	'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.co.it', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.co.nz', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.co.th', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.co.uk', 	'file'=>''),
//			array('host'=>'blogsearch.google.com.au', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.com.mx', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.com.sa', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.com.sg', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.com.tr', 	'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.com.tw', 	'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.com.ua', 	'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.fi', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.fr', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.gr', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.hr', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.ie', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.in', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.it', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.lt', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.pl', 		'file'=>'/ping/RPC2'),			
//			array('host'=>'blogsearch.google.pt', 		'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.ro', 		'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.ru', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.se', 		'file'=>'/ping/RPC2'),
//			array('host'=>'blogsearch.google.sk', 		'file'=>'/ping/RPC2'),
			array('host'=>'blogsearch.google.us', 		'file'=>'/ping/RPC2')
			
			);
			
		foreach ($this->urls as $key=>$value) {
			$this->pingProcess($value);
		}
	}
	
	/**
	 * pingProcess
	 *
	 * ping process
	 *
	 * @author  Fatal
	 * @class   XPingator
	 * @access  public
	 * @return  void
	 */
	function pingProcess($aURL)
	{
		$request = '<?xml version="1.0"?>
					<methodCall>
						<methodName>weblogUpdates.ping</methodName> 
						<params>
							<param><value>'.$aURL.'</value></param>
							<param><value>http://'.$aURL.'</value></param> 
						</params>
					</methodCall>';
		
		$requestLength = strlen($request);
	
		$mcurl = curl_multi_init();
		$curl = array();
		$content = array();
		$size = sizeof($this->pingators);

		for ($i=0; $i<$size; $i++) {        					
			
			$header = array();
			$header[] = "Host: ".$this->pingators[$i]['host'];
			$header[] = "Content-type: text/xml";
			$header[] = "Content-length: ".$requestLength . "\r\n";
			$header[] = $request;

			$curl[$i] = curl_init($this->pingators[$i]['host'].$this->pingators[$i]['file']);
			curl_setopt($curl[$i],	CURLOPT_HTTPHEADER, 		$header );
			curl_setopt($curl[$i],	CURLOPT_CUSTOMREQUEST, 		'POST' );
			
			curl_setopt($curl[$i], 	CURLOPT_RETURNTRANSFER, 	1);
			curl_setopt($curl[$i], 	CURLOPT_FOLLOWLOCATION, 	1);
			curl_setopt($curl[$i],	CURLOPT_TIMEOUT, 			20);
			curl_setopt($curl[$i], 	CURLOPT_CONNECTTIMEOUT, 	5);

			curl_multi_add_handle($mcurl, $curl[$i]);
		}
		
		do { $n = curl_multi_exec($mcurl, $active); } while ($active);
		
		for ($i=0; $i<$size; $i++) {
			$content[$i] = curl_multi_getcontent($curl[$i]);
			curl_multi_remove_handle($mcurl, $curl[$i]);
			curl_close($curl[$i]);
		}
/*		$fp = fopen(SITE_ROOT.'pinglog.txt', 'a+');
		if (!$fp) {
			return false;
		}*/
		/*fwrite($fp, date('Y-m-d H:i:s').' '.$aURL." ok\n");
		fclose($fp);*/
//		Sys::dump($content);
	}
}
?>