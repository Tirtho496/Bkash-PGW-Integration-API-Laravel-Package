<?php

namespace Tirtho496\Bkash_pgw;

use Illuminate\Support\Facades\Http;

class Payment {
    public $base_url = "https://tokenized.sandbox.bka.sh/v1.2.0-beta";

    public function createAgreement(Request $request)
    {
        $header = $this->createHeader();
        $website_url = URL::to("/");
        $body_data = array(
            'mode' => '0000',
            'payerReference' => ' ',
            'callbackURL' => $website_url.'/bkash/executeCallback',
            'amount' => '',
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => ''
        );

        $body_data_json = json_encode($body_data);
        $response = $this->makeRequest('/tokenized/checkout/create',$header,'POST',$body_data_json);

        return redirect((json_decode($response)->bkashURL));
    }

    public function executeAgreement($paymentID)
    {

        $header =$this->createHeader();

        $body = array(
            'paymentID' => ''
        );
        $body_json=json_encode($body);

        $response = $this->makeRequest('/tokenized/checkout/execute',$header,'POST',$body_json);

        $result = json_encode($response,true);

        return $response;
    }

    public function executeCallback(Request $request)
    {
        $Request = $request->all();

        
        if($Request['status'] == 'failure'){
            //return page with agreement failure message

        }else if($Request['status'] == 'cancel'){
            //return page with agreement failure message

        }else{
            
            $response = $this->executeAgreement($Request['paymentID']);

            $arr = json_decode($response,true);

            if($arr)
            {
                if($arr['statusCode'] != '0000'){
                    //return page with failure message
                }
                //here goes code to store agreement id for respective user in database

                
            }
            else{
                $response = queryAgreement($Request['paymentID']);
            }
            

        }

    }
    

    public function payAgreement(Request $request)
    {
        
        //get agreement ID
        $agreement = ""; 
        
        $header =$this->createHeader();

        $website_url = URL::to("/");

        $body_data = array(
            'agreementID' => '',
            'mode' => '0001',
            'payerReference' => ' ',
            'callbackURL' => $website_url.'/bkash/callback',
            'amount' => 1,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => '' // you can pass here OrderID 
        );
        $body_data_json=json_encode($body_data);

        $response = $this->makeRequest('/tokenized/checkout/create',$header,'POST',$body_data_json);

        return redirect((json_decode($response)->bkashURL));

    }


    public function createPayment(Request $request)
    {
        $this->userInfo = $request;
        $header =$this->createHeader();

        $website_url = URL::to("/");

        $body_data = array(
            'mode' => '0011',
            'payerReference' => ' ',
            'callbackURL' => $website_url.'/bkash/callback',
            'amount' => '',
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => ''
        );
        $body_data_json=json_encode($body_data);

        $response = $this->makeRequest('/tokenized/checkout/create',$header,'POST',$body_data_json);

        $this->order = $this->placeOrder($request);

        return redirect((json_decode($response)->bkashURL));
    }

    public function createHeader(){
        return array(
            'Content-Type:application/json',
            'Authorization:' .$this->grantToken(),
            'X-APP-Key: ...'
        );
    }

    public function grantToken()
    {
        $header = array(
                'Content-Type:application/json',
                'username:...',
                'password:...'
                );

        $body_data = array(
            'app_key'=> '...', 
            'app_secret'=>'...');
        $body_data_json=json_encode($body_data);
    
        $response = $this->makeRequest('/tokenized/checkout/token/grant',$header,'POST',$body_data_json);
        $token = json_decode($response);
        $token_id = $token->id_token;

        return $token_id;
    }

    public function makeRequest($url,$header,$method,$body_data_json){
     

        $curl = curl_init($this->base_url.$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl,CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        curl_close($curl);

        $err = curl_error($curl);

        curl_close($curl);

        return $response;
   
    }

    public function executePayment($paymentID)
    {

        $header =$this->createHeader();

        $body = array(
            'paymentID' => $paymentID
        );
        $body_json=json_encode($body);

        $response = $this->makeRequest('/tokenized/checkout/execute',$header,'POST',$body_json);

        $result = json_decode($response,true);


        return $response;
    }

    public function callback(Request $request)
    {
        $Request = $request->all();

        if($Request['status'] == 'failure'){
            //return page with failure message

        }else if($Request['status'] == 'cancel'){
            //return page with failure message

        }else{
            
            $response = $this->executePayment($Request['paymentID']);

            $arr = json_decode($response,true);
    
            if($arr)
            {
                if($arr['statusCode'] != '0000'){
                    //return page with failure message
                }

                //code to store successful payment information

            }
            else{

                //query payment if no response
                $response = queryPayment($Request['paymentID']);
            }
            

        }

    }

    public function queryPayment($paymentID)
    {

        $header =$this->createHeader();

        $body_data = array(
            'paymentID' => $paymentID,
        );
        $body_data_json=json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/status',$header,'POST',$body_data_json);
        
        $res_array = json_decode($response,true);
        

        return $response;
    }

    public function searchPayment(Request $request)
    {

        $header =$this->createHeader();

        $body_data = array(
            'trxID' => '',
        );
        $body_data_json=json_encode($body_data);

        $response = $this->makeRequest('/tokenized/checkout/general/searchTransaction',$header,'POST',$body_data_json);
        
    }

    public function refundPayment(Request $request)
    {
        $header =$this->createHeader();
        $body_data = array(
            'paymentID' => '',
            'amount' => '',
            'trxID' => '',
            'sku' => 'Test',
            'reason' => 'Test'
        );
     
        $body_data_json=json_encode($body_data);

        $response = $this->makeRequest('/tokenized/checkout/payment/refund',$header,'POST',$body_data_json);
        
    }  
}
