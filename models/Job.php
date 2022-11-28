<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Job extends Model
{
    private $sess;
    
    public function __construct($config = []) {
        $this->sess = Yii::$app->session;

        parent::__construct($config);
   }

    public function findJobs() {
        $userId = $this->sess->get('id');

        $sql = "
            SELECT 
                j.id,
                j.id_empresa,
                j.titulo,
                j.descricao,
                j.salario,
                array_to_json(j.stacks) as id_stacks,
                json_agg(s.stack) as stacks, 
                j.tempo_exp,
                j.tipo_contrato,
                j.remoto,
                j.status,
                j.data_cadastro
            FROM public.jobs j
            INNER JOIN public.stacks s ON s.id = any(j.stacks)
            LEFT JOIN public.applications a ON a.id_job = j.id
            WHERE 
                j.status = 1 AND
                a.id_user NOT IN({$userId})
            GROUP BY j.id";

        $retorno = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($retorno as $k => $v) {
            $retorno[$k]['id_stacks'] = json_decode($retorno[$k]['id_stacks'], true);
            $retorno[$k]['stacks'] = json_decode($retorno[$k]['stacks'], true);
        }

        return $retorno;
    }

    /**
     * Filter the jobs by the percent that the user matches it
     * @param mixed[] $jobs
     * @return mixed[]
     */
    public function filterJobsByPercent($jobs) {
        $retorno = [];
        $sess = Yii::$app->session;

        $userStacksArr = $sess->get('stacks');
        $userTempoExp = $sess->get('tempo_exp');
        $userPretencao = $sess->get('pretencao_salarial');

        foreach ($jobs as $job) {
            $stackScore = 0;
            $expScore = 0;
            $salarioScore = 0;
            $scoreTotal = 0;

            $jobStacksArr = $job['id_stacks'];
            $stacksIntersect = array_intersect($jobStacksArr, $userStacksArr);
            $stackScore = (count($stacksIntersect) / count($jobStacksArr)) * 100;

            $jobTempoExp = $job['tempo_exp'];
            $expScore = abs($userTempoExp - $jobTempoExp);
            $expScore = 100 - (25 * $expScore);

            $jobSalario = $job['salario'];
            $salarioScore = intval((($jobSalario * 100) / $userPretencao) / 2);
            $scoreTotal = $stackScore + $expScore + $salarioScore;

            $retorno[] = array_merge($job, ['score' => $scoreTotal]);
        }

        usort($retorno, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $retorno;
    }

    public function findCompanyJobs($companyId) {
        $sql = "
            SELECT 
                j.id,
                j.id_empresa,
                j.titulo,
                j.descricao,
                j.salario,
                array_to_json(j.stacks) as id_stacks,
                json_agg(s.stack) as stacks, 
                j.tempo_exp,
                j.tipo_contrato,
                j.remoto,
                j.status,
                j.data_cadastro
            FROM public.jobs j
            INNER JOIN public.stacks s ON s.id = any(j.stacks)
            WHERE 
                j.status = 1 AND
                j.id_empresa = {$companyId}
            GROUP BY j.id";

        $retorno = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($retorno as $k => $v) {
            $retorno[$k]['id_stacks'] = json_decode($retorno[$k]['id_stacks'], true);
            $retorno[$k]['stacks'] = json_decode($retorno[$k]['stacks'], true);
        }

        return $retorno;
    }

    public function apply($jobId) {
        if ($this->findApplication($jobId)) {
            $toast = array(
                'class' => 'danger',
                'msg' => '❌ Já se candidatou nessa vaga.'
            );
        } else {
            try {
                Yii::$app->db->createCommand()->insert('public.applications', [
                    'id_job' => $jobId,
                    'id_user' => $this->sess->get('id')
                ])->execute();

                $toast = array(
                    'class' => 'success',
                    'msg' => '✔ Candidatura enviada! 🎉'
                );
            } catch (\Exception $th) {
                //throw $th;
                $toast = array(
                    'class' => 'danger',
                    'msg' => '❌ Ocorreu um erro.'
                );
            }
        }

        $this->sess->set('toast', $toast);

        Yii::$app->response->redirect(Url::toRoute('/'));
    }

    public function findApplication($jobId) {
        $sql = "
            SELECT a.id 
            FROM public.applications a 
            WHERE a.id_job = {$jobId}";

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function findApplications() {
        $userId = $this->sess->get('id');

        $sql = "
            SELECT
                a.id,
                j.id AS id_job,
                j.id_empresa,
                j.titulo,
                j.descricao,
                j.salario,
                array_to_json(j.stacks) AS id_stacks,
                json_agg(s.stack) AS stacks, 
                j.tempo_exp,
                j.tipo_contrato,
                j.remoto,
                j.status AS job_status,
                a.status,
                j.data_cadastro
            FROM public.applications a 
            INNER JOIN public.jobs j ON j.id = a.id_job 
            INNER JOIN public.stacks s ON s.id = any(j.stacks)
            WHERE 
                a.id_user = {$userId} AND
                j.status = 1
            GROUP BY 
                a.id,
                j.id";

        $retorno = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($retorno as $k => $v) {
            $retorno[$k]['id_stacks'] = json_decode($retorno[$k]['id_stacks'], true);
            $retorno[$k]['stacks'] = json_decode($retorno[$k]['stacks'], true);
        }

        return $retorno;
    }

}
