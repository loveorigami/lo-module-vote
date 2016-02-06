<?php

return [
    'modules' => [
        'vote' => [
            'class' => 'lo\modules\vote\Module',
            'allowGuests' => false, // if true will check IP, otherwise - UserID
            'allowChangeVote' => true, // if true vote can be changed
            'models' => [ // matching model names with whatever unique integer ID
                3 => [
					'modelName' => 'lo\modules\love\models\Aphorism',
					'allowGuests' => false,
					'allowChangeVote' => true
				] 
                //'lo\modules\love\models\Aphorism' => 3, // or array with 'id' key
            ],
        ],
    ],
];