<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Cadastrar Vaga';

$sess = Yii::$app->session;
$maskClass = MaskedInput::class;

?>
<div class="company-register-job">

    <!-- <div class="row">
        <div class="col-md-12 mt-5 text-center">
            <h1 class="fs-1 fw-bold">Cadastro de vaga</h1>
        </div>
    </div> -->

    <div class="row d-flex justify-content-center align-items-center mt-3">
        <div class="col-md-8">
            <div class="card p-4 border-0 shadow-lg fs-5" style="border-radius: 10px;">
                <h2 class="fw-bold mb-3" style="color: #5260F4;">Dados da vaga</h2>

                <div class="row">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                    ]); ?>
    
                        <div class="row mb-2">
                            <div class="col-md-12">
                            <?= $form->field($model, 'titulo')->textInput(['autofocus' => true]) ?>
                            </div>
                        </div>
    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <?= $form->field($model, 'descricao')->textarea()->label('Descrição') ?>
                            </div>
                        </div>
    
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <?= $form->field($model, 'salario')->textInput()->label('Salário')->widget($maskClass, [
                                    'clientOptions' => [
                                        'alias' => 'numeric',
                                        'digits' => 0,
                                        'digitsOptional' => false,
                                        'radixPoint' => ',',
                                        'groupSeparator' => '.',
                                        'prefix' => 'R$ ',
                                        'autoGroup' => true,
                                        'rightAlign' => false,
                                        'removeMaskOnSubmit' => true,
                                    ],
                                ])  ?>
                            </div>
    
                            <div class="col-md-4">
                                <?= $form->field($model, 'remoto')->dropDownList(['1' => 'Sim', '2' => 'Não'])->label('Trabalho remoto?') ?>
                            </div>
    
                            <div class="col-md-4">
                                <?= $form->field($model, 'tipoContrato')->dropDownList(['1' => 'CLT', '2' => 'PJ'])->label('Tipo de contrato') ?>
                            </div>
                        </div>
    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <?= $form->field($model, 'stacks')->inline(true)->checkboxList([
                                    '1' => 'PHP',
                                    '2' => 'Javascript',
                                    '3' => 'Typescript',
                                    '4' => 'Python',
                                    '5' => 'Java',
                                    '6' => 'Go',
                                    '7' => 'React',
                                    '8' => 'Vue'
                                ]) ?>
                            </div>
                        </div>
    
                        <div class="row mb-2">
                            <?= $form->field($model, 'tempoExp')->radioList(['1' => 'Menos de um ano', '2' => 'De 1 a 2 anos', '3' => 'De 3 a 4 anos', '4' => 'Mais de 5 anos'], $options = ['class' => 'ms-1'])->label('Tempo de experiência profissional necessário') ?>
                        </div>
    
                        <div class="row mt-3 mb-3">
                            <div class="form-group">
                                <div class="col-lg-12 d-flex justify-content-between">
                                    <?= Html::submitButton('<i class="fa-solid fa-plus"></i>  Cadastrar vaga', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                </div>
                            </div>                              
                        </div>
    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
