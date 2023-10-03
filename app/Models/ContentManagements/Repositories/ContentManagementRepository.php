<?php


namespace App\Models\ContentManagements\Repositories;


use App\Enums\ActionType;
use App\Enums\ContentManagementCodeKey;
use App\Enums\GlobalEnum;
use App\Helpers\FileHelper;
use App\Helpers\FunctionHelper;
use App\Models\ContentManagements\ContentManagement;
use App\Models\ContentManagements\Interfaces\ContentManagementRepositoryInterface;
use Ramsey\Uuid\Uuid;

class ContentManagementRepository implements ContentManagementRepositoryInterface
{
    public function get($keyCode, $tradeName, $returnValue = false)
    {
        $data = ContentManagement::where('key_code', $keyCode)
            ->where('trade_name', $tradeName)
            ->first();

        if ($data && $returnValue)
            return json_decode($data->value, true);

        return $data;
    }

    public function updatePartner($request)
    {
        $bucketFolder = GlobalEnum::CONTENT_MANAGEMENT_FOLDER;
        $fullFolder = env('BUCKET_ENV') . $bucketFolder;
        $data = $this->get(ContentManagementCodeKey::PARTNER, $request->trade_name);
        $savedValue = json_decode($data->value, true);

        $savedValue['title'] = $request->title;
        $savedValue['description'] = $request->description;
        $savedValue['sub_title'] = $request->sub_title;

        if (isset($request->image)) {

            if (isset($savedValue['image']) && $savedValue['image'] != null) {
                $fileName = FileHelper::getFileNameFromFullPathUrl($savedValue['image']);
                FileHelper::deleteFile($fullFolder, $fileName);
            }

            $fileName = FileHelper::saveFileById($fullFolder, $request->file('image'), Uuid::uuid4());
            $savedValue['image'] = FileHelper::getFileUrl($bucketFolder, $fileName);
        }
        $savedValue['terms'] = $request->terms;
        $itemsToSave = isset($savedValue['benefits']) ? $savedValue['benefits'] : [];

        // Save Items
        if ($request->benefits) {
            foreach ($request->benefits as $item) {
                $action = $item['action'];

                if ($action == ActionType::CREATE) {
                    $id = Uuid::uuid4();
                    $fileName = FileHelper::saveFileById($fullFolder, $item['image'], $id);

                    array_push($itemsToSave, [
                        'id'    => $id,
                        'title' => $item['title'],
                        'image' => FileHelper::getFileUrl($bucketFolder, $fileName)
                    ]);

                } else if ($action == ActionType::UPDATE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];
                        $itemFound['title'] = $item['title'];

                        if (isset($item['image'])) {
                            $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                            FileHelper::deleteFile($fullFolder, $fileName);

                            $fileName = FileHelper::saveFileById($fullFolder, $item['image'], Uuid::uuid4());
                            $itemFound['image'] = FileHelper::getFileUrl($bucketFolder, $fileName);
                        }

                        $itemsToSave[$keyFound] = $itemFound;
                    }

                } else if ($action == ActionType::DELETE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];

                        $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                        FileHelper::deleteFile($fullFolder, $fileName);

                        unset($itemsToSave[$keyFound]);
                        $itemsToSave = array_values($itemsToSave);
                    }

                }
            }
        }

        $savedValue['benefits'] = $itemsToSave;

        // Save file
        if (isset($request->file)) {
            if (isset($savedValue['file']) && $savedValue['file'] != null) {
                $fileName = FileHelper::getFileNameFromFullPathUrl($savedValue['file']);
                FileHelper::deleteFile($fullFolder, $fileName);
            }

            $fileName = FileHelper::saveFileById($fullFolder, $request->file('file'), Uuid::uuid4());
            $savedValue['file'] = FileHelper::getFileUrl($bucketFolder, $fileName);
        }

        $data->value = $savedValue;
        $data->save();
        return $data->value;
    }

    public function updateCorporate($request)
    {
        $bucketFolder = GlobalEnum::CONTENT_MANAGEMENT_FOLDER;
        $fullFolder = env('BUCKET_ENV') . $bucketFolder;
        $data = $this->get(ContentManagementCodeKey::CORPORATE, $request->trade_name);
        $savedValue = json_decode($data->value, true);

        $savedValue['title'] = $request->title;
        $savedValue['description'] = $request->description;
        $savedValue['email'] = $request->email;

        if (isset($request->image)) {

            if (isset($savedValue['image']) && $savedValue['image'] != null) {
                $fileName = FileHelper::getFileNameFromFullPathUrl($savedValue['image']);
                FileHelper::deleteFile($fullFolder, $fileName);
            }

            $fileName = FileHelper::saveFileById($fullFolder, $request->file('image'), Uuid::uuid4());
            $savedValue['image'] = FileHelper::getFileUrl($bucketFolder, $fileName);
        }

        $itemsToSave = isset($savedValue['services']) ? $savedValue['services'] : [];

        // Save Items
        if ($request->services) {
            foreach ($request->services as $item) {
                $action = $item['action'];

                if ($action == ActionType::CREATE) {
                    $id = Uuid::uuid4();
                    $fileName = FileHelper::saveFileById($fullFolder, $item['image'], $id);

                    array_push($itemsToSave, [
                        'id'          => $id,
                        'title'       => $item['title'],
                        'description' => $item['description'],
                        'image'       => FileHelper::getFileUrl($bucketFolder, $fileName)
                    ]);

                } else if ($action == ActionType::UPDATE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];
                        $itemFound['title'] = $item['title'];
                        $itemFound['description'] = $item['description'];

                        if (isset($item['image'])) {
                            $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                            FileHelper::deleteFile($fullFolder, $fileName);

                            $fileName = FileHelper::saveFileById($fullFolder, $item['image'], Uuid::uuid4());
                            $itemFound['image'] = FileHelper::getFileUrl($bucketFolder, $fileName);
                        }

                        $itemsToSave[$keyFound] = $itemFound;
                    }

                } else if ($action == ActionType::DELETE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];

                        $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                        FileHelper::deleteFile($fullFolder, $fileName);

                        unset($itemsToSave[$keyFound]);
                        $itemsToSave = array_values($itemsToSave);
                    }

                }
            }
        }

        $savedValue['services'] = $itemsToSave;

        $data->value = $savedValue;
        $data->save();
        return $data->value;
    }

    public function updateAbout($request)
    {
        $bucketFolder = GlobalEnum::CONTENT_MANAGEMENT_FOLDER;
        $fullFolder = env('BUCKET_ENV') . $bucketFolder;
        $data = $this->get(ContentManagementCodeKey::ABOUT, $request->trade_name);
        $savedValue = json_decode($data->value, true);

        $savedValue['title'] = $request->title;

        $itemsToSave = isset($savedValue['items']) ? $savedValue['items'] : [];

        // Save Items
        if ($request->items) {
            foreach ($request->items as $item) {
                $action = $item['action'];
                $image = null;
                $id = Uuid::uuid4();

                if ($action == ActionType::CREATE) {

                    if (isset($item['image'])) {
                        $fileName = FileHelper::saveFileById($fullFolder, $item['image'], $id);
                        $image = FileHelper::getFileUrl($bucketFolder, $fileName);
                    }

                    array_push($itemsToSave, [
                        'id'          => $id,
                        'description' => $item['description'],
                        'image'       => $image
                    ]);

                } else if ($action == ActionType::UPDATE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];
                        $itemFound['description'] = $item['description'];

                        if (isset($item['image'])) {
                            $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                            FileHelper::deleteFile($fullFolder, $fileName);

                            $fileName = FileHelper::saveFileById($fullFolder, $item['image'], Uuid::uuid4());
                            $itemFound['image'] = FileHelper::getFileUrl($bucketFolder, $fileName);
                        }

                        $itemsToSave[$keyFound] = $itemFound;
                    }

                } else if ($action == ActionType::DELETE) {

                    $id = $item['id'];
                    $keyFound = FunctionHelper::searchArrayForKey($itemsToSave, 'id', $id);

                    if ($keyFound !== null) {
                        $itemFound = $itemsToSave[$keyFound];

                        $fileName = FileHelper::getFileNameFromFullPathUrl($itemFound['image']);
                        FileHelper::deleteFile($fullFolder, $fileName);

                        unset($itemsToSave[$keyFound]);
                        $itemsToSave = array_values($itemsToSave);
                    }

                }
            }
        }

        $savedValue['items'] = $itemsToSave;

        $data->value = $savedValue;
        $data->save();
        return $data->value;
    }

    public function updateTerm($request)
    {
        $data = $this->get(ContentManagementCodeKey::TERMS, $request->trade_name);
        $savedValue = json_decode($data->value, true);
        $savedValue['terms'] = $request->terms;
        $data->value = $savedValue;
        $data->save();
        return $data->value;
    }

    public function updatePopupBanner($request)
    {
        $bucketFolder = GlobalEnum::CONTENT_MANAGEMENT_FOLDER;
        $fullFolder = env('BUCKET_ENV') . $bucketFolder;
        $data = $this->get(ContentManagementCodeKey::POPUP_BANNER, $request->trade_name);
        $savedValue = json_decode($data->value, true);

        if (isset($savedValue['image']) && $savedValue['image'] != null) {
            $fileName = FileHelper::getFileNameFromFullPathUrl($savedValue['image']);
            FileHelper::deleteFile($fullFolder, $fileName);
        }

        $fileName = FileHelper::saveFileById($fullFolder, $request->file('image'), Uuid::uuid4());
        $imageUrl = FileHelper::getFileUrl($bucketFolder, $fileName);

        $updatedValues = [
            'image' => $imageUrl,
            'button_name' => $request->button_name ?? $savedValue['button_name'],
            'movie_title' => $request->movie_title ?? $savedValue['movie_title'],
            'popup_title' => $request->popup_title ?? $savedValue['popup_title'],
            'movie_id' => $request->movie_id ?? $savedValue['movie_id']
        ];

        $data->value = array_merge($savedValue, $updatedValues);
        $data->save();
        return $data->value;
    }
}