<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Job extends Model
{
    
    public function findJobs() {
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
            WHERE j.status = 1
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

}
