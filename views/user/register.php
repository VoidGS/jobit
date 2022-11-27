<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\UserRegisterForm $model */
/** @var yii\widgets\MaskedInput $maskClass */

use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$maskClass = MaskedInput::class;

$this->title = 'Registro de usuário';
?>
<div class="user-register">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-6 col-lg-7 col-xl-6">
                    <img src="<?= Url::to('@web/img/draw2.svg') ?>" class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-6 col-lg-5 col-xl-5 offset-xl-1">
                    <div class="row">
                        <h1 class="fw-bold" style="color: #5260F4;"><?= Yii::$app->name ?></h1>
                        <h2 class="mb-2 font-alt"><?= Html::encode($this->title) ?></h2>
    
                        <?php $form = ActiveForm::begin([
                            'id' => 'register-form',
                        ]); ?>
    
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'nome')->textInput(['autofocus' => true]) ?>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'cpf')->textInput()->label('CPF')->widget($maskClass, [
                                        'mask' => '999.999.999-99',
                                        'clientOptions' => [
                                            'removeMaskOnSubmit' => true,
                                        ]
                                    ]) ?>
                                </div>

                                <div class="col-md-6">
                                    <?= $form->field($model, 'dataNasc')->input('date')->label('Data de nascimento') ?>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'email')->input('email') ?>
                                </div>

                                <div class="col-md-6">
                                    <?= $form->field($model, 'pretencaoSalarial')->textInput()->label('Pretenção Salarial')->widget($maskClass, [
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
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'areaAtuacao')->radioList(['Web', 'Mobile'], $options = ['class' => 'ms-1'])->label('Área de atuação') ?>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'stacks')->inline(true)->checkboxList([
                                        'PHP',
                                        'Javascript',
                                        'Typescript',
                                        'Python',
                                        'Java',
                                        'Go',
                                        'React',
                                        'Vue'
                                    ]) ?>
                                </div>
                            </div>

                            
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'tempoExp')->radioList(['Menos de um ano', 'De 1 a 2 anos', 'De 3 a 4 anos', 'Mais de 5 anos'], $options = ['class' => 'ms-1'])->label('Tempo de experiência profissional') ?>
                                </div>
                            </div>

                            
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'password')->passwordInput()->label('Senha') ?>
                                </div>
                            </div>
    
                            <div class="row mt-3 mb-5">
                                <div class="form-group">
                                    <div class="col-lg-12 d-flex justify-content-between">
                                        <?= Html::submitButton('<i class="fa-solid fa-id-badge"></i>  Registre-se', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                        <a href="<?= Url::toRoute('company/login') ?>" class="btn btn-secondary"><i class="fa-solid fa-building"></i>  Painel da empresa</a>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-lg-12 mt-3 text-muted">
                                        <span>Já tem uma conta? <a href="<?= Url::toRoute('user/login') ?>" style="text-decoration: none;">Entre <i class="fa-solid fa-arrow-right"></i></a></span>
                                    </div>
                                </div>
                            </div>
    
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>