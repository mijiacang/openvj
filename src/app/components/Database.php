<?php

namespace VJ;

class Database
{

    const COUNTER_USER_ID    = 0;
    const COUNTER_PROBLEM_ID = 1;

    public static function increaseId($id)
    {

        global $mongo;

        $id = (int)$id;

        $seq = $mongo->command([
            'findandmodify' => 'Counter',
            'query'         => array('_id' => $id),
            'update'        => array('$inc' => array('c' => 1)),
            'new'           => true,
            'upsert'        => true
        ]);

        if ($seq['value']['c'] == null) {
            return 0;
        } else {
            return $seq['value']['c'];
        }

    }

}