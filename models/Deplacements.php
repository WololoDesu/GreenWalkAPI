<?php

namespace API;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class Deplacements extends Model
{
    public function validation()
    {
        $validator = new Validation();

        // Deplacement distance not null
        $validator->add(
            "distance",
            new PresenceOf(
                [
                    "message" => "Deplacement distance must be filled in",
                ]
            )
        );

        // Deplacement duration not null
        $validator->add(
            "temps",
            new PresenceOf(
                [
                    "message" => "Deplacement duration must be filled in",
                ]
            )
        );

        // Deplacement c02saved not null
        $validator->add(
            "co2Sauve",
            new PresenceOf(
                [
                    "message" => "Deplacement c02Saved must be filled in",
                ]
            )
        );

        // Deplacement date not null
        $validator->add(
            "deplacementDate",
            new PresenceOf(
                [
                    "message" => "Deplacement date must be filled in",
                ]
            )
        );
    }
}