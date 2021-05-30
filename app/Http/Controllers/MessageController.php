<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;


class MessageController extends Controller
{
	public function create(Request $request){
	$user = auth()->user();	
    $message = new Message();
	$message->username=$user->name;
	$message->text=$request->Text1;
	$message->save();
	
	return redirect('/');
	}
}
