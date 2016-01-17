<?php

return [
    'modules' => [
        'vote' => [
            'class' => 'lo\modules\vote\Module',
            'allow_guests' => false, // if true will check IP, otherwise - UserID
            'allow_change_vote' => true, // if true vote can be changed
            'matchingModels' => [ // matching model names with whatever unique integer ID
                'origami' => 2, // may be just integer value
                'aphorism' => 2, // may be just integer value
                //'lo\modules\love\models\Aphorism' => 3, // or array with 'id' key
                //'story' => ['id'=>4, 'allow_guests'=>false], // own value 'allow_guests'
                //'world' => ['id'=>5, 'allow_guests'=>false, 'allow_change_vote'=>false],
            ],
        ],
    ],
];