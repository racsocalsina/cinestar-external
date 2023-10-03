<?php


namespace App\Services\Reports\ExhibitorMonthly\Actions;


use App\Enums\PurchaseStatus;
use App\Helpers\FunctionHelper;
use App\Services\Reports\ExhibitorMonthly\Dtos\ExhibitorMonthlyDto;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class ExhibitorMonthly
{
    private ExhibitorMonthlyDto $params;
    private $dates;
    private $datesOfMonth;
    private $dataByMonths;
    private $defaultValue = "-";
    private $ticketEvolutionDataOfRequestYear;
    private $ticketEvolutionDataOfPreviousYear;

    public function execute(ExhibitorMonthlyDto $params): array
    {
        $this->params = $params;
        $this->dates = $this->getFromFirstDayOfYearToLastDayOfMonth();
        $this->datesOfMonth = $this->getFromFirstDayOfMonthToLastDayOfMonth();
        $this->dataByMonths = $this->getDataByMonths();

        return [
            'ticket_sales'        => $this->getTicketSales(),
            'tickets_by_channel'  => $this->getTicketsByChannel(),
            'sales_penetration'   => $this->getSalesPenetration(),
            'movie_ranking'       => $this->getMovieRanking(),
            'headquarter_ranking' => $this->getHeadquarterRanking(),
            'tickets_evolution'   => $this->getTicketEvolution()
        ];
    }

    private function getTicketSales(): array
    {
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $totalOnlineTransactionsByMonth = $this->defaultValue;
            $totalOnlineTicketByMonth = $this->defaultValue;
            $totalTicketsTransactionByMonth = $this->defaultValue;

            if ($month <= intval($this->params->getMonth())) {
                $itemFoundByMonth = $this->dataByMonths->where('month', $month)->first();
                $totalOnlineTransactionsByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_online_transactions) : 0;
                $totalOnlineTicketByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_online_tickets) : 0;
                $totalTicketsTransactionByMonth = 0;

                if ($totalOnlineTransactionsByMonth > 0){
                    $totalTicketsTransactionByMonth = number_format($totalOnlineTicketByMonth / $totalOnlineTransactionsByMonth, 2);
                }
            }

            $data['items'][] = [
                'month_number'        => $month,
                'month_name'          => FunctionHelper::getMonthNameByNumber($month),
                'online_transactions' => number_format(floatval($totalOnlineTransactionsByMonth)),
                'online_tickets'      => number_format(floatval($totalOnlineTicketByMonth)),
                'tickets_transaction' => $totalTicketsTransactionByMonth
            ];
        }

        // Totals
        $totalOnlineTransaction = $this->dataByMonths->sum('total_online_transactions');
        $totalOnlineTickets = $this->dataByMonths->sum('total_online_tickets');
        $totalTicketsTransaction = 0;

        if ($totalOnlineTransaction > 0)
            $totalTicketsTransaction = $totalOnlineTickets / $totalOnlineTransaction;

        $data['total'] = [
            'online_transactions' => number_format($totalOnlineTransaction),
            'online_tickets'      => $totalOnlineTickets,
            'tickets_transaction' => number_format($totalTicketsTransaction, 2)
        ];

        return $data;
    }

    private function getTicketsByChannel(): array
    {
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $totalAppByMonth = $this->defaultValue;
            $totalWebByMonth = $this->defaultValue;
            $totalPercentAppByMonth = $this->defaultValue;
            $totalPercentWebByMonth = $this->defaultValue;

            if ($month <= intval($this->params->getMonth())) {
                $itemFoundByMonth = $this->dataByMonths->where('month', $month)->first();
                $totalAppByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_app) : 0;
                $totalWebByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_web) : 0;
                $totalByMonth = $totalAppByMonth + $totalWebByMonth;
                $totalPercentAppByMonth = "0%";
                $totalPercentWebByMonth = "0%";

                if ($totalAppByMonth > 0)
                    $totalPercentAppByMonth = round(($totalAppByMonth / $totalByMonth) * 100) . '%';

                if ($totalWebByMonth > 0)
                    $totalPercentWebByMonth = round(($totalWebByMonth / $totalByMonth) * 100) . '%';
            }

            $data['items'][] = [
                'month_number' => $month,
                'month_name'   => FunctionHelper::getMonthNameByNumber($month),
                'tickets_app'  => number_format(floatval($totalAppByMonth)),
                'tickets_web'  => number_format(floatval($totalWebByMonth)),
                'percent_app'  => $totalPercentAppByMonth,
                'percent_web'  => $totalPercentWebByMonth
            ];
        }

        // Totals
        $totalTicketsApp = $this->dataByMonths->sum('total_app');
        $totalTicketsWeb = $this->dataByMonths->sum('total_web');
        $total = $totalTicketsApp + $totalTicketsWeb;
        $totalPercentApp = 0;
        $totalPercentWeb = 0;

        if ($totalTicketsApp > 0)
            $totalPercentApp = round(($totalTicketsApp / $total) * 100) . '%';

        if ($totalTicketsWeb > 0)
            $totalPercentWeb = round(($totalTicketsWeb / $total) * 100) . '%';

        $data['total'] = [
            'tickets_app' => number_format($totalTicketsApp),
            'tickets_web' => number_format($totalTicketsWeb),
            'percent_app' => $totalPercentApp,
            'percent_web' => $totalPercentWeb
        ];

        return $data;
    }

    private function getSalesPenetration(): array
    {
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $totalSalesByMonth = $this->defaultValue;
            $totalPercentWebByMonth = $this->defaultValue;

            if ($month <= intval($this->params->getMonth())) {
                $itemFoundByMonth = $this->dataByMonths->where('month', $month)->first();
                $totalSalesByMonth = $itemFoundByMonth ? $itemFoundByMonth->total_sales : 0;
                $totalAppByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_app) : 0;
                $totalWebByMonth = $itemFoundByMonth ? intval($itemFoundByMonth->total_web) : 0;
                $totalAppWeb = $totalAppByMonth + $totalWebByMonth;
                $totalPercentWebByMonth = "0.00%";

                if ($totalSalesByMonth > 0)
                    $totalPercentWebByMonth = number_format(($totalAppWeb / $totalSalesByMonth) * 100, 2) . '%';
            }

            $data['items'][] = [
                'month_number' => $month,
                'month_name'   => FunctionHelper::getMonthNameByNumber($month),
                'sales'        => is_string($totalSalesByMonth) ? $totalSalesByMonth : number_format($totalSalesByMonth, 2),
                'percent'      => $totalPercentWebByMonth
            ];
        }

        // Totals
        $totalApp = $this->dataByMonths->sum('total_app');
        $totalWeb = $this->dataByMonths->sum('total_web');
        $totalAppWeb = $totalApp + $totalWeb;
        $totalSales = $this->dataByMonths->sum('total_sales');
        $totalPercent = 0;

        if ($totalSales > 0)
            $totalPercent = number_format(($totalAppWeb / $totalSales) * 100, 2);

        $data['total'] = [
            'sales'   => number_format($totalSales, 2),
            'percent' => $totalPercent . '%'
        ];

        return $data;
    }

    private function getMovieRanking(): array
    {
        $movieRankingData = DB::table('purchases')
            ->selectRaw("movies.name as movie_name,                 
                SUM(purchases.number_tickets) as total_online_tickets")
            ->join('purchase_tickets', 'purchases.id', 'purchase_tickets.purchase_id')
            ->join('headquarters', 'purchases.headquarter_id', 'headquarters.id')
            ->join('movies', 'purchases.movie_id', 'movies.id');

        $this->setFilters($movieRankingData, $this->datesOfMonth);

        $movieRankingData = $movieRankingData->groupBy('movies.code')
            ->orderByRaw('total_online_tickets desc')
            ->get();

        $data = [];
        $totalOnlineTickets = $movieRankingData->sum('total_online_tickets');
        $totalOnlineTicketOfOthers = 0;
        $totalPercentByOthers = 0;
        $totalOthersPercent = 0;

        $counter = 0;
        foreach ($movieRankingData as $item) {
            $counter += 1;

            if ($counter > 10) {
                $totalOnlineTicketOfOthers += $item->total_online_tickets;

                if ($totalOnlineTickets > 0) {
                    $percent = ($item->total_online_tickets / $totalOnlineTickets) * 100;
                    $totalPercentByOthers += $percent;
                    $totalOthersPercent += $percent;
                }

            } else {
                $totalPercentByItem = 0;

                if ($totalOnlineTickets > 0)
                    $totalPercentByItem = ($item->total_online_tickets / $totalOnlineTickets) * 100;

                $data['items'][] = [
                    'movie'          => $item->movie_name,
                    'online_tickets' => number_format($item->total_online_tickets),
                    'percent'        => number_format($totalPercentByItem, 2) . '%'
                ];

                $totalOthersPercent += $totalPercentByItem;
            }
        }

        if ($counter > 10) {
            $data['items'][] = [
                'name'           => 'Otros',
                'online_tickets' => number_format($totalOnlineTicketOfOthers),
                'percent'        => number_format($totalPercentByOthers, 2) . '%'
            ];
        }

        // Totals
        $data['total'] = [
            'online_tickets' => number_format($totalOnlineTickets),
            'percent'        => round($totalOthersPercent) . '%'
        ];

        return $data;
    }

    private function getHeadquarterRanking(): array
    {
        $headquarterRankingData = DB::table('purchases')
            ->selectRaw("headquarters.name as headquarter_name,                 
                SUM(purchases.number_tickets) as total_online_tickets")
            ->join('purchase_tickets', 'purchases.id', 'purchase_tickets.purchase_id')
            ->join('headquarters', 'purchases.headquarter_id', 'headquarters.id')
            ->where('headquarters.trade_name', $this->params->getTradeName())
            ->whereDate('purchases.created_at', '>=', $this->datesOfMonth['start_date'])
            ->whereDate('purchases.created_at', '<=', $this->datesOfMonth['end_date'])
            ->where('purchases.status', PurchaseStatus::COMPLETED)
            ->groupBy('headquarters.id')
            ->orderByRaw('total_online_tickets desc')
            ->get();

        $data = [];
        $totalOnlineTickets = $headquarterRankingData->sum('total_online_tickets');
        $totalOnlineTicketOfOthers = 0;
        $totalPercentByOthers = 0;
        $totalOthersPercent = 0;

        $counter = 0;
        foreach ($headquarterRankingData as $item) {
            $counter += 1;

            if ($counter > 10) {
                $totalOnlineTicketOfOthers += $item->total_online_tickets;

                if ($totalOnlineTickets > 0) {
                    $percent = ($item->total_online_tickets / $totalOnlineTickets) * 100;
                    $totalPercentByOthers += $percent;
                    $totalOthersPercent += $percent;
                }

            } else {
                $totalPercentByItem = 0;

                if ($totalOnlineTickets > 0)
                    $totalPercentByItem = ($item->total_online_tickets / $totalOnlineTickets) * 100;

                $data['items'][] = [
                    'headquarter'         => $item->headquarter_name,
                    'online_tickets'      => number_format($item->total_online_tickets),
                    'percent'             => number_format($totalPercentByItem, 2) . '%',
                    'percent_penetration' => 0 . '%'
                ];

                $totalOthersPercent += $totalPercentByItem;
            }
        }

        if ($counter > 10) {
            $data['items'][] = [
                'headquarter'         => 'Otros',
                'online_tickets'      => number_format($totalOnlineTicketOfOthers),
                'percent'             => number_format($totalPercentByOthers, 2) . '%',
                'percent_penetration' => 0 . '%'
            ];
        }

        // Totals
        $data['total'] = [
            'online_tickets'      => number_format($totalOnlineTickets),
            'percent'             => round($totalOthersPercent) . '%',
            'percent_penetration' => 0 . '%'
        ];

        return $data;
    }

    private function getTicketEvolution(): array
    {
        // day of week empieza desde 1
        $year = intval($this->params->getYear());
        $this->ticketEvolutionDataOfRequestYear = $this->getEvolutionDataByYear($year);
        $this->ticketEvolutionDataOfPreviousYear = $this->getEvolutionDataByYear($year - 1);
        $firstDayOfRequestYear = Carbon::createFromDate($year, 1, 1);
        $firstDayOfPreviousYear = Carbon::createFromDate($year - 1, 1, 1);
        $startDateCounterOfRequestYear = null;
        $startDateCounterOfPreviousYear = null;

        $data = [];

        for ($weekNumber = 1; $weekNumber <= 52; $weekNumber++) {
            if ($weekNumber == 1) {
                // request year
                $daysToAdd = $this->calculateDaysToAdd($firstDayOfRequestYear->clone()->dayOfWeek);
                $startDateOfRequestYear = $firstDayOfRequestYear->clone();
                $endDateOfRequestYear = $firstDayOfRequestYear->clone()->addDays($daysToAdd);
                $totalTicketsCurrent = $this->getCountOfTicketEvolutionData(true, $startDateOfRequestYear->format('ymd'), $endDateOfRequestYear->format('ymd'));

                // previous year
                $daysToAdd = $this->calculateDaysToAdd($firstDayOfPreviousYear->clone()->dayOfWeek);
                $startDateOfPreviousYear = $firstDayOfPreviousYear->clone();
                $endDateOfPreviousYear = $firstDayOfPreviousYear->clone()->addDays($daysToAdd);
                $totalTicketsOld = $this->getCountOfTicketEvolutionData(false, $startDateOfPreviousYear->format('ymd'), $endDateOfPreviousYear->format('ymd'));

            } else {

                // request year
                $startDateOfRequestYear = $startDateCounterOfRequestYear;
                $endDateOfRequestYear = $weekNumber == 52 ? Carbon::createFromDate($year, 12, 31) : $startDateOfRequestYear->clone()->addDays(6);
                $totalTicketsCurrent = $this->getCountOfTicketEvolutionData(true, $startDateOfRequestYear->format('ymd'), $endDateOfRequestYear->format('ymd'));

                // previous year
                $startDateOfPreviousYear = $startDateCounterOfPreviousYear;
                $endDateOfPreviousYear = $weekNumber == 52 ? Carbon::createFromDate($year - 1, 12, 31) : $startDateOfPreviousYear->clone()->addDays(6);
                $totalTicketsOld = $this->getCountOfTicketEvolutionData(false, $startDateOfPreviousYear->format('ymd'), $endDateOfPreviousYear->format('ymd'));
            }

            $data['items'][] = [
                'week_name'             => "S{$weekNumber}",
                'total_tickets_current' => $totalTicketsCurrent,
                'total_tickets_old'     => $totalTicketsOld
            ];

            $startDateCounterOfRequestYear = $endDateOfRequestYear->clone()->addDays(1)->year > $year ?
                $endDateOfRequestYear :
                $endDateOfRequestYear->addDays(1);

            $startDateCounterOfPreviousYear = $endDateOfPreviousYear->clone()->addDays(1)->year > ($year - 1) ?
                $endDateOfPreviousYear :
                $endDateOfPreviousYear->addDays(1);
        }

        $totalRequestYear = $this->getTotalTicketsByYear($this->params->getYear());
        $totalOldYear = $this->getTotalTicketsByYear($this->params->getYear() - 1);
        $growthRate = 0;

        if($totalRequestYear != $totalOldYear){
            if($totalOldYear > 0)
                $growthRate = (($totalRequestYear - $totalOldYear) / $totalOldYear) * 100;
            else
                $growthRate = 100;
        }

        $data['total'] = [
            'request_year' => $totalRequestYear,
            'old_year'     => $totalOldYear,
            'growth_rate'  => round($growthRate)
        ];

        return $data;
    }

    private function getFromFirstDayOfYearToLastDayOfMonth(): array
    {
        $date = Carbon::createFromDate($this->params->getYear(), $this->params->getMonth(), 1);

        return [
            'start_date' => "{$this->params->getYear()}-01-01",
            'end_date'   => $date->endOfMonth()->toDateString()
        ];
    }

    private function getFromFirstDayOfYearToLastDayOfMonthByYear($year): array
    {
        $date = Carbon::createFromDate($year, $this->params->getMonth(), 1);

        return [
            'start_date' => "{$year}-01-01",
            'end_date'   => $date->endOfMonth()->toDateString()
        ];
    }

    private function getFromFirstDayOfYearToLastDayOfYear(int $year): array
    {
        return [
            'start_date' => "{$year}-01-01",
            'end_date'   => "{$year}-12-31",
        ];
    }

    private function getFromFirstDayOfMonthToLastDayOfMonth(): array
    {
        $date = Carbon::createFromDate($this->params->getYear(), $this->params->getMonth(), 1);

        return [
            'start_date' => $date->startOfMonth()->toDateString(),
            'end_date'   => $date->endOfMonth()->toDateString()
        ];
    }

    private function getDataByMonths(): Collection
    {
        $query = DB::table('purchases')
            ->selectRaw("CONVERT(DATE_FORMAT(purchases.created_at, '%m'), SIGNED) as month, 
                COUNT(*) as total,
                SUM(case when purchases.origin = 'web' then purchases.number_tickets else 0 end) as total_web,
                SUM(case when purchases.origin = 'app' then purchases.number_tickets else 0 end) as total_app,
                COUNT(*) as total_online_transactions,
                SUM(purchases.number_tickets) as total_online_tickets,
                SUM(purchase_tickets.total) as total_sales")
            ->join('purchase_tickets', 'purchases.id', 'purchase_tickets.purchase_id')
            ->join('headquarters', 'purchases.headquarter_id', 'headquarters.id');
        $this->setFilters($query, $this->dates);
        return $query->groupByRaw("DATE_FORMAT(purchases.created_at, '%m')")->get();
    }

    private function getEvolutionDataByYear($year): Collection
    {
        $dates = $this->getFromFirstDayOfYearToLastDayOfYear($year);

        $query = DB::table('purchases')
            ->selectRaw("DATE_FORMAT(purchases.created_at, '%y%m%d') as datex, 
                SUM(purchases.number_tickets) as total_online_tickets")
            ->join('purchase_tickets', 'purchases.id', 'purchase_tickets.purchase_id')
            ->join('headquarters', 'purchases.headquarter_id', 'headquarters.id');
        $this->setFilters($query, $dates);
        return $query->groupByRaw("date(purchases.created_at)")->get();
    }

    private function getTotalTicketsByYear($year): int
    {
        $dates = $this->getFromFirstDayOfYearToLastDayOfMonthByYear($year);

        $query = DB::table('purchases')
            ->join('purchase_tickets', 'purchases.id', 'purchase_tickets.purchase_id')
            ->join('headquarters', 'purchases.headquarter_id', 'headquarters.id');
        $this->setFilters($query, $dates);
        return $query->count('purchases.number_tickets');
    }

    private function calculateDaysToAdd($dayOfWeek): int
    {
        // Carbon->dayOfWeek = starts from 1
        // day to begins = Thursday (4)
        // day to ending = Wednesday (3)

        $thursdayNumber = 4;
        $wednesdayNumber = 3;

        if ($dayOfWeek <= $wednesdayNumber)
            $ret = (7 - $dayOfWeek) - $wednesdayNumber;
        else {
            $ret = (7 - $dayOfWeek) + $thursdayNumber;
        }

        return $ret - 1;
        // 1 = 3
        // 2 = 2
        // 3 = 1
        // 4 = 7
        // 5 = 6
        // 6 = 5
        // 7 = 4
    }

    private function getCountOfTicketEvolutionData(bool $isRequestYear, int $startDate, int $endDate): int
    {
        if ($isRequestYear) {
            return $this->ticketEvolutionDataOfRequestYear
                ->where('datex', '>=', $startDate)
                ->where('datex', '<=', $endDate)
                ->sum('total_online_tickets');
        } else {
            return $this->ticketEvolutionDataOfPreviousYear
                ->where('datex', '>=', $startDate)
                ->where('datex', '<=', $endDate)
                ->sum('total_online_tickets');
        }
    }

    private function setFilters(&$query, $dates)
    {
        $query
            ->where('headquarters.trade_name', $this->params->getTradeName())
            ->whereDate('purchases.created_at', '>=', $dates['start_date'])
            ->whereDate('purchases.created_at', '<=', $dates['end_date'])
            ->where('purchases.status', PurchaseStatus::COMPLETED);
    }
}