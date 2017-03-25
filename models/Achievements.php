<?php

namespace API;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class Achievements extends Model
{
    public function validation()
    {
        $validator = new Validation();

        //Achievement name not null
        $validator->add(
            "nom",
            new PresenceOf(
                [
                    "message" => "Achievement name must be filled in",
                ]
            )
        );

        //Achievement mult not null
        $validator->add(
            "multiplicateur",
            new PresenceOf(
                [
                    "message" => "Achievement multiplicator must be filled in",
                ]
            )
        );
        //Achievement name not null
        $validator->add(
            "description",
            new PresenceOf(
                [
                    "message" => "Achievement desc must be filled in",
                ]
            )
        );

    }
}