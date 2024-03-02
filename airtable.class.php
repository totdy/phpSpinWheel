<?php
class airtable{

    private $baseId;
    private $tableId;
    private $privateToken;

    public function __construct($baseId, $tableId, $privateToken){
        $this->baseId = $baseId;
        $this->tableId = $tableId;
        $this->privateToken = $privateToken;
    }

    public function getRecords(){
        
        $url = "https://api.airtable.com/v0/".$this->baseId."/".$this->tableId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Bearer ".$this->privateToken,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);        
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        if($resp === false) {
            return curl_error($curl);
        } else {
            return $resp;
        }
    }

    public function createRecord($aFields){
        
        $url = "https://api.airtable.com/v0/".$this->baseId."/".$this->tableId;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: Bearer ".$this->privateToken,
        "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = '{"fields": '.json_encode($aFields).'}';
                      
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        if($resp === false) {
            return curl_error($curl);
        } else {
            return $resp;
        }
    }
}