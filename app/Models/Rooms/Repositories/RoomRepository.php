<?php


namespace App\Models\Rooms\Repositories;


use App\Models\Headquarters\Headquarter;
use App\Models\Rooms\Repositories\Interfaces\RoomRepositoryInterface;
use App\Models\Rooms\Room;

class RoomRepository implements RoomRepositoryInterface
{
    public function sync($body, $syncHeadquarter = null)
    {
        if ($syncHeadquarter == null)
            $syncHeadquarter = Headquarter::where('api_url', $body['url'])->get()->first();

        $action = $body['action'];
        $data = $body['data'];

        $arrayBody = [
            'headquarter_id' => $syncHeadquarter ? $syncHeadquarter->id : null,
            'remote_salkey'  => $data['remote_salkey'],
            'room_number'    => $data['room_number'],
            'capacity'       => $data['capacity'],
            'is_numerate'    => strtoupper(trim($data['salres'])) == "P" ? 1 : 0,
            'number_rows'    => $data['number_rows'],
            'number_columns' => $data['number_columns'],
            'number_halls'   => $data['number_halls'],
            'total_columns'  => $data['total_columns'],
            'name'           => $data['name'],
            'planner_graph'  => $data['graph']['graph'],
            'planner_meta'   => $data['graph']['planner_meta'],
            'active'          => true
        ];

        $room = Room::where('remote_salkey', $data['remote_salkey'])
        ->where('headquarter_id', $syncHeadquarter->id)
        ->latest('created_at')
        ->first();

        if(isset($room) && isset($room->id)) {
            Room::where('remote_salkey', $data['remote_salkey'])
            ->where('headquarter_id', $syncHeadquarter->id)
            ->latest('created_at')
            ->update($arrayBody);
        } else {
            Room::create($arrayBody);
        }
    }
}
