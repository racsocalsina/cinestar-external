<?php


namespace App\Rules;


use App\Enums\InternalValidationResponseType;
use App\Helpers\Helper;
use App\Models\Purchases\Purchase;
use App\Models\Tickets\Ticket;
use Illuminate\Contracts\Validation\Rule;

class CheckSeatIsAvailableByIndex implements Rule
{
    protected $purchase;
    protected $index;
    protected $status;
    protected $errorMessage;

    public function __construct($purchaseId, $index, $status)
    {
        $this->purchase = Purchase::with(['movie_time', 'headquarter'])
            ->where('id', $purchaseId)->first();
        $this->index = $index;
        $this->status = $status;
    }

    public function passes($attribute, $value)
    {
        if(!$this->checkIfSeatToUnMarkBelongsToIt())
            return false;

        if(!$this->checkIfSeatIsAvailability())
            return false;

        return true;
    }

    private function checkIfSeatIsAvailability(): bool
    {
        if($this->statusAsUnmarked())
            return true;

        $seatFound = null;
        $seats = json_decode($this->purchase->movie_time->planner_meta);
        foreach ($seats as $s) {
            if ($s->index == $this->index) {
                $seatFound = $s;
                break;
            }
        }

        $column = intval($seatFound->column) > 9 ? intval($seatFound->column) : "0".intval($seatFound->column);
        $seat = $seatFound->row . $column;

        $response = Helper::checkSeatAvailability($this->purchase, $seat);
        $type = $response['data']['type'];
        $availability = $response['data']['availability'];

        if($type == InternalValidationResponseType::ERROR)
        {
            $this->errorMessage = "Se ha producido un error al validar la butaca en internal";
            return false;
        } else {
            if(!$availability)
            {
                $this->errorMessage = "Butaca {$seat} ocupada";
                return false;
            }
        }

        return true;
    }

    private function checkIfSeatToUnMarkBelongsToIt(): bool
    {
        if($this->statusAsMarked())
            return true;

        $ticket = Ticket::where('purchase_id', $this->purchase->id)
            ->where('planner_index', $this->index)
            ->first();

        if (!$ticket){
            $this->errorMessage = "No puede desmarcar esta butaca";
            return false;
        }

        return true;
    }

    private function statusAsMarked(): bool
    {
        return $this->status;
    }

    private function statusAsUnmarked(): bool
    {
        return !$this->status;
    }

    public function message()
    {
        return $this->errorMessage;
    }
}