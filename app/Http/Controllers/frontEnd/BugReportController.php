<?php

namespace App\Http\Controllers\frontEnd;
use App\Http\Controllers\Controller;
use illuminate\Http\Request;

use Auth;
use App\SupportTicket, App\SupportTicketMessage;

class BugReportController extends Controller
{
	  
	//show 500 error page
	public function index(Request $request){
    
        $bug_report = $request->input();
        
    	return view('frontEnd.error_500',compact('bug_report'));

    }

    //add a new support ticket of bug type
    public function add(Request $request) {

    	if($request->isMethod('post'))
        {
    		$data = $request->all();
          
            $website_end = $data['website_end'];
            if($website_end == 'backend'){
                $redirect_path = '/admin';
            } else{
                $redirect_path = '/';
            }

            $ticket                            = new SupportTicket;
            $ticket->user_id                   = $data['user_id'];
            $ticket->title                     = $data['title'];
            $ticket->status                    = 0;
            $ticket->home_id                   = Auth::user()->home_id;
            $ticket->is_bug                    = 1;
            
            if($ticket->save()){
	            $supportTicketMessage              = new SupportTicketMessage;
	            $supportTicketMessage->ticket_id   = $ticket->id;
	            $supportTicketMessage->message     = $data['message'];
	            $supportTicketMessage->sender_type = 0;
            }

            if($supportTicketMessage->save()){

                return redirect($redirect_path)->with('success','Bug report has been sent successfully');
            }
            else{
           		return redirect($redirect_path)->with('error',COMMON_ERR);
            }
    	}
    }


}
