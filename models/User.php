<?php

namespace app\models;

use Yii;
use yii\base\Model;

class User extends Model
{
    
    public function findByEmail($email) {
        $sql = "SELECT email from public.users WHERE email = {$email}";

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function register() {
        echo "teste";
    }

}
