<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Company;

class CompanyRegisterJobForm extends Model {
    public $idEmpresa;
    public $titulo;
    public $descricao;
    public $salario;
    public $stacks;
    public $tempoExp;
    public $tipoContrato;
    public $remoto;

    private $model;
    private $arrPost;
    private $sess;

    public function __construct($config = []) {
        $this->model = new Company();
        $this->arrPost = Yii::$app->request->post();
        $this->sess = Yii::$app->session;

        $this->idEmpresa = $this->sess->get('id');

        parent::__construct($config);
   }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['titulo', 'required', 'message' => ''],
            ['descricao', 'required', 'message' => ''],
            ['salario', 'required', 'message' => ''],
            ['stacks', 'required', 'message' => ''],
            ['tempoExp', 'required', 'message' => ''],
            ['tipoContrato', 'required', 'message' => ''],
            ['remoto', 'required', 'message' => ''],
            
            ['salario', 'validateSalario'],
        ];
    }

    /**
     * Validates the salary.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateSalario($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->salario < 1000 || $this->salario > 100000) {
                $this->addError($attribute, 'Salário inválido.');
            }
        }
    }

    public function registerJob() {
        if ($this->validate()) {
            return $this->model->registerJob($this->idEmpresa, $this->titulo, $this->descricao, $this->salario, $this->stacks, $this->tempoExp, $this->tipoContrato, $this->remoto);
        }

        return false;
    }
}
