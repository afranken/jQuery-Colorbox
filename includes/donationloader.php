<?php
/**
 * @package Techotronic
 * @subpackage Donation Loader
 *
 * @version 1.0
 * @author Arne Franken
 *
 * Object that handles Ajax to Xml RPC calls
 */
if (!defined('DONATIONLOADER_XMLRPC_URL')) {
  //define('DONATIONLOADER_XMLRPC_URL', 'http://xmlrpc.techotronic.de/');
  define('DONATIONLOADER_XMLRPC_URL', 'http://www.wpthemetest.de/wordpress/xmlrpc.php');
}
if (!defined('DONATIONLOADER_CACHETIME')) {
  //cachetime in seconds
  define('DONATIONLOADER_CACHETIME', 600);
}
//has to have pluginname-prefix because Class names can't be used twice...
class JQueryColorboxDonationLoader {
  var $donationLoaderUserAgent = JQUERYCOLORBOX_USERAGENT;
  var $donationLoaderPluginName = JQUERYCOLORBOX_PLUGIN_NAME;
  var $donationLoaderPluginUrl = JQUERYCOLORBOX_PLUGIN_URL;

  /**
   * Constructor
   *
   * @since 1.0
   * @access public
   * @access static
   * @author Arne Franken
   *
   * @return void
   */
  function JQueryColorboxDonationLoader() {
    //not logged in users can trigger the action
    //add_action( 'wp_ajax_nopriv_action', 'methodName' );
    //only logged in users can trigger the action
    //add_action( 'wp_ajax_action', array($this, 'methodName') );
    add_action( 'wp_ajax_load-topDonations', array($this, 'getTopDonations') );
    add_action( 'wp_ajax_load-latestDonations', array($this, 'getLatestDonations') );
  }

  // DonationLoader()

  /**
   * XML RPC Test, not used
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
//  function getTest() {
//    $this->doGetDonations(xmlrpc_encode_request('demo.sayHello','doesntMatter'));
//  }

  // getTest()

  /**
   * Get top donations
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function getTopDonations() {
  function getTopDonations() {
    $this->getAndReturnDonations('manageDonations.getTopDonations','top');
  }

  // getTopDonations()

  /**
   * Get latest donations
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function getLatestDonations() {
  function getLatestDonations() {
    $this->getAndReturnDonations('manageDonations.getLatestDonations','latest');
  }

  // getLatestDonations()

  /**
   * Generic donation getter.
   * Wrap the XML RPC call and return the value to the Ajax call
   * Caches the serialized response for $cacheTime seconds.
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @param String $remoteProcedureCall RPC method name
   * @param String $identifier cache-identifier for the request
   * 
   * @return void
   */
  function getAndReturnDonations($remoteProcedureCall,$identifier) {
    // get the submitted parameters
    $pluginName = $_POST['pluginName'];

    $key = $identifier . '-' . $pluginName;

    //try to get response from DB cache
    $response = get_transient($key);
    if ( false == $response ) {
      // response not found in DB cache, generate response
      $xmlRpcRequest = xmlrpc_encode_request($remoteProcedureCall,$pluginName);
    
      $response = $this->getRemoteXmlRpcContent(DONATIONLOADER_XMLRPC_URL,$this->donationLoaderUserAgent,$xmlRpcRequest);

      set_transient($key, serialize($response), DONATIONLOADER_CACHETIME);
    } else {
      $response = unserialize($response);
    }

    // header content-type must match the one used in the jQuery.post call.
    //header( "content-type: application/json" );
    header( "content-type: text/html" );

    // echo instead of return, $response is given back to the Ajax call.
    echo $response;
    // IMPORTANT: don't forget to "exit"
    exit;
  }

  // getDonations()

  /**
   * Build JavaScript array for loading donations.
   * Also registers JavaScript file.
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function registerDonationJavaScript() {
  function registerDonationJavaScript() {
    $javaScriptArray = array('ajaxurl' => admin_url( 'admin-ajax.php' ),
    'pluginName' => $this->donationLoaderPluginName);

    wp_register_script('donation', $this->donationLoaderPluginUrl . '/js/donation-min.js', array('jquery'));
    wp_enqueue_script('donation');
    wp_localize_script('donation', 'Donation', $javaScriptArray);
  }

  // registerDonationJavaScript()

  /**
   * Read XML from a remote url
   * 
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @param string $url
   * @param string $userAgent
   * @param string $xmlRpcRequest
   *
   * @return the response or FALSE if there was an error
   */
  //private function getRemoteXmlRpcContent($url) {
  function getRemoteXmlRpcContent($url,$userAgent,$xmlRpcRequest) {
    $returnValue = false;
    if (function_exists('wp_remote_post')) {
      $options = array('user-agent' => $userAgent,
      'body' => $xmlRpcRequest);
      
      $response = wp_remote_post($url, $options);
      if (!is_wp_error($response) && 200 == wp_remote_retrieve_response_code($response)) {
        $decodedResponse = xmlrpc_decode($response['body'],'utf-8');
        if(!xmlrpc_is_fault($decodedResponse)) {
          $returnValue = $decodedResponse;
        }
      }
    }
    return $returnValue;
  }

  // getRemoteXmlRpcContent()
}

// DonationLoader()
?>