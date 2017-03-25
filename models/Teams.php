<?php

namespace API;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class Teams extends Model
{
    public function validation()
    {
        $validator = new Validation();

        // Team name not null
        $validator->add(
            "nom",
            new PresenceOf(
                [
                    "message" => "Team name must be filled in",
                ]
            )
        );//

        //Team score not null
        $validator->add(
            "score",
            new PresenceOf(
                [
                    "message" => "Team score must be filled in",
                ]
            )
        );

        //Team name must be unique
        $validator->add(
          "nom",
            new PresenceOf(
                [
                    "message" => "Team name already exist",
                ]
            )
        );

        return $this->validate($validator);
    }
}