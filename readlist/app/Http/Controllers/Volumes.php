<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Volumes extends Controller
{
    function list() 
    {
        $ListEndpoint = 'https://www.googleapis.com/books/v1/users/114558902594008357886/bookshelves/1001/volumes/';
        $client = new Client();
        $res = $client->request(
            'GET', $ListEndpoint,
            ['headers' => [
            'key' => 'AIzaSyCI9PQh2HP7RA-v1NKC1F1CeMqCrovm5w8',
            'Accept' => 'application/json','Content-type' => 'application/json']]
        );

      $items = json_decode((string) $res->getBody(), true)['items'];
        return view('volumes',['data'=>$items]);
    }
}
