<?php
/**
 * Contains API methods for PlayJoom Wikipedia.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Contains API methods for PlayJoom Wikipedia
 *
 * @package     PlayJoom.Site
 * @subpackage  com_playjoom.apis
 */
abstract class PlayJoomWikiHelper
{
    public function __construct($config = array()) {
    	
		ini_set('max_execution_time',3600);
    
        // Initializing variable for status-messages
        $status = null;

        // Initializing variable for authentification cookie data
        $mwcookies = array();

		parent::__construct($config);
	}    
    
    
    function PostRequest($url, $referer, $_data, $addheader=null) {
    	
    	$data = null;
        
    	while(list($n,$v) = each($_data)) {
        	$data.= '&'.$n.'='.rawurlencode($v);
        }
        
        $data = substr($data, 1);
 
        $url = parse_url($url);
        
        if($url['scheme'] != 'http') die("Only HTTP-Request are supported");
        
        $host = $url['host'];
        $path = $url['path'];
 
        $fp = fsockopen($host, 80);
 
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Referer: $referer\r\n");
        fputs($fp, "User-Agent: BotTool (http://testhh.pytalhost.com)\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        
        if($addheader != '') {
        	fputs($fp, $addheader);
        }
        
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = null;
 
        while(!feof($fp)) {
        	$result .= fgets($fp,128);
        }
        
        fclose($fp);
 
        $result = explode("\r\n\r\n",$result,2);
 
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
 
        return array($header, $content);
    }

    /**
     * void parsecookies(string content)
     * parsing all http-cookies
     */

    function parsecookies($content) {
    	
    	preg_match_all("^Set-Cookie: (.*?)=(.*?);^", $content, $cookies);

        for($x = 0; $x < count($cookies[1]); $x++) {
        	
        	PlayJoomWikiHelper::savecookie($cookies[1][$x], $cookies[2][$x]);
        }

        PlayJoomWikiHelper::status("All cookies updated.");
    }
    
    function savecookie($cookie_name, $cookie_value) {
    	
    	global $mwcookies;
        $mwcookies[$cookie_name] = $cookie_value;
    }
    
    function getcookies() {
    	
    	global $mwcookies;
        $return = 'Cookie: ';

        foreach($mwcookies as $k => $v) {
        	
        	$return .= "$k=$v;";
        }

        return $return;
    }

    function status($text) {
    	
    	global $status;
        $status .= "<font face=\"Courier New\" size=\"2\">[".date("Y-m-d H:i:s")."] $text</font><br>";
    }
    
    /**
     * array getcontent(string title, string wikilang)
     * fetching content from an article
     */
    function getcontent($About, $wikilang) {
    	
    	$title = base64_decode($About);
    	
    	PlayJoomWikiHelper::status("Fetching page $title");

        $title = preg_replace("/ /","_",$title);

        $data = array(
              'action' => 'parse',
              'prop' => 'text',
              'pageid' => $title,
              'format' => 'php'
        );

        list($header, $content) = PlayJoomWikiHelper::PostRequest('http://'.$wikilang.'.wikipedia.org/w/api.php','http://'.$_SERVER['REMOTE_ADDR'],$data);
       
        PlayJoomWikiHelper::parsecookies($header);

        $content = unserialize($content);
               
        return $content;
    }

    function CheckWikiContent($title, $wikilang) {
    	
    	$title = preg_replace("/ /","_",$title);

        $data = array(
              'action' => 'query',
              'prop' => 'info',
              'rvprop' => 'content',
              'titles' => $title,
              'format' => 'php'
        );

        list($header, $content) = PlayJoomWikiHelper::PostRequest('http://'.$wikilang.'.wikipedia.org/w/api.php','http://'.$_SERVER['REMOTE_ADDR'],$data);

        PlayJoomWikiHelper::parsecookies($header);

        $content = unserialize($content);

        $info = $content['query']['pages'];

        foreach($info as $k=>$v) {
        	$pageid = $k;
        }

        $pagedata = array();

        $pagedata['pageid'] = $pageid;
        $pagedata['ns'] = $info[$pageid]['ns'];
        $pagedata['title'] = $info[$pageid]['title'];
        $pagedata['missing'] = (isset($info[$pageid]['missing'])) ? (int) 1 : (int) 0;

        return $pagedata;
    } 
}