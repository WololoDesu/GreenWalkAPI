<?php

namespace API;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class Users extends Model
{
    public function validation()
    {
        $validator = new Validation();

        // User name not null
        $validator->add(
            "nom",
            new PresenceOf(
                [
                    "message" => "User last name must be filled in",
                ]
            )
        );

        // User firstname not null
        $validator->add(
            'prenom',
            new PresenceOf(
                [
                    "message" => "User first name must be filled in",
                ]
            )
        );

        // User pseudo not null
        $validator->add(
            'pseudo',
            new PresenceOf(
                [
                    "message" => "User pseudo must be filled in",
                ]
            )
        );

        // User mail not null
        $validator->add(
            'mail',
            new PresenceOf(
                [
                    "message" => "User email must be filled in",
                ]
            )
        );

        //Password not null
        $validator->add(
            'password',
            new PresenceOf(
                [
                    "message" => "User password must be filled in",
                ]
            )
        );

        //Creation date not null
        $validator->add(
            'creationDate',
            new PresenceOf(
                [
                    "message" => "Creation date must be filled in",
                ]
            )
        );

        //Score not null
        $validator->add(
            'score',
            new PresenceOf(
                [
                    "message" => "Score must be filled in",
                ]
            )
        );

        //idTeam must exist
        $validator->add(
            'idTeam',
            new PresenceOf(
                [
                    "message" => "User must be in a team",
                ]
            )
        );

        // Pseudo must be unique
        $validator->add(
            'pseudo',
            new Uniqueness(
                [
                    "message" => "Pseudo already exist",
                ]
            )
        );

        //Mail must be unique
        $validator->add(
            'mail',
            new Uniqueness(
                [
                    "message" => "Email already exist",
                ]
            )
        );

        return $this->validate($validator);
    }
}