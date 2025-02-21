<?php

namespace App\Services;

use App\Services\MessageService;
use Illuminate\Support\Facades\Log;

class ReveSmsGateway
{
    private $ApiKey;
    private $SecretKey;
    private $CallerId;
    private $Client;

    function __construct()
    {
        $this->ApiKey = config('app.revesms_api_key');
        $this->SecretKey = config('app.revesms_secret_key');
        $this->CallerId = config('app.revesms_caller_id');
        $this->Client = config('app.revesms_client');
    }

    private function solveNo($phone)
    {
        $len = strlen($phone);

        if ($len == 11) return '88' . $phone;
        if ($len == 10) return '880' . $phone;
        return $phone;
    }

    private function format(array $contents)
    {
        $data = [];
        foreach ($contents as $item) {
            $phone = $this->solveNo($item['phone']);

            array_push($data, [
                "callerID" => $this->CallerId,
                "toUser" => $phone,
                "messageContent" => $item['message']
            ]);
        }
        return $data;
    }

    private function saveData(array $contents)
    {
        // $message_service = new MessageService();

        // foreach ($contents as $item) {
        //     // $filter_res = array_filter($response, fn ($v) => $v['MobileNumber'] == $this->solveNo($item['phone']));
        //     // $msg = $msg_id = 'Failed';
        //     // if (count($filter_res)) {
        //     //     $key = array_keys($filter_res)[0];
        //     //     $msg_id = $filter_res[$key]['MessageId'];
        //     //     $status = $filter_res[$key]['MessageErrorDescription'];
        //     //     $msg = "$status: Message ID - $msg_id";
        //     // }
        //     $message_service->store([
        //         'phone' => $item['phone'],
        //         'message' => $item['message'],
        //         'user_id' => $item['user_id'],
        //         'status' => "Accepted",
        //         // 'tran_id' => $msg_id,
        //     ]);
        // }
    }

    public function send(array $contents)
    {
        if (!config('app.sms_send') || !count($contents)) return false;

        $returnStatus = true;
        foreach (array_chunk($contents, 350) as $arr) {

            $data = $this->format($arr);

            ///Curl Start
            $route_postfields_array = array(
                "apikey" =>  $this->ApiKey,
                "secretkey" => $this->SecretKey,
                "content" => $data,
            );
            
            $payload = json_encode($route_postfields_array);
            $curl = curl_init('http://103.177.125.108/send');
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            // Set HTTP Header for POST request 
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json'
                )
            );
            $output = curl_exec($curl);
            if(!$output){
                $returnStatus = false;
                break;
            }
            $output_json = json_decode($output, true);
            curl_close($curl);

            // if ($output_json['ErrorCode'] != 0) {
            //     Log::info($output_json['ErrorDescription']);
            //     $returnStatus = false;
            //     break;
            // }

            $this->saveData($arr);
        }

        return $returnStatus;
    }

    public function getDLRRep($id)
    {
    }

    public function getBalance()
    {
        $url = "https://smpp.revesms.com/sms/smsConfiguration/smsClientBalance.jsp?client=$this->Client";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($response, true);

        try {
            $balance = +$res['Balance'];
            return floor($balance / config('app.sms_cost'));
        } catch (\Throwable $th) {
            Log::info($th);
            Log::info($res);
            return 0;
        }
    }
}