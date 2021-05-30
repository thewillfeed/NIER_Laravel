<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Message;


class MainController extends Controller
{
    public function index(){	
		$user = auth()->user();
		$messages = Message::all();	
		return view('main', ['messages'=>$messages],['user'=>$user]);
	}
}
