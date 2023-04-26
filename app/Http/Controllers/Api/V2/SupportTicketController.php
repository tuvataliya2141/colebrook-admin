<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Mail\SupportMailManager;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Mail;
use Image;
use Storage;


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
        // dd($request->all());
        $ticket = new Ticket();
        // $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->code = random_int(100000, 999999).date('s');
        $ticket->user_id = auth()->user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->product_id = $request->product_id;
        if ($request->attachments != null && $request->attachments != "") {
            //  dd($request->attachments->getRealPath());
            $type = array(
                "jpg"=>"image",
                "jpeg"=>"image",
                "png"=>"image",
                "svg"=>"image",
                "webp"=>"image",
                "gif"=>"image",
                "mp4"=>"video",
                "mpg"=>"video",
                "mpeg"=>"video",
                "webm"=>"video",
                "ogg"=>"video",
                "avi"=>"video",
                "mov"=>"video",
                "flv"=>"video",
                "swf"=>"video",
                "mkv"=>"video",
                "wmv"=>"video",
                "wma"=>"audio",
                "aac"=>"audio",
                "wav"=>"audio",
                "mp3"=>"audio",
                "zip"=>"archive",
                "rar"=>"archive",
                "7z"=>"archive",
                "doc"=>"document",
                "txt"=>"document",
                "docx"=>"document",
                "pdf"=>"document",
                "csv"=>"document",
                "xml"=>"document",
                "ods"=>"document",
                "xlr"=>"document",
                "xls"=>"document",
                "xlsx"=>"document"
            );
            $upload = new Upload;
            $extension = strtolower($request->attachments->getClientOriginalExtension());

            if(isset($type[$extension])){
                $upload->file_original_name = 'Support-Ticket';
                
                $path = $request->attachments->store('uploads/all', 'local');
                $size = $request->attachments->getSize();

                // Return MIME type ala mimetype extension
                $finfo = finfo_open(FILEINFO_MIME_TYPE); 

                // Get the MIME type of the file
                $file_mime = finfo_file($finfo, base_path('public/').$path);

                if($type[$extension] == 'image' && get_setting('disable_image_optimization') != 1){
                    try {
                        $img = Image::make($request->attachments->getRealPath())->encode();
                        $height = $img->height();
                        $width = $img->width();
                        if($width > $height && $width > 1500){
                            $img->resize(1500, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }elseif ($height > 1500) {
                            $img->resize(null, 800, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $img->save(base_path('public/').$path);
                        clearstatcache();
                        $size = $img->filesize();

                    } catch (\Exception $e) {
                        //dd($e);
                    }
                }
                
                if (env('FILESYSTEM_DRIVER') == 's3') {
                    Storage::disk('s3')->put(
                        $path,
                        file_get_contents(base_path('public/').$path),
                        [
                            'visibility' => 'public',
                            'ContentType' =>  $extension == 'svg' ? 'image/svg+xml' : $file_mime
                        ]
                    );
                }

                $upload->extension = $extension;
                $upload->file_name = $path;
                $upload->user_id = auth()->user()->id;
                $upload->type = $type[$upload->extension];
                $upload->file_size = $size;
                $upload->save();
                $ticket->files = $upload->id;
            }
        }
        // dd($ticket);
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
            Mail::to(User::where('user_type', 'admin')->first()->email)->send(new SupportMailManager($array));
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
