<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
class ScraperController extends Controller
{
    public function scraper(){
        $client = new Client();

    }
}
