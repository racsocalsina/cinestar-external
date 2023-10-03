<?php


namespace App\Models\Purchases\Repositories;


use App\Enums\PromotionTypes;
use App\Enums\PurchaseStatus;
use App\Enums\SeatType;
use App\Enums\SoldItemTypes;
use App\Enums\TariffType;
use App\Enums\TicketStatus;
use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Http\Requests\Purchase\PurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Http\Requests\Purchase\UpdateSeatRequest;
use App\Http\Resources\Purchases\PurchaseErrorPaymentGatewayResource;
use App\Http\Resources\Purchases\PurchasePaymentDataResource;
use App\Http\Resources\Purchases\TicketResource;
use App\Jobs\SendErrorEmail;
use App\Models\Headquarters\Headquarter;
use App\Models\Movies\Movie;
use App\Models\MovieTimes\MovieTime;
use App\Models\MovieTimeTariffs\MovieTimeTariff;
use App\Models\PaymentGateways\PaymentGatewayInfo;
use App\Models\PaymentGateways\PaymentGatewayTransaction;
use App\Models\PurchaseErrors\PurchaseError;
use App\Models\PurchaseErrors\Repositories\Interfaces\PurchaseErrorRepositoryInterface;
use App\Models\PurchaseItems\PurchaseItem;
use App\Models\PurchaseLogs\PurchaseLog;
use App\Models\PurchasePromotion\PurchasePromotion;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Models\PurchaseSweets\PurchaseSweet;
use App\Models\PurchaseTickets\PurchaseTicket;
use App\Models\SweetsSold\SweetSold;
use App\Models\TicketAwards\TicketAward;
use App\Models\TicketPromotions\Repositories\Interfaces\TicketPromotionRepositoryInterface;
use App\Models\TicketPromotions\TicketPromotion;
use App\Models\Tickets\Ticket;
use App\SearchableRules\PurchaseSearchableRule;
use App\Services\Mail\Actions\BuildExceptionDto;
use App\Services\Searchable\Searchable;
use GuzzleHttp\Client;
use App\Enums\PurchaseStatusTransaction;
use Carbon\Carbon;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    private $model;
    private $ticketPromotionRepository;
    private $searchableService;

    public function __construct(Purchase $model, TicketPromotionRepositoryInterface $ticketPromotionRepository, Searchable $searchableService)
    {
        $this->model = $model;
        $this->ticketPromotionRepository = $ticketPromotionRepository;
        $this->searchableService = $searchableService;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function create(PurchaseRequest $request)
    {
        $movie_time = MovieTime::find($request->movie_time_id);
        $user = FunctionHelper::getApiUser();
        [$totalAmount, $totalTickets, $bought4X, $boughtGeneral, $boughtVIP] = $this->getTotals($request->tickets);

        $purchase = Purchase::create([
            'origin' => $request->origin,
            'user_id' => $user ? $user->id : null,
            'movie_time_id' => $movie_time->id,
            'movie_id' => $movie_time->movie_id,
            'headquarter_id' => $movie_time->headquarter_id,
            'status' => PurchaseStatus::PENDING,
            'amount' => $totalAmount,
            'number_tickets' => $totalTickets
        ]);

        // Create PurchaseTickets
        $purchaseTicket = PurchaseTicket::create([
            'purchase_id' => $purchase->id,
            'total' => $totalAmount
        ]);

        // Create tickets
        $this->createItems($purchase, $request->tickets, $purchaseTicket);

        // Validate if user bought 4X seats
        $graph = $movie_time->planner_graph;
        $original_graph = $movie_time->room->planner_graph;
        if (!$bought4X && !$boughtVIP) {
            $graph = $this->disable4XSeats($graph);
            $original_graph = $this->disable4XSeats($original_graph);
        }
        if ($bought4X && !$boughtGeneral && !$boughtVIP) {
            $graph_initial = $this->disableSeats($graph);
            $original_graph_initial = $this->disableSeats($original_graph);
            $graph = $this->disableFrontRow4XSeats($graph_initial);
            $original_graph = $this->disableFrontRow4XSeats($original_graph_initial);
        }
        if ($boughtVIP && !$boughtGeneral && !$bought4X){
            $graph_initial = $this->disableSeats($graph);
            $original_graph_initial = $this->disableSeats($original_graph);
            $graph = $this->disable4XSeatsAfterFirstRows($graph_initial);
            $original_graph = $this->disable4XSeatsAfterFirstRows($original_graph_initial);
        }
        if ($bought4X && $boughtVIP && !$boughtGeneral){
            $graph = $this->disableSeats($graph);
            $original_graph = $this->disableSeats($original_graph);
        }
        if ($bought4X && $boughtGeneral && !$boughtVIP){
            $graph = $this->disableFrontRow4XSeats($graph);
            $original_graph = $this->disableFrontRow4XSeats($original_graph);
        }
        if (!$bought4X && $boughtGeneral && $boughtVIP){
            $graph = $this->disable4XSeatsAfterFirstRows($graph);
            $original_graph = $this->disable4XSeatsAfterFirstRows($original_graph);
        }
        return [
            'purchase' => $purchase,
            'graph' => $graph,
            'original_graph' => $original_graph,
            'business_name' => $purchase->headquarter->business_name
        ];
    }

    public function update($id, UpdatePurchaseRequest $request)
    {
        $movie_time = MovieTime::find($request->movie_time_id);
        $purchase = Purchase::where('id', $id)->first();

        // calculate values and update total
        [$totalAmount, $totalTickets, $bought4X, $boughtGeneral, $boughtVIP] = $this->getTotals($request->tickets);
        $globalTotal = $this->getGlobalTotal($purchase->id, $totalAmount);

        $purchase->update([
            'amount' => $globalTotal,
            'number_tickets' => $totalTickets
        ]);

        // Delete tickets
        $this->deleteItems($purchase);

        // Create PurchaseTickets
        $purchaseTicket = PurchaseTicket::create([
            'purchase_id' => $purchase->id,
            'total' => $totalAmount
        ]);

        // Create tickets
        $this->createItems($purchase, $request->tickets, $purchaseTicket);

        // Validate if user bought 4X seats
        $graph = $movie_time->planner_graph;
        $original_graph = $movie_time->room->planner_graph;
        if (!$bought4X && !$boughtVIP) {
            $graph = $this->disable4XSeats($graph);
            $original_graph = $this->disable4XSeats($original_graph);
        }
        if ($bought4X && !$boughtGeneral && !$boughtVIP) {
            $graph_initial = $this->disableSeats($graph);
            $original_graph_initial = $this->disableSeats($original_graph);
            $graph = $this->disableFrontRow4XSeats($graph_initial);
            $original_graph = $this->disableFrontRow4XSeats($original_graph_initial);
        }
        if ($boughtVIP && !$boughtGeneral && !$bought4X){
            $graph_initial = $this->disableSeats($graph);
            $original_graph_initial = $this->disableSeats($original_graph);
            $graph = $this->disable4XSeatsAfterFirstRows($graph_initial);
            $original_graph = $this->disable4XSeatsAfterFirstRows($original_graph_initial);
        }
        if ($bought4X && $boughtVIP && !$boughtGeneral){
            $graph = $this->disableSeats($graph);
            $original_graph = $this->disableSeats($original_graph);
        }
        if ($bought4X && $boughtGeneral && !$boughtVIP){
            $graph = $this->disableFrontRow4XSeats($graph);
            $original_graph = $this->disableFrontRow4XSeats($original_graph);
        }
        if (!$bought4X && $boughtGeneral && $boughtVIP){
            $graph = $this->disable4XSeatsAfterFirstRows($graph);
            $original_graph = $this->disable4XSeatsAfterFirstRows($original_graph);
        }
        return [
            'purchase' => $purchase,
            'graph' => $graph,
            'original_graph' => $original_graph,
            'business_name' => $purchase->headquarter->business_name
        ];
    }

    private function createPurchaseLog($purchase)
    {
        $ticket = Ticket::where('purchase_id', $purchase->id)->get()->toArray();
        $sweetSold = SweetSold::where('purchase_id', $purchase->id)->get()->toArray();
        $purchaseTicket = PurchaseTicket::where('purchase_id', $purchase->id)->get()->toArray();
        $purchasePromotion = PurchasePromotion::where('purchase_id', $purchase->id)->get()->toArray();
        $data = [
            'ticket' => $ticket,
            'sweet_sold' => $sweetSold,
            'purchase_ticket' => $purchaseTicket,
            'purchase_promotion' => $purchasePromotion,
        ];

        PurchaseLog::create([
            'purchase_id' => $purchase->id,
            'data' => json_encode($data)
        ]);
    }

    public function deleteItems($purchase)
    {
        $this->createPurchaseLog($purchase);

        $tickets = Ticket::where('purchase_id', $purchase->id)->whereNotNull('uuid')->get();
        foreach ($tickets as $ticket) {
            Helper::deleteSeatInternal($ticket);
        }

        Ticket::where('purchase_id', $purchase->id)->forceDelete();
        SweetSold::where('purchase_id', $purchase->id)->forceDelete();
        PurchaseTicket::where('purchase_id', $purchase->id)->forceDelete();
        PurchasePromotion::where('purchase_id', $purchase->id)->forceDelete();
    }

    public function updateSeats(int $id, UpdateSeatRequest $request)
    {
        $purchase = Purchase::where('id', $id)->firstOrFail();
        $movie_time = MovieTime::find($purchase->movie_time_id);
        $seats = json_decode($movie_time->planner_meta);
        [$seat_status, $total_hall, $graph_index, $row_num] = Helper::getGraphParams($movie_time, $request->index);
        $is_4X = $seat_status == SeatType::FOUR_X;
    
        foreach ($seats as $s) {
            if ($s->index == $request->index) {
                $seat = $s;
                break;
            }
        }

        if (!$request->status) {
            $ticket = Ticket::where('purchase_id', $purchase->id)->where('planner_index', $request->index)
                //->movieTariff4X($is_4X)
                ->first();
            if (!$ticket) return null;
            $update = [
                'planner_index' => null,
                'chair_row' => null,
                'chair_column' => null,
                'seat_name' => null,
                'uuid' => null,
                'status' => TicketStatus::CREATED
            ];

            Helper::deleteSeatInternal($ticket);
        } else {
            $ticket = Ticket::where('purchase_id', $purchase->id)
                ->where(function ($q) use ($request) {
                    $q->whereNull('planner_index')
                        ->orWhere('planner_index', $request->index);
                })
                ->movieTariff4X($is_4X, $row_num)
                ->first();

            if (!$ticket || (is_null($ticket->planner_index) && !in_array($seat_status, SeatType::AVAILABLE_TYPES))) {
                return [
                    'index' => $request->index,
                    'status' => false
                ];
            }
            $column = intval($seat->column) > 9 ?  intval($seat->column) : "0".intval($seat->column);
            $update = [
                'planner_index' => $request->index,
                'chair_row' => $seat->row,
                'chair_column' => $column,
                'seat_name' => $seat->row . $column,
            ];

            $res = Helper::reserveSeatInternal($ticket->purchase, $update);

            $update['uuid'] = $res['data']['uuid'];
        }
        $ticket->update($update);
        return new TicketResource($ticket);
    }

    public function getGraphByUser($id)
    {
        $purchase = Purchase::where('id', $id)->firstOrFail();
        $tickets = Ticket::where('purchase_id', $purchase->id)->get();
        $graph = $purchase->movie_time->planner_graph;
        $bought4X = false;
        $boughtVIP = false;
        $boughtGeneral = false;
        $seats = [];
        foreach ($tickets as $ticket) {
            $movie_time_tariff = MovieTimeTariff::find($ticket['movie_time_tariff_id']);
            $name = $movie_time_tariff->movie_tariff->name;
            if (strpos($name, TariffType::FOUR_X) !== false) $bought4X = true;
            elseif(strpos($name, TariffType::VIP) !== false) $boughtVIP = true;
            else $boughtGeneral = true;
            if (!is_null($ticket['planner_index'])) {
                $index = Helper::getGraphIndex($purchase->movie_time, $ticket->planner_index);
                $graph = substr_replace($graph, SeatType::RESERVED, $index, 1);
                array_push($seats, [
                    'seat_name' => $ticket->seat_name,
                    'index' => $ticket->planner_index
                ]);
            }
        }
        if (!$bought4X && !$boughtVIP) {
            $graph = $this->disable4XSeats($graph);
        }
        if ($bought4X && !$boughtGeneral && !$boughtVIP) {
            $graph_initial = $this->disableSeats($graph);
            $graph = $this->disableFrontRow4XSeats($graph_initial);
        }
        if ($boughtVIP && !$boughtGeneral && !$bought4X){
            $graph_initial = $this->disableSeats($graph);
            $graph = $this->disable4XSeatsAfterFirstRows($graph_initial);
        }
        if ($bought4X && $boughtVIP && !$boughtGeneral){
            $graph = $this->disableSeats($graph);
        }
        if ($bought4X && $boughtGeneral && !$boughtVIP){
            $graph = $this->disableFrontRow4XSeats($graph);
        }
        if (!$bought4X && $boughtGeneral && $boughtVIP){
            $graph = $this->disable4XSeatsAfterFirstRows($graph);
        }

        return [
            'graph' => $graph,
            'seats' => $seats
        ];
    }

    public function updateAsConfirmed(Purchase $purchase, $response): Purchase
    {
        /**@var Purchase $purchase * */
        $purchase = $purchase->refresh();
        $paymentGatewayInfo = PaymentGatewayInfo::where('purchase_id', $purchase->id)->first();
        
        $purchase->transaction_status = PurchaseStatusTransaction::PAYMENT_CONFIRMED;
        $purchase->status = PurchaseStatus::CONFIRMED;
        $purchase->confirmed = true;
        $purchase->sold_item_types = $this->getSoldItemTypesOfPurchase($purchase->id);
        $purchase->guid = FunctionHelper::createGuid();
        $purchase->voucher_type = $paymentGatewayInfo->voucher_type;

        // Add new record
        PaymentGatewayTransaction::create([
            'payment_gateway_info_id' => $paymentGatewayInfo->id,
            'response' => $response
        ]);

        $purchase->save();

        return $purchase;
    }

    public function getPurchasePaymentData($id)
    {
        $purchase = Purchase::with([
            'headquarter', 'payment_gateway_info.payment_gateway_transaction',
            'movie.gender', 'tickets.movie_time_tariff.movie_tariff',
            'sweets_sold.product', 'purchase_ticket', 'purchase_sweet'
        ])
            ->where('purchases.id', $id)
            ->first();

        if ($purchase->status == PurchaseStatus::ERROR_PAYMENT_GATEWAY) {
            $purchaseError = PurchaseError::where('purchase_id', $purchase->id)
                ->where('status', PurchaseStatus::ERROR_PAYMENT_GATEWAY)
                ->orderBy('id', 'desc')
                ->first();
            return new PurchaseErrorPaymentGatewayResource($purchaseError);
        }

        return (new PurchasePaymentDataResource($purchase))->setAction('show');
    }

    public function getAllConfirmedPurchasePaymentByUser($userId)
    {
        return Purchase::selectRaw("purchases.*")
            ->with([
                'headquarter', 'payment_gateway_info.payment_gateway_transaction',
                'movie.gender', 'tickets.movie_time_tariff.movie_tariff',
            ])
            ->join('payment_gateway_info', 'purchases.id', 'payment_gateway_info.purchase_id')
            ->leftJoin('movie_times', 'purchases.movie_time_id', 'movie_times.id')
            ->where('purchases.user_id', $userId)
            ->where('purchases.confirmed', true)
            ->whereRaw('payment_gateway_info.deleted_at IS NULL')
            // ->orderByRaw("(case when purchases.movie_time_id is null then payment_gateway_info.created_at else movie_times.start_at end) desc")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function destroy($id)
    {
        $purchase = Purchase::where('id', $id)
            ->where('confirmed', false)
            ->first();

        if ($purchase) {
            $this->deleteItems($purchase);
            $purchase->delete();
        }
    }

    public function getByRemoteKey($remoteKey, $headquarter_id)
    {
        $data = null;
        $sold_item_type = SoldItemTypes::TICKET;
        $purchaseTicketOrSweet = PurchaseTicket::where('remote_movkey', $remoteKey)
        ->whereHas('purchase', function ($query) use ($headquarter_id) {
            $query->where('headquarter_id', $headquarter_id);
        })
        ->first();

        if (is_null($purchaseTicketOrSweet)) {
            $sold_item_type = SoldItemTypes::SWEET;
            $purchaseTicketOrSweet = PurchaseSweet::where('remote_movkey', $remoteKey)
            ->whereHas('purchase', function ($query) use ($headquarter_id) {
                $query->where('headquarter_id', $headquarter_id);
            })
            ->first();
        }

        if (is_null($purchaseTicketOrSweet)) {
            return null;
        }

        if ($sold_item_type == SoldItemTypes::TICKET) {
            $data = Purchase::with([
                'movie_time.movie',
                'movie_time.room',
                'tickets.movie_time_tariff.movie_tariff',
                'tickets.purchaseItem',
                'payment_gateway_info',
                'headquarter',
                'purchase_ticket'
            ])->find($purchaseTicketOrSweet->purchase_id);

        } else {
            $data = Purchase::with([
                'payment_gateway_info',
                'headquarter',
                'sweets_sold',
                'sweets_sold.product_by_code',
                'purchase_sweet'
            ])->find($purchaseTicketOrSweet->purchase_id);
        }

        return [
            'data' => $data,
            'sold_item_type' => $sold_item_type
        ];
    }

    public function searchBO($request)
    {
        $query = Purchase::with([
            'purchase_ticket.purchase_voucher', 'purchase_sweet.purchase_voucher', 'headquarter', 'user.customer', 'movie',
            'payment_gateway_info.payment_gateway_transaction', 'sweets_sold.product',
            'movie_time.room', 'purchase_items', 'tickets.movie_time_tariff.movie_tariff', 'movie_time'
        ]);
        $this->searchableService->applyArray($query, new PurchaseSearchableRule(), $request);
        return $query
            ->forBO()
            ->orderBy('created_at', 'desc')
            ->paginate(Helper::perPage($request));
    }

    public function transactionSearchBO($request)
    {
        $query = Purchase::where(function ($query) {
            $query->whereNotNull('transaction_status');
        })
        ->with([
            'purchase_ticket.purchase_voucher', 'purchase_sweet.purchase_voucher', 'headquarter', 'user.customer', 'movie',
            'payment_gateway_info.payment_gateway_transaction', 'sweets_sold.product',
            'movie_time.room', 'purchase_items', 'tickets.movie_time_tariff.movie_tariff', 'movie_time'
        ]);

        $this->searchableService->applyArray($query, new PurchaseSearchableRule(), $request);
        return $query
            ->transactionForBO()
            ->orderBy('created_at', 'desc')
            ->paginate(Helper::perPage($request));
    }

    private function getGlobalTotal($purchaseId, $ticketTotal)
    {
        $purchaseSweet = PurchaseSweet::where('purchase_id', $purchaseId)->first();
        $sweetTotal = $purchaseSweet ? $purchaseSweet->total : 0;

        return floatval($ticketTotal) + floatval($sweetTotal);
    }

    private function createItems(Purchase $purchase, $tickets, $purchaseTicket)
    {
        foreach ($tickets as $ticket) {
            $movie_time_tariff = MovieTimeTariff::find($ticket['movie_time_tariff_id']);
            $promotion = null;
            if ($ticket['type'] === PromotionTypes::PREMIO) {
                $ticket_award = TicketAward::find($ticket['promotion_id']);
                $purchase_promotion = $ticket_award->purchase_promotion()->create([
                    'purchase_id' => $purchase->id,
                    'qty' => $ticket['quantity']
                ]);
                [$amount, $prices] = $this->ticketPromotionRepository->aplicatePromotion($movie_time_tariff, $ticket_award->promotion);
                $priceIndex = 0;
                $ticket_numbers = $ticket_award->promotion->tickets_number * $ticket['quantity'];
                for ($i = 0; $i < $ticket_numbers; $i++) {
                    if ($priceIndex >= count($prices)) {
                        $priceIndex = 0;
                    }
                    $this->saveTicket($purchase, $prices[$priceIndex], $purchaseTicket, $movie_time_tariff, $purchase_promotion);
                    $priceIndex++;
                }
            } else if ($ticket['type'] === PromotionTypes::PROMOCION || $ticket['type'] === PromotionTypes::CODIGO) {
                $codes = null;
                $promotion = TicketPromotion::find($ticket['promotion_id']);
                if ($ticket['type'] === PromotionTypes::CODIGO && count($ticket['codes'])) {
                    $codes = $ticket['codes'];
                }
                $purchase_promotion = $promotion->purchase_promotion()->create([
                    'purchase_id' => $purchase->id,
                    'qty' => $ticket['quantity'],
                    'codes' => $codes
                ]);
                [$amount, $prices] = $this->ticketPromotionRepository->aplicatePromotion($movie_time_tariff, $promotion);
                $priceIndex = 0;
                $ticket_numbers = $promotion->ticket_qty * $ticket['quantity'];
                if($ticket['type'] === PromotionTypes::CODIGO){
                    $unit_price = number_format($prices[1]/$promotion->ticket_qty, 2);
                    for ($i = 0; $i < $ticket_numbers; $i++) {
                        $this->saveTicket($purchase,$unit_price, $purchaseTicket, $movie_time_tariff, $purchase_promotion);
                    }
                }else{
                    for ($i = 0; $i < $ticket_numbers; $i++) {
                        if ($priceIndex >= count($prices)) {
                            $priceIndex = 0;
                        }
                        $this->saveTicket($purchase, $prices[$priceIndex], $purchaseTicket, $movie_time_tariff, $purchase_promotion);
                        $priceIndex++;
                    }
                }
            } else {
                for ($i = 0; $i < $ticket['quantity']; $i++) {
                    $this->saveTicket($purchase, $movie_time_tariff->online_price, $purchaseTicket, $movie_time_tariff);
                }
            }


        }
    }

    private function saveTicket($purchase, $price, $purchaseTicket, $movie_time_tariff, $promotion = null)
    {
        $item = PurchaseItem::create([
            'original_amount' => $price,
            'paid_amount' => $price,
            'purchase_id' => $purchase->id,
            'purchase_ticket_id' => $purchaseTicket->id
        ]);

        Ticket::create([
            'purchase_id' => $purchase->id,
            'purchase_item_id' => $item->id,
            'movie_time_tariff_id' => $movie_time_tariff->id,
            'purchase_promotion_id' => $promotion ? $promotion->id : null
        ]);
    }

    private function disable4XSeats($graph)
    {
        return str_replace(SeatType::FOUR_X, SeatType::UNAVAILABLE, $graph);
    }

    private function disableSeats($graph)
    {
        $seats = [SeatType::AVAILABLE, SeatType::WHEELCHAIR];
        return str_replace($seats, SeatType::UNAVAILABLE, $graph);
    }

    private function disableFrontRow4XSeats($graph)
    {
        //$seats = [SeatType::AVAILABLE, SeatType::WHEELCHAIR];
        //$graph_initial = str_replace($seats, SeatType::UNAVAILABLE, $graph);
        // Dividir el mapa de asientos en filas
        $rows = explode("/", $graph);
        // Desactivar los asientos 4X solo en la primera fila
        $first_row = $rows[0];
        $first_row = str_replace(SeatType::FOUR_X, SeatType::UNAVAILABLE, $first_row);
        // Volver a unir las filas modificadas
        $rows[0] = $first_row;
        $graph = implode("/", $rows);

        return $graph;
    }

    private function disable4XSeatsAfterFirstRows($graph)
    {
        //$seats = [SeatType::AVAILABLE, SeatType::WHEELCHAIR];
        //$graph_initial = str_replace($seats, SeatType::UNAVAILABLE, $graph);
        $rows = explode('/', $graph);
        foreach ($rows as $index => $row) {
            if ($index > 0) { // Ignorar la primera fila
                $rows[$index] = str_replace(SeatType::FOUR_X, SeatType::UNAVAILABLE, $row);
            }
        }
        return implode('/', $rows);
    }

    public function getSoldItemTypesOfPurchase($purchaseId)
    {
        $array = [];
        $purchaseTicketsExists = PurchaseTicket::where('purchase_id', $purchaseId)->count() > 0;
        $purchaseSweetsExists = PurchaseSweet::where('purchase_id', $purchaseId)->count() > 0;

        if ($purchaseTicketsExists)
            array_push($array, SoldItemTypes::TICKET);

        if ($purchaseSweetsExists)
            array_push($array, SoldItemTypes::SWEET);

        return implode(',', $array);
    }

    private function getTotals($tickets)
    {
        $totalAmount = 0;
        $totalTickets = 0;
        $bought4X = false;
        $boughtVIP = false;
        $boughtGeneral = false;
        foreach ($tickets as $ticket) {
            $movie_time_tariff = MovieTimeTariff::find($ticket['movie_time_tariff_id']);
            $name = $movie_time_tariff->movie_tariff->name;
            if ($ticket['type'] === PromotionTypes::PREMIO) {
                $ticket_award = TicketAward::find($ticket['promotion_id']);
                [$amount, $prices] = $this->ticketPromotionRepository->aplicatePromotion($movie_time_tariff, $ticket_award->promotion);
                $totalAmount += $amount * $ticket['quantity'];
                $totalTickets += $ticket_award->promotion->tickets_number * $ticket['quantity'];
            } else if ($ticket['type'] === PromotionTypes::PROMOCION || $ticket['type'] === PromotionTypes::CODIGO) {
                $promotion = TicketPromotion::find($ticket['promotion_id']);
                [$amount, $prices] = $this->ticketPromotionRepository->aplicatePromotion($movie_time_tariff, $promotion);
                $totalAmount += $amount * $ticket['quantity'];
                $totalTickets += $promotion->ticket_qty * $ticket['quantity'];
            } else {
                $totalAmount += $movie_time_tariff->online_price * $ticket['quantity'];
                $totalTickets += $ticket['quantity'];
            }

            if (strpos($name, TariffType::FOUR_X) !== false) $bought4X = true;
            elseif(strpos($name, TariffType::VIP) !== false) $boughtVIP = true;
            else $boughtGeneral = true;
        }
        return [$totalAmount, $totalTickets, $bought4X, $boughtGeneral, $boughtVIP];
    }

    public function getTotalData($params)
    {
        $month = $params['month'];
        $year = $params['year'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $headquarter_id = $params['headquarter_id'];
        $movie_id = $params['movie_id'];
        $schedule = $params['schedule'];
        $is_presale = $params['is_presale'];

        $total = PurchaseItem::selectRaw('sum(paid_amount) as total_amount, count(*) as total_count')
            ->join('purchases', 'purchase_items.purchase_id', 'purchases.id')
            ->join('movie_times', 'purchases.movie_time_id', 'movie_times.id')
            ->join('movies', 'movie_times.movie_id', 'movies.id')
            ->whereNotNull('purchase_ticket_id')
            ->when($month, function ($q) use ($month, $year) {
                $q->whereRaw('MONTH(purchases.created_at) =' . $month)
                ->whereRaw('YEAR(purchases.created_at) =' . $year);
            })
            ->when($start_date, function ($q) use ($start_date, $end_date) {
                $q->whereDate('purchases.created_at', '>=', $start_date)
                    ->whereDate('purchases.created_at', '<=', $end_date);
            })
            ->when($headquarter_id, function ($q) use ($headquarter_id) {
                $q->where('purchases.headquarter_id', $headquarter_id);
            })
            ->when($movie_id, function ($q) use ($movie_id) {
                $q->where('purchases.movie_id', $movie_id);
            })
            ->when($schedule, function ($q) use ($schedule) {
                $q->where('movie_times.time_start', 'like', $schedule);
            })
            ->when($is_presale, function ($q) {
                $q->whereRaw('purchases.created_at < movies.premier_date');
            })
            ->where('purchases.status', PurchaseStatus::COMPLETED)
            ->first();
        $total_remote = 0;
        if (!is_null($headquarter_id) && !$is_presale) {
            $movie = $movie_id ? Movie::find($movie_id) : null;
            $headquarter = Headquarter::find($headquarter_id);
            $data = [
                'month' => $month,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'funpel' => $movie ? $movie->code : null,
                'schedule' => substr(str_replace(':', '', $schedule), 0, 4)
            ];
            $internal = $this->getInternalTotals($data, $headquarter);
            $total_remote = $internal['total_remote'];
        }
        $total['total_amount'] = $total['total_amount'] ? $total['total_amount'] : 0;
        $total['total_tickets'] = $total['total_count'] + $total_remote;
        $total['total_remote'] = $total_remote;

        return $total;
    }

    public function getInternalTotals($data, $headquarter)
    {
        $token = Helper::loginInternal($headquarter);
        $api_url = Helper::addSlashToUrl($headquarter['api_url']);
        $client = new Client();
        $URL_GET_TOTALS = $api_url . "api/v1/consumer/purchase-tickets-totals";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $params['json'] = $data;
        $params['headers'] = $headers;
        $response = $client->post($URL_GET_TOTALS, $params);
        $body = (string)$response->getBody();
        $body = json_decode($body, true);
        return $body;
    }

    public function getSchedules($params)
    {
        $headquarter_id = $params['headquarter_id'];
        $movie_id = $params['movie_id'];
        return Purchase::select('movie_times.time_start')
            ->join('movie_times', 'purchases.movie_time_id', 'movie_times.id')
            ->where('purchases.status', PurchaseStatus::COMPLETED)
            ->when($headquarter_id, function ($q) use ($headquarter_id) {
                $q->where('headquarter_id', $headquarter_id);
            })
            ->when($movie_id, function ($q) use ($movie_id) {
                $q->where('movie_id', $movie_id);
            })
            ->orderBy('movie_times.time_start')
            ->groupBy('movie_times.time_start')
            ->get();
    }

    public function purchaseIsFree($purchase)
    {
        return $purchase->amount == 0;
    }

    public function updateAsError($errorStatus, $purchase, $emailSubject, $exception)
    {
        $code = $exception->getCode();
        $message = "[Purchase:" . $purchase->id . " " . $exception->getMessage();
        $purchaseErrorRepository = \App::make(PurchaseErrorRepositoryInterface::class);

        // create new error record and update status
        $purchaseErrorRepository->create($purchase->id, PurchaseStatus::ERROR, $exception->getMessage());

        Purchase::find($purchase->id)->update([
            'status' => $errorStatus
        ]);

        // send email
        if($errorStatus == PurchaseStatus::ERROR_PAYMENT_GATEWAY) {
            if($code >= 500 && $code <= 599){
                $exceptionDto = (new BuildExceptionDto($exception))->build();
                $exceptionDto->setSubject('Error al procesar pago con PayU');
                $exceptionDto->setMessage($message);
//                SendErrorEmail::dispatch($exceptionDto);
                FunctionHelper::sendErrorMail($exceptionDto);
            }
        } else if($errorStatus == PurchaseStatus::ERROR_INTERNAL) {

            $emailSubject = is_null($emailSubject) ? "Error al enviar la compra a la sede {$purchase->headquarter->name}" : $emailSubject;

            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($emailSubject);
            $exceptionDto->setMessage($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        } else if($errorStatus == PurchaseStatus::ERROR_BILLING) {

            $emailSubject = is_null($emailSubject) ? "Error en el proceso del facturador" : $emailSubject;

            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($emailSubject);
            $exceptionDto->setMessage($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        } else {

            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject('[URGENTE] Compra ' . $purchase->id . ' no completada');
            $exceptionDto->setMessage($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
        }
    }

    public function getByPurchase($id)
    {
        $purchase = Purchase::where('purchase_id', $id)->firstOrFail();
        return $purchase;
    }

    public function updateStatusTransaction($id, array $data)
    {
        $purchase = Purchase::find($id)->update($data);
        return $purchase;
    }

    public function transactionsPerDay()
    {
        $currentDate = Carbon::now()->toDateString();

        // Realiza la consulta con el filtro de fecha y estado
        $countProcess = Purchase::where(function ($query) use ($currentDate) {
            $query->whereDate('created_at', $currentDate)
                ->orWhereDate('updated_at', $currentDate);
        })
        ->where('transaction_status', 'payment-in-process')
        ->count();

        $countConfirmed = Purchase::where(function ($query) use ($currentDate) {
            $query->whereDate('created_at', $currentDate)
                ->orWhereDate('updated_at', $currentDate);
        })
        ->where('transaction_status', 'payment-confirmed')
        ->count();

        $countTicketSent = Purchase::where(function ($query) use ($currentDate) {
            $query->whereDate('created_at', $currentDate)
                ->orWhereDate('updated_at', $currentDate);
        })
        ->where('transaction_status', 'ticket-sent')
        ->count();

        $countCompleted = Purchase::where(function ($query) use ($currentDate) {
            $query->whereDate('created_at', $currentDate)
                ->orWhereDate('updated_at', $currentDate);
        })
        ->where('status', 'completed')
        ->count();

        $data = [
                'paymentInProcess' => $countProcess,
                'paymentConfirmed' => $countConfirmed,
                'ticketSent' => $countTicketSent,
                'completedPurchases' => $countCompleted
            ];

        return $data;
    }

    public function transactionsPerMonth()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Realiza la consulta con el filtro de mes y estado
        $countProcess = Purchase::where(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('created_at', $currentYear)
                      ->whereMonth('created_at', $currentMonth)
                      ->where('transaction_status', 'payment-in-process');
            })
            ->orWhere(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('updated_at', $currentYear)
                      ->whereMonth('updated_at', $currentMonth)
                      ->where('transaction_status', 'payment-in-process');
            })
            ->count();

        $countConfirmed = Purchase::where(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('created_at', $currentYear)
                      ->whereMonth('created_at', $currentMonth)
                      ->where('transaction_status', 'payment-confirmed');
            })
            ->orWhere(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('updated_at', $currentYear)
                      ->whereMonth('updated_at', $currentMonth)
                      ->where('transaction_status', 'payment-confirmed');
            })
            ->count();

        $countTicketSent = Purchase::where(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('created_at', $currentYear)
                      ->whereMonth('created_at', $currentMonth)
                      ->where('transaction_status', 'ticket-sent');
            })
            ->orWhere(function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('updated_at', $currentYear)
                      ->whereMonth('updated_at', $currentMonth)
                      ->where('transaction_status', 'ticket-sent');
            })
            ->count();

        $data = [
            'paymentInProcess' => $countProcess,
            'paymentConfirmed' => $countConfirmed,
            'ticketSent' => $countTicketSent
        ];

        return $data;
    }


    public function transactionsPerWeek()
    {
        $data = [];

        // Obtener la fecha de hace 4 semanas
        $fourWeeksAgo = Carbon::now()->subWeeks(4);

        // Agrupar el conteo de registros por semana durante las últimas 4 semanas
        for ($i = 0; $i < 4; $i++) { // 4 semanas
            $startDate = $fourWeeksAgo->copy()->addWeeks($i);
            $endDate = $startDate->copy()->addDays(6);

            $count = Purchase::where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereBetween('updated_at', [$startDate, $endDate]);
            })
            ->whereRaw('JSON_EXTRACT(error_event_history, "$.error") = "true"')
            ->count();

            $data[] = [
                'week' => 'Semana ' . ($i + 1),
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
                'count' => $count,
            ];
        }

        return $data;
    }

    public function purchasesTransactionPayu($request){
        // Obtener la fecha de inicio y fin del rango
        $startDate = $request['startDate'] . ' 23:59:59';
        $endDate = $request['endDate'] . ' 00:00:00';
        
        // Obtener las IDs de compras dentro del rango de fechas
        $idsPurchase = Purchase::where(function ($query) use ($startDate, $endDate) {
            $query->where('created_at', '>=', $endDate)
                  ->where('created_at', '<=', $startDate);
        })
        ->whereNull('deleted_at')
        ->pluck('id')
        ->toArray();
        
        // Filtrar elementos que no están en la lista de compras
        $filteredData = array_filter($request['data'], function ($elemento) use ($idsPurchase) {
            return !in_array($elemento, $idsPurchase);
        });
        $result = array_values($filteredData);
        return $result;
    }
}
