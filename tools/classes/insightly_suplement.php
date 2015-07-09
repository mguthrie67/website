<?php
class Insightly_sup{
  private $apikey;

  public function __construct($apikey){
    $this->apikey = $apikey;
  }

  /**
   * Gets a list of contacts 
   * 
   * @param array $options
   * @return mixed
   * @link https://api.insight.ly/v2.1/Help/Api/GET-Contacts_ids_email_tag
   */
  public function getContacts($options = null){
    $email = isset($options["email"]) ? $options["email"] : null;
    $tag = isset($options["tag"]) ? $options["tag"] : null;
    $ids = isset($options["ids"]) ? $options["ids"] : null;

    $request = $this->GET("/v2.1/Contacts");

    // handle standard OData options
    $this->buildODataQuery($request, $options);

    // handle other options
    if($email != null){
      $request->queryParam("email", $email);
    }
    if($tag != null){
      $request->queryParam("tag", $tag);
    }
    if($ids != null){
      $s = "";
      foreach($ids as $key => $value){
        if($key > 0){
          $s = $s . ",";
        }
        $s = $s . $value;
      }
      $request->queryParam("ids", $s);
    }

    return $request->asJSON();
  }

  public function getOrganizations($options = null){
    $request = $this->GET("/v2.1/Organisations");
    $this->buildODataQuery($request, $options);
    return $request->asJSON();
  }


  private function GET($url_path){
    return new InsightlyRequest("GET", $this->apikey, $url_path);
  }

  private function PUT($url_path){
    return new InsightlyRequest("PUT", $this->apikey, $url_path);
  }

  private function POST($url_path){
    return new InsightlyRequest("POST", $this->apikey, $url_path);
  }

}

class InsightlyRequest{
  const URL_BASE = 'https://api.insight.ly';
  
  /**
   * CURL resource
   * 
   * @var resource
   */
  private $curl;
  
  /**
   * URL path outside the base URL
   * 
   * @var string
   */
  private $url_path;
  
  /**
   * Request headers
   * 
   * @var array
   */
  private $headers;
  
  /**
   * Request parameters
   * 
   * @var array
   */
  private $querystrings;
  
  /**
   * Response body
   * 
   * @var string
   */
  private $body;

  /**
   * Request initialisation
   * 
   * @param string $method (GET|DELETE|POST|PUT)
   * @param string $apikey
   * @param string $url_path
   * @throws Exception
   */
  function __construct($method, $apikey, $url_path){
    $this->curl = curl_init();
    $this->url_path = $url_path;
    $this->headers = array("Authorization: Basic " . base64_encode($apikey . ":"));
    $this->querystrings = array();
    $this->body = null;

    switch($method){
    case "GET":
      // GET is the default
      break;
    case "DELETE":
      $this->method("DELETE");
      break;
    case "POST":
      $this->method("POST");
      break;
    case "PUT":
      $this->method("PUT");
      break;
    default: throw new Exception('Invalid HTTP method: ' . $method);
    }

    // Have curl return the response, rather than echoing it
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
  }

  /**
   * Get executed request response
   * 
   * @throws Exception
   * @return string
   */
  public function asString(){
    // This may be useful for debugging
    //curl_setopt($this->curl, CURLOPT_VERBOSE, true);

    $url =  InsightlyRequest::URL_BASE . $this->url_path . $this->buildQueryString();
    curl_setopt($this->curl, CURLOPT_URL, $url);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

    $response = curl_exec($this->curl);
    $errno = curl_errno($this->curl);
    if($errno != 0){
      throw new Exception("HTTP Error (" . $errno . "): " . curl_error($this->curl));
    }

    $status_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    if(!($status_code == 200 || $status_code == 201 || $status_code == 202)){
      throw new Exception("Bad HTTP status code: " . $status_code);
    }

    return $response;
  }

  /**
   * Return decoded JSON response
   * 
   * @throws Exception
   * @return mixed
   */
  public function asJSON(){
    $data = json_decode($this->asString());

    $errno = json_last_error();
    if($errno != JSON_ERROR_NONE){
      throw new Exception("Error encountered decoding JSON: " . json_last_error_msg());
    }

    return $data;
  }

  /**
   * Add data to the current request
   * 
   * @param mixed $obj
   * @throws Exception
   * @return InsightlyRequest
   */
  public function body($obj){
    $data = json_encode($obj);

    $errno = json_last_error();
    if($errno != JSON_ERROR_NONE){
      throw new Exception("Error encountered encoding JSON: " . json_last_error_message());
    }

    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
    $this->headers[] = "Content-Type: application/json";
    return $this;
  }

  /**
   * Set request method
   * 
   * @param string $method
   * @return InsightlyRequest
   */
  private function method($method){
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
    return $this;
  }

  /**
   * Add query parameter to the current request
   * 
   * @param string $name
   * @param mixed $value
   * @return InsightlyRequest
   */
  public function queryParam($name, $value){
    // build the query string for this name/value pair
    $querystring = http_build_query(array($name => $value));

    // append it to the list of query strings
    $this->querystrings[] = $querystring;

    return $this;
  }

  /**
   * Build query string for the current request
   * 
   * @return string
   */
  private function buildQueryString(){
    if(count($this->querystrings) == 0){
      return "";
    }
    else{
      $querystring = "?";

      foreach($this->querystrings as $index => $value){
        if($index > 0){
          $querystring .= "&";
        }
        $querystring .= $value;
      }

      return $querystring;
    }
  }
}
