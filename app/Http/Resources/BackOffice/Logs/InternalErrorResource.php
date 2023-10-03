<?php


namespace App\Http\Resources\BackOffice\Logs;


use Illuminate\Http\Resources\Json\JsonResource;

class InternalErrorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'headquarter_name' => $this->headquarter_name,
            'db_name'          => $this->db_name,
            'job_trigger_id'   => $this->job_trigger_id,
            'actionable'       => $this->actionable,
            'actionable_id'    => $this->actionable_id,
            'action_realized'  => $this->action_realized,
            'code'             => $this->code,
            'line'             => $this->line,
            'file'             => $this->file,
            'message'          => $this->message,
            'trace'            => $this->trace,
            'request'          => $this->request,
            'created_at'       => $this->created_at->format('d/m/Y h:i:s a'),
        ];
    }
}
