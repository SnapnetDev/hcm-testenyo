<?php

namespace App\Jobs;

use App\FacialVerifyRequest;
use App\Mail\SendAttachMail;
use App\Traits\FaceMatchTrait;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class VerifyFacialJob /*implements ShouldQueue*/
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, facematchtrait;

    protected $facial_request;


    /**
     * VerifyFacialJob constructor.
     * @param $facial_request
     */
    public function __construct($facial_request)
    {
        $this->facial_request = $facial_request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $facial_request_id = $this->facial_request;
        $facial_request = FacialVerifyRequest::find($facial_request_id);

        $url = $facial_request->image_url;
        try {
            $res = $this->faceDetectandMatch($url);
            if (isset($res->error->message)) {
                throw new \Exception($res->error->message);
            }
            $newRes = [];
            foreach ($res as $response) {
                $response = (array)$response;
                $newRes[] = array_merge($response, ['user' => User::where('image_id', $response['persistedFaceId'])->with('company')->first()]);
            }
            $users = $newRes;
            FacialVerifyRequest::where('id',$facial_request_id)->update(['response'=>$users,'status'=>'success']);

            $from='info@snapnet.com.ng';
            $subject='Facial Recognition Result';
            $view_t='emails.emaildailyshift';   //plain email
            $view_template='email.facial_result';//html to be converted to pdf
            $data=['mail'=>'Facial Request Result is ready','users'=>$users,'facial_request'=>$facial_request];
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView($view_template, compact('data'))->setPaper('a4', 'landscape');

            Mail::to($facial_request->user->email)->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));

        } catch (\Exception $exception) {
            FacialVerifyRequest::where('id',$facial_request_id)->update(['status'=>'failed','response'=>$exception->getMessage()]);
        }

    }
}
