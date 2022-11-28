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
            <h1 class="fs-1 fw-bold">Candidaturas</h1>
            <h4 class="fw-bold text-muted">Analise com cautela as informações</h4>
        </div>
    </div>

    <div class="row">
        <?php
            $applications = $jobModel->findJobApplications($jobId);

            if (count($applications) < 1) {
            ?>
                <div class="col-md-12 mt-5">
                    <h1 class="fw-bold text-center"><i class="fa-regular fa-clock"></i> Nenhuma candidatura até o momento</h1>
                </div>
            <?php
            }

            foreach ($applications as $candidatura) {
                switch ($candidatura['tempo_exp']) {
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
        ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-5">
                    <div class="card p-3 border-0 shadow-lg h-100" style="border-radius: 15px;">
                        <h3 class="ms-1" style="color: #5260F4;"><?= $candidatura['nome'] ?></h3>
                        <p class="ms-2 text-muted text-truncate"><i class="fa-solid fa-message"></i> <?= $candidatura['area_atuacao'] == 1 ? 'Web developer' : 'Mobile developer' ?></p>
                        <h5 class="fw-light ms-2 mb-3"><i class="fa-solid fa-envelope"></i> <?= $candidatura['email'] ?></h5>
                        <h5 class="fw-light ms-2 mb-3"><i class="fa-solid fa-calendar-check"></i> <?= date_diff(new DateTime($candidatura['data_nasc']), new DateTime())->format("%y") ?> anos</h5>
                        <h5 class="fw-light ms-2 mb-3"><i class="fa-solid fa-clock"></i> <?= $tempoExp ?></h5>
                        <div class="row d-inline ms-1 mb-3">
                            <?php foreach ($candidatura['stacks'] as $stack) { ?>
                                <h5 class="d-inline p-0"><span class="badge rounded-pill bg-primary"><?= $stack ?></span></h5>
                            <?php } ?>
                        </div>
                        <h5 class="fw-light ms-2 mb-3 text-success"><i class="fa-solid fa-wallet"></i> <?= $formatter->asCurrency($candidatura['pretencao_salarial'], 'BRL', [
                            NumberFormatter::MAX_FRACTION_DIGITS => 0,
                        ]) ?></h5>

                        <div class="row mt-auto">
                            <div class="col-md-12">
                                <a href="<?= Url::toRoute(['company/reject-application', 'applicationId' => $candidatura['id']]) ?>" class="btn btn-danger">Rejeitar</a>
                                <a href="<?= Url::toRoute(['company/accept-application', 'applicationId' => $candidatura['id']]) ?>" class="btn btn-success">Aceitar</a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </div>

</div>
