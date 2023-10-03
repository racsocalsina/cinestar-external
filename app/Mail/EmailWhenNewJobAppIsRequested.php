<?php


namespace App\Mail;


use App\Enums\GlobalEnum;
use App\Helpers\FunctionHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWhenNewJobAppIsRequested extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $fn = $this->from(env("MAIL_FROM_ADDRESS"), ucfirst(strtolower($this->data->trade_name)))
            ->subject('Trabaja con nosotros')
            ->to(FunctionHelper::getWorkWithUsEmailByTradeName($this->data->trade_name))
            ->with(['data' => $this->data])
            ->markdown('emails.job-application');

        if($this->hasFile())
        {
            $path = config('constants.path_images').env('BUCKET_ENV').GlobalEnum::JOB_APPLICATIONS_FOLDER."/".$this->data->file_guid;

            $fn->attach($path, [
                'as'   => $this->data->file_name
            ]);
        }

        return $fn;
    }

    private function hasFile()
    {
        return $this->data->file_guid;
    }
}
