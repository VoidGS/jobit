<?php

/** @var yii\web\View $this */

use app\models\Job;
use yii\helpers\Url;

$this->title = 'Home';

$sess = Yii::$app->session;
$jobModel = new Job();
$formatter = Yii::$app->formatter;

?>
<div class="site-index">

    <div class="row">
        <div class="col-md-12 mt-5 text-center">
            <h1 class="fs-1 fw-bold">Suas candidaturas</h1>
            <h4 class="fw-bold text-muted">Fique de olho na caixa de entrada do seu email</h4>
        </div>
    </div>

    <div class="row">
        <?php
        if ($sess->has('cpf')) {
            $vagas = $jobModel->findApplications();

            if (count($vagas) < 1) {
            ?>
                <div class="col-md-12 mt-5">
                    <h1 class="fw-bold text-center"><i class="fa-regular fa-clock"></i> Nenhuma candidatura até o momento</h1>
                </div>
            <?php
            }

            foreach ($vagas as $vaga) {
                $remoto = $vaga['remoto'] == 1 ? 'Remoto' : 'Presencial';
                $tipoContrato = $vaga['tipo_contrato'] == 1 ? 'CLT' : 'PJ';
                switch ($vaga['tempo_exp']) {
                    case '1':
                        $tempoExp = 'Menos de um ano';
                        break;
                    
                    case '2':
                        $tempoExp = 'De 1 a 2 anos';
                        break;
                    
                    case '3':
                        $tempoExp = 'De 3 a 4 anos';
                        break;
                    
                    case '4':
                        $tempoExp = 'Mais de 5 anos';
                        break;
                    
                    default:
                        break;
                }

                switch ($vaga['status']) {
                    case '1':
                        $textStatusApplication = 'Candidatura pendente';
                        $classStatusApplication = 'secondary';
                        break;
                    
                    case '2':
                        $textStatusApplication = 'Candidatura rejeitada';
                        $classStatusApplication = 'danger';
                        break;
                    
                    case '3':
                        $textStatusApplication = 'Candidatura aceita';
                        $classStatusApplication = 'success';
                        break;
                    
                }
        ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-5">
                    <div class="card p-3 text-white shadow-lg h-100" style="background-color: #292841; border-radius: 15px;">
                        <h3 style="color: #5260F4;"><?= $vaga['titulo'] ?></h3>
                        <p class="ms-2 text-muted text-truncate"><i class="fa-solid fa-message"></i> <?= $vaga['descricao'] ?></p>
                        <h5 class="fw-light text-light ms-2 mb-3"><i class="fa-solid fa-globe"></i> <?= $remoto ?></h5>
                        <h5 class="fw-light text-light ms-2 mb-3"><i class="fa-solid fa-briefcase"></i> Contrato <?= $tipoContrato ?></h5>
                        <h5 class="fw-light text-light ms-2 mb-3"><i class="fa-solid fa-clock"></i> <?= $tempoExp ?></h5>
                        <div class="row d-inline ms-1 mb-3">
                            <?php foreach ($vaga['stacks'] as $stack) { ?>
                                <h5 class="d-inline p-0"><span class="badge rounded-pill bg-primary"><?= $stack ?></span></h5>
                            <?php } ?>
                        </div>
                        <h5 class="fw-light ms-2 mb-3" style="color: #63E6BE;"><i class="fa-solid fa-wallet"></i> <?= $formatter->asCurrency($vaga['salario'], 'BRL', [
                            NumberFormatter::MAX_FRACTION_DIGITS => 0,
                        ]) ?></h5>

                        <div class="row mt-auto">
                            <div class="col-md-12">
                                <h5 class=""><span class="badge bg-<?= $classStatusApplication ?>"><?= $textStatusApplication ?></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

</div>
