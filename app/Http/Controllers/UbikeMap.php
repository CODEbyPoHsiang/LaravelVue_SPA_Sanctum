<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UbikeMap extends Controller
{
    public function taipeiubikemap(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://tcgbusfs.blob.core.windows.net/blobyoubike/YouBikeTP.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    public function taipeiubikemap_search(Request $request)
    {
        $keywords = $request->keywords;

        // dd($keywords);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://tcgbusfs.blob.core.windows.net/blobyoubike/YouBikeTP.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);


        $arr = json_decode($response, true);

        curl_close($curl);

        $data_reset = [];

        foreach ($arr as $key =>$val) {
            $data_reset=$val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            $final_data[$v['sna']]=[
                $v,
            ];
        }

        $result = isset($final_data[$keywords]) ? $final_data[$keywords] : null;

        // $record = [
        //     "retCode"=> 1,
        //     "retVal"=>$result,
        // ];

        
        // return $record;

        if ($result == ""){
            return response("請輸入完整站名或輸入的站名不存在",202);
        }
        return response($result,200);
    }

    public function taichungubikemap(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/9af00e84-473a-4f3d-99be-b875d8e86256',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
