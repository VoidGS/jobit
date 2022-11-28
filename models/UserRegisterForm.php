<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use DateTime;

class UserRegisterForm extends Model {
    public $nome;
    public $cpf;
    public $dataNasc;
    public $email;
    public $pretencaoSalarial;
    public $areaAtuacao;
    public $stacks;
    public $tempoExp;
    public $password;

    private $model;
    private $arrPost;

    public function __construct($config = []) {
        $this->model = new User();
        $this->arrPost = Yii::$app->request->post();

        parent::__construct($config);
   }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['nome', 'required', 'message' => ''],
            ['cpf', 'required', 'message' => ''],
            ['dataNasc', 'required', 'message' => ''],
            ['email', 'required', 'message' => ''],
            ['pretencaoSalarial', 'required', 'message' => ''],
            ['areaAtuacao', 'required', 'message' => ''],
            ['stacks', 'required', 'message' => ''],
            ['tempoExp', 'required', 'message' => ''],
            ['password', 'required', 'message' => ''],

            ['cpf', 'validateCpf'],
            ['dataNasc', 'validateDataNasc'],
            ['email', 'validateEmail'],
            ['pretencaoSalarial', 'validatePretencao'],
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
     * Validates the CPF.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCpf($attribute, $params) {
        if (!$this->hasErrors()) {
            $cpf = preg_replace( '/[^0-9]/is', '', $this->cpf );
            
            if (strlen($cpf) != 11) {
                $this->addError($attribute, 'CPF Inválido!');
            }

            if (preg_match('/(\d)\1{10}/', $cpf)) {
                $this->addError($attribute, 'CPF Inválido!');
            }

            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    $this->addError($attribute, 'CPF Inválido!');
                }
            }
        }
    }

    /**
     * Validates the date of birth.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateDataNasc($attribute, $params) {
        if (!$this->hasErrors()) {
            $dataLimite = new DateTime('1920-01-01');
            $data = new DateTime($this->dataNasc);

            if ($data < $dataLimite) {
                $this->addError($attribute, 'Data de nascimento inválida.');
            } else if (date_diff($data, new DateTime())->format("%y") < 18) {
                $this->addError($attribute, 'Data de nascimento inválida.');
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

    /**
     * Validates the salary.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePretencao($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->pretencaoSalarial < 1000 || $this->pretencaoSalarial > 100000) {
                $this->addError($attribute, 'Pretenção inválida.');
            }
        }
    }

    public function register() {
        if ($this->validate()) {
            return $this->model->register($this->nome, $this->cpf, $this->dataNasc, $this->email, $this->pretencaoSalarial, $this->areaAtuacao, $this->stacks, $this->tempoExp, md5($this->password));
        }

        return false;
    }
}
