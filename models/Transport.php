<?php
/**
 * Created by PhpStorm.
 * User: Bastien
 * Date: 25/03/2017
 * Time: 13:55
 */

namespace API;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

class Transport extends Model
{
    public function validation()
    {
        $validator = new Validation();

        //Vehicule name not null
        $validator->add(
            "nom",
            new PresenceOf(
                [
                    "message" => "Vehicule name must be filled in",
                ]
            )
        );

        //Multiplicator not null
        $validator->add(
            "multiplicateur",
            new PresenceOf(
                [
                    "message" => "Multiplicator name must be filled in",
                ]
            )
        );

        $validator->add(
            "tauxSauve",
            new PresenceOf(
                [
                    "message" => "Rate must be filled in",
                ]
            )
        );

        //Vehicule name unique
        $validator->add(
            "nom",
            new Uniqueness(
                [
                    "message" => "Vehicule name already exist",
                ]
            )
        );
    }
}