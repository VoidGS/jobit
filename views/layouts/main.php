<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

// Construct
$sess = Yii::$app->session;
$currentRoute = Yii::$app->request->url;

$optionsArr = [
    'navItemLabel' => 'undefined',
    'navItemUrl' => '/',
    'profileName' => 'undefined'
];

$noSessionRoutes = [
    '/user/login',
    '/user/register',
    '/company/login',
    '/company/register',
];

if (!$sess->has('id')) {
    if (!in_array($currentRoute, $noSessionRoutes)) {
        Yii::$app->response->redirect(Url::toRoute('/user/login'));
    }
} else {
    if (in_array($currentRoute, $noSessionRoutes)) {
        Yii::$app->response->redirect(Url::toRoute('/'));
    } else {
        if ($sess->has('cpf')) {
            if (isset(explode('/', $currentRoute)[1]) && explode('/', $currentRoute)[1] == 'company') {
                Yii::$app->response->redirect(Url::toRoute('/'));
            } else {
                $optionsArr = [
                    'navItemLabel' => '<i class="fa-solid fa-hourglass-end"></i> Candidaturas',
                    'navItemUrl' => '/user/candidaturas',
                    'profileName' => '<i class="fa-solid fa-user"></i> ' . $sess->get('nome'),
                ];
            }
        } else if ($sess->has('cnpj')) {
            if (isset(explode('/', $currentRoute)[1]) && explode('/', $currentRoute)[1] == 'user') {
                Yii::$app->response->redirect(Url::toRoute('/'));
            } else {
                $optionsArr = [
                    'navItemLabel' => '<i class="fa-solid fa-plus"></i> Cadastrar vaga',
                    'navItemUrl' => '/company/register-job',
                    'profileName' => '<i class="fa-solid fa-building"></i> ' . $sess->get('razao_social'),
                ];
            }
        } else {
            Yii::$app->response->redirect(Url::toRoute('/site/error'));
        }
    }
}

// Start
AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php if ($sess->has('id')) { ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => ['class' => 'fs-2 fw-bold'],
        'innerContainerOptions' => ['class' => 'container'],
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-primary shadow fixed-top fs-5', 'style' => 'background-color: #5260F4 !important'],
    ]);
    echo Nav::widget([
        'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            ['label' => '<i class="fa-solid fa-house"></i> Home', 'url' => ['/site/home']],
            ['label' => $optionsArr['navItemLabel'], 'url' => [$optionsArr['navItemUrl']]],
            ['label' => $optionsArr['profileName'], 'items' => [
                ['label' => 'Perfil', 'url' => '#'],
                ['label' => 'Logout', 'url' => ['/site/deslogar']]
            ]]
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container mt-5">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php } else { ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="d-flex align-items-center justify-content-center h-100">
        <?= $content ?>
    </div>
</main>

<?php } ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
