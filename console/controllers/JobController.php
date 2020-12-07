<?php

namespace console\controllers;

use common\jobs\FlagmerJob;

use yii\console\Controller;
use yii\helpers\Json;
use Yii;

class JobController extends Controller
{


    public function actionRun()
    {
         $data = Json::decode(file_get_contents(__DIR__.'/../../tasks.json'));

        $queue = Yii::$app->queue;

        foreach ($data as $jobItem)
        {
            try {
                $queue->push(new FlagmerJob(['jobItem'=>$jobItem]));
            }
            catch (\Exception $e)
            {
                Yii::error($e->getMessage());
                print $e->getMessage().PHP_EOL;
            }
        }

    }

}
