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
            <h1 class="fs-1 fw-bold">Vagas para você</h1>
            <h4 class="fw-bold text-muted">Sempre buscando as melhores oportunidades</h4>
        </div>
    </div>

    <div class="row">
        <?php
        if ($sess->has('cpf')) {
            $vagas = $jobModel->findJobs();
            $vagasFiltered = $jobModel->filterJobsByPercent($vagas);

            // echo "<pre>";
            // print_r($vagas);
            // echo "</pre>";
            // exit();
            foreach ($vagasFiltered as $vaga) {
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
        ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-5">
                    <div class="card p-3 text-white shadow-lg" style="background-color: #292841; border-radius: 15px;">
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

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <a href="<?= Url::toRoute(['user/aplly', 'idJob' => $vaga['id']]) ?>" class="btn btn-primary"><i class="fa-solid fa-building"></i>  Candidatar-se</a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else if ($sess->has('cnpj')) {
        ?>

        <?php
        }
        ?>
    </div>

</div>
