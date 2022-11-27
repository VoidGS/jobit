<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Company;
use DateTime;

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
