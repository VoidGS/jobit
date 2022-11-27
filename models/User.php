<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class User extends Model
{
    
    public function findByEmail($email) {
        $sql = "SELECT email FROM public.users WHERE email = '{$email}'";

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function register($nome, $cpf, $dataNasc, $email, $pretencaoSalarial, $areaAtuacao, $stacks, $tempoExp, $password) {
        Yii::$app->db->createCommand()->insert('public.users', [
            'nome' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'senha' => $password,
            'data_nasc' => $dataNasc,
            'area_atuacao' => $areaAtuacao,
            'stacks' => $stacks,
            'pretencao_salarial' => $pretencaoSalarial,
            'tempo_exp' => $tempoExp
        ])->execute();

        $this->login($email, $password);
    }

    public function login($email, $password) {
        $sql = "SELECT id, nome, cpf, email, data_nasc, area_atuacao, stacks, pretencao_salarial, tempo_exp FROM public.users WHERE email = '{$email}' AND senha = '{$password}'";

        $loginData = Yii::$app->db->createCommand($sql)->queryOne();

        if (isset($loginData['id'])) {
            $sess = Yii::$app->session;

            $sess->open();
            foreach ($loginData as $k => $v) {
                $sess->set($k, $v);
            }
            return Yii::$app->response->redirect(Url::toRoute('site/home'));
        }
    }

}
