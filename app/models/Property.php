<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Property extends Eloquent {

    protected $collection = 'property';

    public function lock($id)
    {   $col = DB::collection($this->collection)->raw();

        $result = $col->findAndModify(
                array('_id'=>new MongoId($id)),
                array('lock'=>1),
                null,
                array(
                    'new' => true
                )
            );

        return $result;
    }

    public function unlock($id)
    {   $col = DB::collection($this->collection)->raw();

        $result = $col->findAndModify(
                array('_id'=>new MongoId($id)),
                array('lock'=>0),
                null,
                array(
                    'new' => true
                )
            );

        return $result;
    }

    public function reserve($id)
    {   $col = DB::collection($this->collection)->raw();

        $result = $col->findAndModify(
                array('_id'=>new MongoId($id)),
                array('availability'=>'reserved'),
                null,
                array(
                    'new' => true
                )
            );

        return $result;
    }

    public function release($id)
    {   $col = DB::collection($this->collection)->raw();

        $result = $col->findAndModify(
                array('_id'=>new MongoId($id)),
                array('availability'=>'available'),
                null,
                array(
                    'new' => true
                )
            );

        return $result;
    }


}