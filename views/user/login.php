<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\UserLoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login de usuário';
?>
<div class="user-login">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-6 col-lg-7 col-xl-6">
                    <img src="<?= Url::to('@web/img/draw2.svg') ?>" class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-6 col-lg-5 col-xl-5 offset-xl-1">
                    <h1 class="fw-bold" style="color: #5260F4;"><?= Yii::$app->name ?></h1>
                    <h2 class="mb-2 font-alt"><?= Html::encode($this->title) ?></h2>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'col-form-label'],
                            'inputOptions' => ['class' => 'form-control form-control-lg'],
                            'errorOptions' => ['class' => 'invalid-feedback'],
                        ],
                        'options' => ['class' => '']
                    ]); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <div class="form-group">
                            <div class="col-lg-12 d-flex justify-content-between">
                                <?= Html::submitButton('<i class="fa-solid fa-user"></i>  Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                <button class="btn btn-secondary"><i class="fa-solid fa-building"></i>  Painel da empresa</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-12 mt-3 text-muted">
                                <span>Não tem uma conta? <a href="<?= Url::toRoute('user/register') ?>" style="text-decoration: none;">Registre-se <i class="fa-solid fa-arrow-right"></i></a></span>
                            </div>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>