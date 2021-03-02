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

        if ($keywords == "") {
            return response("請輸入關鍵字查詢!", 202);
        }

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

        foreach ($arr as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            //進行模糊搜尋
            if (preg_match("/" . $keywords . "/i", $v['sna'])) {
                $final_data[$v['sno']] =
                    $v;
            }
        }

        if ($final_data == []) {
            return response("輸入的站名不存在，請重新操作!", 202);
        }

        return $final_data;

        // if ($result == []) {
        //     return response("輸入的站名不存在", 202);
        // }
        // return response($result, 200);
    }

    //此api是搜尋出來後要點擊單一資料時做完全比對
    public function taipeiubikemap_full_match($keywords)
    {
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

        foreach ($arr as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            $final_data[$v['sno']] = [
                $v,
            ];
        }

        $result = isset($final_data[$keywords]) ? $final_data[$keywords] : null;

        // $record = [
        //     "retCode"=> 1,
        //     "retVal"=>$result,
        // ];


        // return $record;

        if ($result == "") {
            return response("請輸入完整站名或輸入的站名不存在", 202);
        }
        return response($result, 200);
    }

    //顯示列表(台中 ubkie)
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
    //台中ibike
    public function taichungibikemap(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/edfc90e4-a71b-4193-8b2c-aefcc79c6549',
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

        return response($response);

        $array = json_decode(json_encode(simplexml_load_string($response)), true);

        foreach ($array as $k => $v) {
            // print_r($v);
            $data[] = [
                'sna' => $v[0]["Position"],
                "sarea" => $v[0]["CArea"],
                "ar" => $v[0]["CAddress"],
                "snaen" => $v[0]["EName"],
                "sareaen" => $v[0]["EArea"],
                "aren" => $v[0]["EAddress"],
                "sno" => $v[0]["ID"],
                "tot" => $v[0]["AvailableCNT"] + $v[0]["EmpCNT"],
                "sbi" => $v[0]["AvailableCNT"],
                "mday" => $v[0]["UpdateTime"],
                "lat" => $v[0]["Y"],
                "lng" => $v[0]["X"],
                "bemp" => $v[0]["EmpCNT"],
                "act" => "1",
            ];
        }
        return response($data);
    }

    // 台中ibike+ubike 全部列表
    public function taichungallbikemap(Request $request)
    {
        //台中 ibike(1.0列表)

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/edfc90e4-a71b-4193-8b2c-aefcc79c6549',
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

        $array = json_decode(json_encode(simplexml_load_string($response)), true);

        foreach ($array as $key => $val) {
            $ibike_data_arr= $val;
            // print_r($v);
            // $ibike_data= [
            //     'sna' => $v[0]["Position"],
            //     "sarea" => $v[0]["CArea"],
            //     "ar" => $v[0]["CAddress"],
            //     "snaen" => $v[0]["EName"],
            //     "sareaen" => $v[0]["EArea"],
            //     "aren" => $v[0]["EAddress"],
            //     "sno" => $v[0]["ID"],
            //     "tot" => $v[0]["AvailableCNT"] + $v[0]["EmpCNT"],
            //     "sbi" => $v[0]["AvailableCNT"],
            //     "mday" => $v[0]["UpdateTime"],
            //     "lat" => $v[0]["Y"],
            //     "lng" => $v[0]["X"],
            //     "bemp" => $v[0]["EmpCNT"],
            //     "act" => "1",
            // ];
        }
        foreach ($ibike_data_arr as $v) {
            $ibike_data_json[]= [
                'sna' => $v["Position"],
                "sarea" => $v["CArea"],
                "ar" => $v["CAddress"],
                "snaen" => $v["EName"],
                "sareaen" => $v["EArea"],
                "aren" => $v["EAddress"],
                "sno" => $v["ID"],
                "tot" => $v["AvailableCNT"] + $v["EmpCNT"],
                "sbi" => $v["AvailableCNT"],
                "mday" => $v["UpdateTime"],
                "lat" => $v["Y"],
                "lng" => $v["X"],
                "bemp" => $v["EmpCNT"],
                "act" => "1",
            ];
        }
        // return response($ibike_data_json);


        //台中ubike列表(2.0)
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

        $ubike_data = curl_exec($curl);

        curl_close($curl);
        $arr_ubike_data= json_decode($ubike_data, true);

        $array_ubike_merge=array_merge($ibike_data_json, $arr_ubike_data["retVal"]);
        return response(["retVal"=>$array_ubike_merge]);
    }


    //台中搜尋
    public function taichungubikemap_search(Request $request)
    {
        $keywords = $request->keywords;

        if ($keywords == "") {
            return response("請輸入關鍵字查詢!", 202);
        }

        // dd($keywords);

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


        $arr = json_decode($response, true);


        curl_close($curl);

        $data_reset = [];

        foreach ($arr as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            //進行模糊搜尋
            if (preg_match("/" . $keywords . "/i", $v['sna'])) {
                $final_data[$v['sno']] =
                    $v;
            }
        }

        if ($final_data == []) {
            return response("輸入的站名不存在，請重新操作!", 202);
        }

        return response($final_data);

        // if ($result == []) {
        //     return response("輸入的站名不存在", 202);
        // }
        // return response($result, 200);
    }

    //臺中 ibike+ubike 全部搜尋api
    public function taichungallbikemap_search(Request $request)
    {
        $keywords = $request->keywords;

        if ($keywords == "") {
            return response("請輸入關鍵字查詢!", 202);
        }
        //台中 ibike(1.0列表)

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/edfc90e4-a71b-4193-8b2c-aefcc79c6549',
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

        $array = json_decode(json_encode(simplexml_load_string($response)), true);

        foreach ($array as $key => $val) {
            $ibike_data_arr= $val;
        }
        foreach ($ibike_data_arr as $v) {
            $ibike_data_json[]= [
                'sna' => $v["Position"],
                "sarea" => $v["CArea"],
                "ar" => $v["CAddress"],
                "snaen" => $v["EName"],
                "sareaen" => $v["EArea"],
                "aren" => $v["EAddress"],
                "sno" => $v["ID"],
                "tot" => $v["AvailableCNT"] + $v["EmpCNT"],
                "sbi" => $v["AvailableCNT"],
                "mday" => $v["UpdateTime"],
                "lat" => $v["Y"],
                "lng" => $v["X"],
                "bemp" => $v["EmpCNT"],
                "act" => "1",
            ];
        }
        // return response($ibike_data_json);


        //台中ubike列表(2.0)
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

        $ubike_data = curl_exec($curl);

        curl_close($curl);
        $arr_ubike_data= json_decode($ubike_data, true);

        $array_ubike_merge=array_merge($ibike_data_json, $arr_ubike_data["retVal"]);
        $all_bike_data = ["retVal"=>$array_ubike_merge];

        //進行搜尋比對

        $data_reset = [];

        foreach ($all_bike_data as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            //進行模糊搜尋
            if (preg_match("/" . $keywords . "/i", $v['sna'])) {
                $final_data[$v['sno']] =
                    $v;
            }
        }

        if ($final_data == []) {
            return response("輸入的站名不存在，請重新操作!", 202);
        }

        return response($final_data);
    }

    //臺中 ibike+ubike 全部搜尋api
    public function taichungallbikemap_full_match(Request $request)
    {
        $keywords = $request->keywords;

        if ($keywords == "") {
            return response("請輸入關鍵字查詢!", 202);
        }
        //台中 ibike(1.0列表)

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/edfc90e4-a71b-4193-8b2c-aefcc79c6549',
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

        $array = json_decode(json_encode(simplexml_load_string($response)), true);

        foreach ($array as $key => $val) {
            $ibike_data_arr= $val;
        }
        foreach ($ibike_data_arr as $v) {
            $ibike_data_json[]= [
                'sna' => $v["Position"],
                "sarea" => $v["CArea"],
                "ar" => $v["CAddress"],
                "snaen" => $v["EName"],
                "sareaen" => $v["EArea"],
                "aren" => $v["EAddress"],
                "sno" => $v["ID"],
                "tot" => $v["AvailableCNT"] + $v["EmpCNT"],
                "sbi" => $v["AvailableCNT"],
                "mday" => $v["UpdateTime"],
                "lat" => $v["Y"],
                "lng" => $v["X"],
                "bemp" => $v["EmpCNT"],
                "act" => "1",
            ];
        }
        // return response($ibike_data_json);


        //台中ubike列表(2.0)
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

        $ubike_data = curl_exec($curl);

        curl_close($curl);
        $arr_ubike_data= json_decode($ubike_data, true);

        $array_ubike_merge=array_merge($ibike_data_json, $arr_ubike_data["retVal"]);
        $all_bike_data = ["retVal"=>$array_ubike_merge];

        //進行搜尋比對

        $data_reset = [];

        foreach ($all_bike_data as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            $final_data[$v['sno']] = [
                $v,
            ];
        }

        $result = isset($final_data[$keywords]) ? $final_data[$keywords] : null;

        // $record = [
        //     "retCode"=> 1,
        //     "retVal"=>$result,
        // ];


        // return $record;

        if ($result == "") {
            return response("請輸入完整站名或輸入的站名不存在", 202);
        }
        return response($result, 200);
    }

    //此api是搜尋出來後要點擊單一資料時做完全比對(台中)
    public function taichungubikemap_full_match($keywords)
    {
        // dd($keywords);

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


        $arr = json_decode($response, true);

        curl_close($curl);

        $data_reset = [];

        foreach ($arr as $key => $val) {
            $data_reset = $val;
        }

        $final_data = [];
        foreach ($data_reset as $k => $v) {
            $final_data[$v['sno']] = [
                $v,
            ];
        }

        $result = isset($final_data[$keywords]) ? $final_data[$keywords] : null;

        // $record = [
        //     "retCode"=> 1,
        //     "retVal"=>$result,
        // ];


        // return $record;

        if ($result == "") {
            return response("請輸入完整站名或輸入的站名不存在", 202);
        }
        return response($result, 200);
    }
}
