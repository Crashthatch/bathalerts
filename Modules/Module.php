<?php

abstract class Module {

    private $tkn = "";

    function fetch() {
        $opts = array(
            'http' => array(
                'method'=> "GET",
                'header'=>  "Accept: application/json\r\n" .
                            "Content-type: application/json\r\n" .
                            "X-App-Token: " . $this->tkn
            )
        );
        $c = stream_context_create($opts);
        return file_get_contents($this->url, false, $c);
    }
}

?>