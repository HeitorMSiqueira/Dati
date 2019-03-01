<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;


class ControladorProduto extends Controller
{
    public function indexView()
    {
        return view('produtos');
    }
    
    public function index()
    {
    
        $curl = curl_init();
        $url  = 'http://18.228.14.48/api/products?cmd=list';
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CUSTOMREQUEST   => 'GET',
            CURLOPT_URL             => $url

        ]);
        $produtos = json_decode(curl_exec($curl));
        curl_close($curl);
         
       // print_r($produtos);exit(); 
        return ($produtos);
        
    }
    
    
    
    public function show($id)
    {
        
        $curl = curl_init();
        $url  = 'http://18.228.14.48/api/products?cmd=details&id='.$id;
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CUSTOMREQUEST   => 'GET',
            CURLOPT_URL             => $url

        ]);
        $prod = json_decode(curl_exec($curl));
        curl_close($curl);
        if (isset($prod)) {
            return json_encode($prod);            
        }
        return response('Produto não encontrado', 404);
    }

    
    public function update(Request $request, $id)
    {
        $url = 'http://18.228.14.48/api/products/'.$id;
        $data = array(  'description' => $request->input('description'),
                        'code' => $request->input('code'),
                        'short_description' => $request->input('short_description'),
                        'status' => $request->input('status'),
                        'value' => $request->input('value'),
                        'qty' => $request->input('qty')
                );
        $prod = curl_init($url);
        curl_setopt($prod, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($prod, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($prod, CURLOPT_POSTFIELDS,http_build_query($data));

        $response = curl_exec($prod);
        //print_r($prod); exit();
        if ($response){
            return $response; 
        }
        else{
            return false;
        }
    }
    
    
    public function store(Request $request)
    {
        $url = 'http://18.228.14.48/api/products';
        $post = [
            'description' => $request->input('description'),
            'code' => $request->input('code'),
            'short_description' => $request->input('short_description'),
            'status' => $request->input('status'),
            'value' => $request->input('value'),
            'qty' => $request->input('qty')
        ];

        $produto = curl_init($url);
        curl_setopt($produto, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($produto, CURLOPT_POSTFIELDS, http_build_query($post));

        // execute!
        $response = curl_exec($produto);

        // close the connection, release resources used
        curl_close($produto);

        // do anything you want with your response
      
        if ($response){
            return $response; 
        }
        else{
            return false;
        }
         
                
    }  
    
    
    public function destroy($id)
    {
        $url = 'http://18.228.14.48/api/products/'. $id;
       
        $produto = curl_init();
        curl_setopt($produto, CURLOPT_URL, $url);
        curl_setopt($produto, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($produto);
        $httpCode = curl_getinfo($produto, CURLINFO_HTTP_CODE);
        curl_close($produto);

        if (isset($produto)) {
            return response('Removido com sucesso', 200);
        }
        return response('Produto não encontrado', 404);
    }
    
       
}


















