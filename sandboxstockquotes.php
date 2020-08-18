<?php

/*
Plugin Name: Sandbox stock quotes
Plugin URI: http://stonks.ca
Description: This is a sample plugin for sandbox that dives into the murky waters of the stock market
Version: 1.6.6.6
Author URI: http://stonks.ca
*/

// In PHP, when we want to make a constant, we use the "define()" function.
// The first argument is the constant name, the second is its value.
// We can call upon constants directly by their name (without quotes) to access their value.
// It is convention for constants to be in all caps.
define( 'STOCK_QUOTES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
include_once plugin_dir_path( __FILE__ ).'/includes/enqueue.php';

// Link the "shortcode" name to our function name.
add_shortcode(
  'showstock', // The name between square brackets that our client can type into a post / page.
  'show_stock_quotes' // The actual name of our function.
);


function show_stock_quotes () {
 
//The function will then use either an input list or the default list to create the correct url:
$url = "https://www.alphavantage.co/query?function=TIME_SERIES_WEEKLY&symbol=TSLA&apikey=3AM473W2EIHY0WL6&f=nl1c1&e=.csv";
 
//and then read in the data stream from the Yahoo! Finance web site:
$response = file_get_contents( $url );

  // If successful, proceed.
  if ( $response !== FALSE && !empty(json_decode( $response )->{"Weekly Time Series"}))
  {

    // Attempt to convert JSON from a string to a PHP array / object.
    if ( ( $responseJson = json_decode( $response ) ) !== NULL )
    {
        //var_dump($responseJson->{"Weekly Time Series"});
        // Start an output buffer (echos after this point are NOT sent to the browser, until we clear the buffer.)
        ob_start();

      echo "<h2>TSLA Stock Prices</h2>";
      foreach( $responseJson->{"Weekly Time Series"} as $key => $value) :         
        ?>
        <div class="stock">
        <div><?php echo $key ?></div>
        <div><?php echo "$".$value->{"3. low"} ?></div>
        <div><?php echo "$".$value->{"2. high"}?></div>
        </div>
       <?php
       endforeach; 
     
    }
}

// clear the output buffer
return ob_get_clean();
}
?>