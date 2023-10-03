<?php


namespace App\Models\Bins\Repositories;


use App\Enums\GlobalEnum;
use App\Models\Bins\Bin;
use App\Models\Bins\Repositories\Interfaces\BinRepositoryInterface;
use App\Models\Headquarters\Headquarter;

class BinRepository implements BinRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        if ($action == GlobalEnum::ACTION_SYNC_DELETE) {
            $bin = Bin::where('tpm_code', $data['tpm_code'])
                ->where('bin', $data['bin'])
                ->first();

            $bin->delete();
        } else if ($action == GlobalEnum::ACTION_SYNC_UPDATE) {

            $bin = Bin::where('tpm_code', $data['old_tpm_code'])
                ->where('bin', $data['old_bin'])
                ->first();

            if (!$bin) {
                Bin::create([
                    'tpm_code' => $data['tpm_code'],
                    'bin'      => $data['bin'],
                ]);
            } else {
                $bin->tpm_code = $data['tpm_code'];
                $bin->bin = $data['bin'];
                $bin->save();
            }

        } else {

            $bin = Bin::where('tpm_code', $data['tpm_code'])
                ->where('bin', $data['bin'])
                ->first();

            if (!$bin) {
                Bin::create([
                    'tpm_code' => $data['tpm_code'],
                    'bin'      => $data['bin'],
                ]);
            }
        }
    }
}