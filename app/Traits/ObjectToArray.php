<?php


namespace App\Traits;


trait ObjectToArray
{
    public function toArray(){
        $refl = new \ReflectionClass(self::class);
        $properties =  array_column($refl->getProperties(),'name');
        $data = [];
        foreach ($properties as $key){
            $data[$key] = $this->{$key};
        }
        return $data;
    }
}
