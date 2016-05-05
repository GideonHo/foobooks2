<?php

namespace App\Libraries;

class GoogleBooks {

    /**
    *
    */
    public function __construct() {
        $this->client = new \Google_Client();
        $this->client->setApplicationName("Client_Library_Examples");
        $this->client->setDeveloperKey(\Config::get('apis.google_api_key'));
    }

    /**
    *
    */
    public function getOtherBooksByAuthor($author_name, $maxResults = 5) {

        $service = new \Google_Service_Books($this->client);

        $optParams = [
            'q' => 'author:'.$author_name,
            'maxResults' => $maxResults
        ];

        return $service->volumes->listVolumes('', $optParams);
    }

}