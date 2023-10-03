<?php


namespace App\Traits\Controllers;


use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\MongoMovies\MongoMovie;

trait ChangeStatus
{
    public function toggleStatus($id, Request $req)
    {
        $model = $this->repository->findOrFail($id);
            
        if($model instanceof ActivatableInterface){
            $model->toggleActive();
            if($model->isActive() === true)
            { $valor = 1; }
            else
            { $valor = 0; }
            MongoMovie::where('id', intval($id))->update(['status' => $valor],['upsert' => true]);

            return response()->json(['new_status'=>$model->isActive()]);
        }
        return response()->json($model,412);
    }

}
