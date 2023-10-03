<?php


namespace App\Models\Cards\Repositories;


use Carbon\Carbon;
use App\Models\Cards\Card;
use App\Enums\BusinessName;
use App\Models\Customers\Customer;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Services\PayU\Tokenization\Dtos\DeleteCreditCardDto;
use App\Services\PayU\Tokenization\Actions\CreateCreditCardToken;
use App\Services\PayU\Tokenization\Actions\DeleteCreditCardToken;
use App\Services\PayU\Tokenization\Dtos\CreateCreditCardTokenDto;
use App\Models\Cards\Repositories\Interfaces\CardRepositoryInterface;

class CardRepository implements CardRepositoryInterface
{
    private $model;

    public function __construct(Card $model)
    {
        $this->model = $model;
    }

    public function queryable()
    {
        return $this->model->query();
    }

    public function all(int $userId)
    {
        $now = Carbon::now();
        $fiveMinutesAgo = $now->subMinutes(5);

        $lastPurchase = Purchase::whereUserId($userId)
            ->where('created_at', '>', $fiveMinutesAgo)
            ->with('headquarter')
            ->latest()
            ->first();

        if ($lastPurchase) {
            $tradeName = $lastPurchase->headquarter->business_name;
        }

        $query =  $this->model->where('user_id', $userId);

        if (isset($tradeName)) {
            $query->whereBusinessName($tradeName);
        } else {
            // Agrupar por masked_number
            $query->groupBy('masked_number');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function create(array $info)
    {
        foreach ($info as $key => $data) {
            $data = [
                'token'          => $data['token'],
                'payment_method' => $data['payment_method'],
                'user_id'        => $data['user_id'],
                'full_name'      => $data['full_name'],
                'masked_number'  => $data['masked_number'],
                'business_name'  => $data['business_name']
            ];

            $alreadyExisted = false;
            $card = $this->model->where('token', $data['token'])->first();

            if ($card) {
                $alreadyExisted = true;
                $this->model->find($card->id)->update($data);
                $card = $card->refresh();
            } else
                $card = $this->model->create($data);
        }

        return ['card' => $card, 'already_existed' => $alreadyExisted];
    }

    public function delete(Card $model)
    {
        $model->delete();
    }

    public function deleteTokenization(Card $card): void
    {
        $dto = new DeleteCreditCardDto();
        $dto->setBusinessName(BusinessName::TOP_RANK);
        $dto->setPayerId($card->user_id);
        $dto->setCCTokenId($card->token);

        (new DeleteCreditCardToken())->execute($dto);
    }

    public function createTokenization(array $data)
    {
        $data['user_id'] = Auth::user()->id;

        $customer = Customer::where('user_id', $data['user_id'])->first();

        foreach (BusinessName::ALL_DATA as $key => $business) {
            $dto = new CreateCreditCardTokenDto();

            $dto->setBusinessName($business['id']);
            $dto->setPayerId($data['user_id']);
            $dto->setName($data['full_name']);
            $dto->setIdentificationNumber($customer->document_number);
            $dto->setPaymentMethod($data['payment_method']);
            $dto->setNumber($data['number']);
            $dto->setExpirationDate('20' . $data['expiration_date']);

            $data['business_name'] = $business['id'];

            $response = (new CreateCreditCardToken())->execute($dto);
            $info[] =  array_merge($data, $response);
        }

        return $info;
    }
}
