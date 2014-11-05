<?php
/*
  class rely on:
    - php-json
    - php-curl
*/ 
class ApiBG {
    private $host = 'http://my.billingrad.com/api/';
 
    function __construct( $open = "", $close = "", $host = '' ){
        $this->open = $open;
        $this->close = $close;
        if( $host !== '' )
            $this->host = $host;
    }
    function request( $api, $fn, $data ){
        $data = json_encode( $data );
        $key = base64_encode( hash( 'sha256', $this->close . $data, true ) );

        $c = curl_init();
        $url = $this->host . $api .'/'. $fn . '?_open='. rawurlencode( $this->open ) .'&_key=' . rawurlencode( $key );
 
 
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $c, CURLOPT_HEADER, 0);
        curl_setopt( $c, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $c, CURLOPT_URL, $url );
        curl_setopt( $c, CURLOPT_POST, true );
        curl_setopt( $c, CURLOPT_POSTFIELDS, $data );
 
        $result = curl_exec($c);
        curl_close($c);
 
        return json_decode( $result );
 
    } 
}

/* Usage example
$bg_api = new ApiBG('public_key','private_key');
$out = $bg_api->request('project', 'get', array('id' => 'project_id'));
var_dump( $out );
*/
