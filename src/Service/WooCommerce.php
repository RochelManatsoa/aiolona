<?php

namespace App\Service;

use App\Data\ImportData;
use Doctrine\ORM\EntityManagerInterface;

class WooCommerce
{
    private $endpoint;
    private $key;
    private $secret;
    private $em;
    
    public function __construct(
        $endpoint,
        $key,
        $secret,
        EntityManagerInterface $em
    ) {
        $this->endpoint = $endpoint;
        $this->key = $key;
        $this->secret = $secret;
        $this->em = $em;
    }

    public function importProduct(ImportData $importData){

        $url = $this->endpoint;

        if($importData instanceof ImportData){
            $url .= '?'. http_build_query($importData);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        curl_setopt($ch, CURLOPT_USERPWD, $this->key . ':' . $this->secret);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $responses = json_decode($result);
        $values = [];
        $resp = [];

        foreach ($responses as $key => $response) {
            $resp['id'] = $response->id;
            $resp['name'] = $response->name;
            $resp['slug'] = $response->slug;
            $resp['categories'] = $response->categories;
            $resp['external_url'] = $response->external_url;
            $resp['images'] = $response->images;
            $resp['related_ids'] = $response->related_ids;
            $resp['short_description'] = $response->short_description;
            $resp['status'] = $response->status;
            foreach ($response->meta_data as $key => $value) {
                if($value->key == 'slogan'){
                    $resp['slogan'] = $value->value;
                }
            }

            $values[] = $resp;
        }

        return $values;
    }

    public function importFixturesProduct(){

        $url = $this->endpoint.'?per_page=100';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        curl_setopt($ch, CURLOPT_USERPWD, $this->key . ':' . $this->secret);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $responses = json_decode($result);
        $values = [];
        $resp = [];

        foreach ($responses as $key => $response) {
            $resp['id'] = $response->id;
            $resp['name'] = $response->name;
            $resp['slug'] = $response->slug;
            $resp['categories'] = $response->categories;
            $resp['external_url'] = $response->external_url;
            $resp['images'] = $response->images;
            $resp['related_ids'] = $response->related_ids;
            $resp['short_description'] = $response->short_description;
            $resp['status'] = $response->status;
            // $resp['meta_data'] = $response->meta_data[105];

            $values[] = $resp;
        }

        return $values;
    }
}