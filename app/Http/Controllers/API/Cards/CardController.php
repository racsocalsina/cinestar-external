<?php


namespace App\Http\Controllers\API\Cards;


use App\Helpers\FunctionHelper;
use App\Http\Controllers\ApiController;
use App\Http\Requests\API\Cards\DestroyCardRequest;
use App\Http\Requests\API\Cards\StoreCardRequest;
use App\Http\Resources\API\Cards\CardResource;
use App\Jobs\SendErrorEmail;
use App\Models\Cards\Card;
use App\Models\Cards\Repositories\Interfaces\CardRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use App\Services\PayU\Shared\Exceptions\PayuException;
use Illuminate\Support\Facades\Auth;

class CardController extends ApiController
{
    private CardRepositoryInterface $cardRepository;

    public function __construct(
        CardRepositoryInterface $cardRepository
    )
    {
        $this->cardRepository = $cardRepository;
    }

    public function index()
    {
        $data = $this->cardRepository->all(Auth::user()->id);
        return CardResource::collection($data)->additional(['status' => 200]);
    }

    public function store(StoreCardRequest $request)
    {
        try {
            $tokenizationData = $this->cardRepository->createTokenization($request->validated());
            $data = $this->cardRepository->create($tokenizationData);

            if ($data['already_existed'])
                return $this->errorResponse(['status' => 422, 'message' => __('app.cards.token_already_exists')], 422);

            return $this->created(
                new CardResource($data['card'])
            );

        } catch (PayuException $payuException) {
            return $this->errorResponse(['message' => $payuException->getMessage(), 'dev' => $payuException], 400, $payuException);

        } catch (\Exception $exception) {
            $message = "Error al guardar los datos de la tarjeta.";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            return $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);

        }
    }

    public function destroy(DestroyCardRequest $request, $token)
    {
        try {
            $card = Card::where('token', $token)->first();
            $this->cardRepository->deleteTokenization($card);
            $this->cardRepository->delete($card);
            return $this->successResponse([]);
        } catch (PayuException $payuException) {

            return $this->errorResponse(['message' => $payuException->getMessage(), 'dev' => $payuException], 400, $payuException);

        } catch (\Exception $exception) {

            $message = "Error al eliminar los datos de la tarjeta.";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
            $exceptionDto->setMessage($exception->getMessage());
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            return $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);

        }
    }
}
