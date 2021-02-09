<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Police extends Controller
{
    public function search(Request $request)
    {
        //     $curl = curl_init();

        //     curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://opendata.taichung.gov.tw/api/action/datastore_search?resource_id=89651507-9952-438b-ab83-a25ac019bccf',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        //     ));

        //     $response = curl_exec($curl);
        //     $a = json_decode($response, true);

        //     $data = $a['result']['records'];

        //     foreach ($data as $key => $val) {
        //         $data_array [] = [
        //             "站名" =>$val["Position"],
        //             "英文站名"=>$val["EName"],
        //             "中文區名" =>$val["CArea"],
        //             "英文區名" =>$val["EArea"],
        //             "中文地址" =>$val["CAddress"],
        //             "英文地址" =>$val["EAddress"],
        //             "可借數量" =>$val["AvailableCNT"],
        //             "可還數量" =>$val["EmpCNT"],
        //             "X 座標" =>$val["X"],
        //             "Y 座標" =>$val["Y"],
        //             "更新時間" =>$val["UpdateTime"],


        //         ];
        //     }


        //     curl_close($curl);
        //     // return response($response["result"]["records"]) ;
        //     return response($data_array) ;

       
        $curl = curl_init();

        curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://datacenter.taichung.gov.tw/swagger/OpenData/f15bb282-df4b-45f2-be6a-037a8a709055',
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
        // echo $response;
        // $key = $request->key;

        $a = json_decode($response, true);

        $key = $request->key;

        $ss = collect($a)->where('路口', '=', $request->key)->toArray();
       
   
        return $ss;
    }
}
