<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
</head>
<body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Brand</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
	        <li><a href="#">Link</a></li>
	      </ul>
	      <ul class="nav navbar-nav navbar-right">
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	          </ul>
	        </li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
<div class="container">
<?php 
ini_set('max_execution_time', 300);
// Display Script End time
$time_start = microtime(true);


/**
 * Pattern
 * 
 * @var string
 */
$pattern = str_ireplace(array(" a "," the "," an "),"",strtolower($_POST['query']));

/**
 * Text to search
 * 
 * @var string
 */

 
/**
 * Preprocess the pattern and return the "next" table
 * 
 * @param string $pattern
 */
function preprocessMorrisPratt($pattern, &$nextTable)
{
    $i = 0;
	$j = $nextTable[0] = -1;
	$len = strlen($pattern);
 
	while ($i < $len) {
		while ($j > -1 && $pattern[$i] != $pattern[$j]) {
			$j = $nextTable[$j];
		}
 
		$nextTable[++$i] = ++$j;
	}
}
 
/**
 * Performs a string search with the Morris-Pratt algorithm
 * 
 * @param string $text
 * @param string $pattern
 */
function MorrisPratt($text, $pattern)
{
	// get the text and pattern lengths
	$n = strlen($text);
	$m = strlen($pattern);
	$nextTable = array();
 
	// calculate the next table
	preprocessMorrisPratt($pattern, $nextTable);
 
	$i = $j = 0;
	while ($j < $n) {
		while ($i > -1 && $pattern[$i] != $text[$j]) {
			$i = $nextTable[$i];
		}
		$i++;
		$j++;
		if ($i >= $m) {
			return $j - $i;
		}
	}
	return -1;
}
 
// 275


/****

* Simple PHP application for using the Bing Search API

*/

$acctKey = 'jXfLdll2f8TCt6FY9AX6+yXSge8w4O2HXIm1Llj5Lsk';

$rootUri = 'https://api.datamarket.azure.com/Bing/Search';


// Encode the query and the single quotes that must surround it.

$query = urlencode($pattern);

// Construct the full URI for the query.

$requestUri = "$rootUri/Web?\$format=json&Query=%27$query%27";

$auth = base64_encode("$acctKey:$acctKey");

$data = array(

'http' => array(

'request_fulluri' => true,

// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10

'ignore_errors' => true,

'header' => "Authorization: Basic $auth")

);

$context = stream_context_create($data);

// Get the response from Bing.

$response = file_get_contents($requestUri, 0, $context);

// Decode the response. 

$jsonObj = json_decode($response);
$c=0;
echo "<p>$pattern</p><br/><br/> Above Text is present on the pages<br/>";
foreach($jsonObj->d->results as $value) 
	{
		$c++;
		if($c==4)
			break;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $value->Url,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));// Send the request & save response to $resp
		$text =  str_ireplace(array(" a "," the "," an "),"",strtolower(strip_tags(curl_exec($curl))));
		// Close request to clear up some resources
		curl_close($curl);
		$temp = MorrisPratt($text, $pattern);
		if($temp>0) 		echo "<a href='$value->Url'>$value->Title</a><br/>";
	}

$time_end = microtime(true);
//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<br/><b>Total Execution Time:</b> '.$execution_time.' Mins';

?>
</div>