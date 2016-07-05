<?php
/*
mailboxLayer class - Verify e-mail addresses
version 1.0 12/5/2015

API reference at https://mailboxlayer.com/documentation

Copyright (c) 2015, Wagon Trader

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class Mailboxlayer{

    //Your mailboxlayer API key
    //Available at https://mailboxlayer.com/product
    private $apiKey = 'YOUR API KEY';

    //API endpoint
    //only needs to change if the API changes location
    //to establish a secure connection, change http to https
    private $endPoint = 'http://apilayer.net/api/check';

    //holds the error code, if any
    public $errorCode;

    //holds the error text, if any
    public $errorText;

    //response object
    public $response;

    //JSON response from API
    public $responseAPI;

    /*
    method:  verifyMail
    usage:   verifyMail(string email[bool smtpCheck=true][,bool formatJSON=false][,string callBack=''][,bool catch_all=false]);
    params:  email = the email address to be checked
             smtpCheck = check mx redord and smtp server
             formatJSON = true to use prettified JSON for debugging
             callback = JSONP callback function.

    This method prepares the API request to verify the supplied e-mail address.
    If you do not require the mx or smtp check, setting smtpCheck to false will greatly increse the response time.

    returns: false if error returned or true if response returned
    */
    public function verifyMail($email,$smtpCheck=true,$formatJSON=false,$callback='',$catch_all=false){

        $request = $this->endPoint.'?access_key='.$this->apiKey.'&email='.$email;

        $request .= ( $smtpCheck === false ) ? '&smtp=0' : '';

        $request .= ( empty($formatJSON) ) ? '' : '&format=1';

        $request .= ( empty($calback) ) ? '' : '&callback='.$callback;

        $request .= ( empty($catch_all) ) ? '' : '&catch_all=1';

        $this->response = $this->sendRequest($request);

        if( !empty($this->response->error->code) ){

            $this->errorCode = $this->response->error->code;
            $this->errorText = $this->response->error->info;

            return false;

        }else{

            return true;

        }

    }

    /*
    method:  sendRequest
    usage:   sendRequest(string request);
    params:  request = full endpoint for API request

    This method sends the API request and decodes the JSON response.

    returns: object of request results
    */
    public function sendRequest($request){

        $this->responseAPI = file_get_contents($request);

        $return = json_decode($this->responseAPI);

        return $return;

    }

}
?>
