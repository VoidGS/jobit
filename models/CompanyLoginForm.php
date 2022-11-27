<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Company;

class CompanyLoginForm extends Model
{
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
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'validateEmail'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
            $regex = preg_match($pattern, $this->password);

            if (!$regex) {
                $this->addError($attribute, 'Senha inválida!');
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
            if ($this->model->findByEmail($this->email) != $this->email) {
                $this->addError($attribute, 'Não existe uma conta com este email.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return $this->model->login($this->email, md5($this->password));
        }
        return false;
    }
}
