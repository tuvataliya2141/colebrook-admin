<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Mail\SupportMailManager;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Mail;

class SupportTicketController extends Controller
{
    public function supportTicketsList($id) {
        $tickets = Ticket::where('user_id', $id)->get();

        if(count($tickets) > 0) {
            return response()->json([
                'status' => true,
                'message' => translate('Data get successfully'),
                'data' => $tickets
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('No tickets available'),
            ]);
        }
    }

    public function supportTicketDetails($id) {
        $ticket = Ticket::where('id', $id)->first();
        
        if($ticket) {
            $ticket['replies'] = TicketReply::where('ticket_id', $id)->orderBy('id', 'ASC')->get();
            return response()->json([
                'status' => true,
                'message' => translate('Data get successfully'),
                'data' => $ticket
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Ticket Details not available'),
            ]);
        }
    }

    public function ticketReply(Request $request) {
        $ticket = Ticket::where('id', $request->TicketId)->where('user_id', $request->user_id)->first();
        if ($ticket) {
            $ticketReply = new TicketReply();
            $ticketReply->ticket_id = $request->TicketId;
            $ticketReply->user_id = $request->user_id;
            $ticketReply->details = $request->details;
            if ($ticketReply->save()) {
                return response()->json([
                    'status' => true,
                    'message' => translate('Ticket has been sent successfully'),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Ticket Details not available'),
            ]);
        }
    }

    public function tikect_support(Request $request)
    {
        $ticket = new Ticket();
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = auth()->user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;

        if($ticket->save()){
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            return response()->json([
                'status' => true,
                'message' => translate('Ticket has been sent successfully'),
            ]);
            // return redirect()->route('support_ticket.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return response()->json([
                'status' => true,
                'message' => translate('Something went wrong'),
            ]);
        }
    }

    public function send_support_mail_to_admin($ticket){
        $array['view'] = 'emails.support';
        $array['subject'] = 'Support ticket Code is:- '.$ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Hi. A ticket has been created. Please check the ticket.';
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $ticket->user->name;
        $array['details'] = $ticket->details;

        // dd($array);
        // dd(User::where('user_type', 'admin')->first()->email);
        try {
            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }
    
    public function tikect_support_product_list(){
        $orderData = OrderDetail::select('order_details.order_id', 'order_details.delivery_status', 'order_details.product_id', 'order_details.updated_at', 'products.id', 'products.name', 'products.return_days', 'products.replace_days')
                            ->join('products', 'products.id' ,'=' ,'order_details.product_id')
                            ->where('order_details.delivery_status', 'confirmed')
                            ->get();
        $data = [];
        $date = date("Y-m-d");
        $checkDate = date("Y-m-d");
        $productData = [];
        foreach ($orderData as $key => $value) {
            $return_days = $value->return_days;
            $replace_days = $value->replace_days;
            $updated_at = $value->updated_at;
            $checkDate = $updated_at;
            if($return_days != null || $replace_days != null){
                if($return_days != null){
                    $effectiveDate = strtotime("+$return_days days", strtotime($updated_at));
                    $checkDate = date('Y-m-d',$effectiveDate);
                } elseif($replace_days != null) {
                    $effectiveDate = strtotime("+$replace_days days", strtotime($updated_at));
                    $checkDate = date('Y-m-d',$effectiveDate);
                }
                if($date <= $checkDate){
                    $data['product_id'] = $value->product_id;
                    $data['name'] = $value->name;
                }
                $productData []= $data;
            }
        }
        if($productData){
            return response()->json([
                'status' => true,
                'message' => 'List fatch successfully',
                'data' => $productData
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
                'data' => []
            ],200);
        }
    }
}
