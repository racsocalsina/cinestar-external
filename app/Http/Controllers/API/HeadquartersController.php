<?php


namespace App\Http\Controllers\Api;


use App\Http\Resources\API\Headquarters\HeadquarterResourceByUrl;
use App\Models\CustomerHeadquarterFavorites\CustomerHeadquarterFavorite;
use App\Models\Customers\Customer;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Http\Controllers\ApiController;
use App\Models\Headquarters\Headquarter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadquartersController  extends ApiController
{
    private $headquarterRepository;

    public function __construct(HeadquarterRepositoryInterface $headquarterRepository)
    {
        $this->headquarterRepository = $headquarterRepository;
    }

    public function index(Request $request)
    {
        $res = $this->headquarterRepository->listHeadquarters($request);
        return $this->successResponse($res);
    }

    public function listAll(Request $request)
    {
        $res = $this->headquarterRepository->listHeadquarters($request);
        return $this->successResponse($res);
    }

    public function detailHeadquarter($headquarter)
    {
        $headquarter = Headquarter::findOrFail($headquarter);
        $res = $this->headquarterRepository->detailHeadquarter($headquarter);
        return $this->successResponse($res);
    }

    public function updateFavorite(int $headquarter_id) {

        $user = Auth::user();

        $customer = Customer::where('user_id', $user->id)->first();

        $favorite = CustomerHeadquarterFavorite::where('customer_id', $customer->id)
            ->where('headquarter_id', $headquarter_id)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            CustomerHeadquarterFavorite::create(
                [
                    'customer_id' => $customer->id,
                    'headquarter_id' => $headquarter_id
                ]
            );
        }

        return  $this->successResponse(['message' => 'Cine favorito actualizado']);
    }

    public function getByUrl(Request $request)
    {
        $headquarter = Headquarter::where('api_url', $request->url)->first();
        if($headquarter)
            return $this->successResponse(new HeadquarterResourceByUrl($headquarter));
        else
            return abort(404);
    }
}
