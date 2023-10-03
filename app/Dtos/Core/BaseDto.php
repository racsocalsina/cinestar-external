<?php


namespace App\Dtos\Core;



class BaseDto
{
    public function toArray(){
        $refl = new \ReflectionClass($this);
        $properties =  array_column($refl->getProperties(),'name');
        $data = [];
        foreach ($properties as $key){
            $nameMethod = 'get'.ucfirst($key);
            $data[$key] = method_exists($this, $nameMethod) ? $this->{$nameMethod}() :  $this->{$key};
        }
        return $data;
    }
}
