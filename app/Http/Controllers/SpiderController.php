<?php 
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SpiderController extends Controller{

    function index(Request $req){
        $url = 'https://ciliku.net/toSearch';
        $client = new Client(['base_uri'=>$url,  'timeout' => 10]);

        $post_data = [
            'keyword'=> $req->input('k')
            ,'page' => $req->input('p', 1)
            ,'size' => $req->input('s', 15)
        
        ];
        $response = $client->request('POST', $url, ['query'=>$post_data]);
        // $body = $response->getBody();

        $body=$response->getBody();
        $json = $body->getContents(); 

        $ret = json_decode($json, true);

        $new_r = [];
        if(empty($ret['data']['content'])){
            return 'empty';
        }

        foreach($ret['data']['content'] as $k => $v){
            $s = round($v['size']/1024/1024);
            echo $v['name'].'('.$s.')';
            echo '<div>'.'magnet:?xt=urn:btih:'.$v['btih'].'</div>';
            echo "<br>";

        }

       
    }



}