<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Company;

class CompanyRegisterForm extends Model {
    public $razaoSocial;
    public $cnpj;
    public $qntFuncionarios;
    public $email;
    public $password;

    private $model;
    private $arrPost;

    public function __construct($config = []) {
        $this->model = new Company();
        $this->arrPost = Yii::$app->request->post();

        parent::__construct($config);
   }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['razaoSocial', 'required', 'message' => ''],
            ['cnpj', 'required', 'message' => ''],
            ['qntFuncionarios', 'required', 'message' => ''],
            ['email', 'required', 'message' => ''],
            ['password', 'required', 'message' => ''],

            ['cnpj', 'validateCnpj'],
            ['email', 'validateEmail'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
            $regex = preg_match($pattern, $this->password);

            if (!$regex) {
                $this->addError($attribute, 'Senha inválida!');
            }
        }
    }

    /**
     * Validates the CNPJ.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCnpj($attribute, $params) {
        if (!$this->hasErrors()) {
            $cnpj = preg_replace('/[^0-9]/', '', (string) $this->cnpj);
	
            // Valida tamanho
            if (strlen($cnpj) != 14)
                $retorno = false;

            // Verifica se todos os digitos são iguais
            if (preg_match('/(\d)\1{13}/', $cnpj))
                $retorno = false;	

            // Valida primeiro dígito verificador
            for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
            {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }

            $resto = $soma % 11;

            if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
                $retorno = false;

            // Valida segundo dígito verificador
            for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
            {
                $soma += $cnpj[$i] * $j;
                $j = ($j == 2) ? 9 : $j - 1;
            }

            $resto = $soma % 11;

            $retorno = $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);

            if (!$retorno) {
                $this->addError($attribute, 'CNPJ inválido.');
            }
        }
    }

    /**
     * Validates the email.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->model->findByEmail($this->email) == $this->email) {
                $this->addError($attribute, 'Email já cadastrado.');
            }
        }
    }

    public function register() {
        if ($this->validate()) {
            return $this->model->register($this->razaoSocial, $this->cnpj, $this->qntFuncionarios, $this->email, md5($this->password));
        }

        return false;
    }
}
