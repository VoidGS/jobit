<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Company extends Model
{
    
    public function findByEmail($email) {
        $sql = "SELECT email FROM public.companys WHERE email = '{$email}'";

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function register($razaoSocial, $cnpj, $qntFuncionarios, $email, $password) {
        Yii::$app->db->createCommand()->insert('public.companys', [
            'razao_social' => $razaoSocial,
            'cnpj' => $cnpj,
            'qnt_funcionarios' => $qntFuncionarios,
            'email' => $email,
            'senha' => $password
        ])->execute();

        $this->login($email, $password);
    }

    public function login($email, $password) {
        $sql = "SELECT id, razao_social, cnpj, qnt_funcionarios, email, senha FROM public.companys WHERE email = '{$email}' AND senha = '{$password}'";

        $loginData = Yii::$app->db->createCommand($sql)->queryOne();

        if (isset($loginData['id'])) {
            $sess = Yii::$app->session;

            $sess->open();
            foreach ($loginData as $k => $v) {
                $sess->set($k, $v);
            }
            return Yii::$app->response->redirect(Url::toRoute('/'));
        }
    }

}
