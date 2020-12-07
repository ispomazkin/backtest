<?php

namespace common\jobs;

use Flagmer\Billing\Account;
use Flagmer\Integrations\AmoCrm;
use yii\base\BaseObject;
use yii\base\ErrorException;
use yii\queue\JobInterface;


class FlagmerJob extends BaseObject implements JobInterface
{

    /**
     * @var array
     */
    protected $workers=[
        'account' => Account::class,
        'amocrm' => AmoCrm::class
    ];

    /**
     * @var \stdClass
     */
    public $jobItem;

    /**
     * @inheritDoc
     */
    public function execute($queue)
    {

        $jobItem = $this->jobItem;
        $workerClass = $this->getWorkerClass($jobItem['category']);

        if ($workerClass === null)
            throw new ErrorException('Worker class does not exist');

        $refWorker = new \ReflectionClass($workerClass);
        $taskClass = $this->getTaskClass($refWorker, $jobItem['category'], $jobItem['task']);

        $refTask = new \ReflectionClass($taskClass);

        if (!class_exists($taskClass))
            throw new ErrorException('Task class ' . $taskClass.' does not exist');

        $action = $this->getTaskAction($jobItem['task']);

        if (!$refWorker->hasMethod($action))
            throw new ErrorException('Method '. $action .' does not exist in ' . $refWorker->getName());

        $task = $this->createInstance($refTask, $jobItem['data']);
        $worker = $this->createInstance($refWorker);
        $worker->{$action}($task);

    }


    /**
     * @param \ReflectionClass $ref
     * @param array            $properties
     *
     * @return object
     * @throws ErrorException
     */
    protected function createInstance(\ReflectionClass $ref, array $properties=[])
    {
        $instance = $ref->newInstance();
        foreach ($properties as $property=>$value)
        {
            if (!$ref->hasProperty($property))
                throw new ErrorException('Property '.$property.' does not exists in ' . $ref->getName());
            $instance->{$property} = $value;
        }
        return $instance;
    }



    /**
     * @param string $key
     *
     * @return string|null
     */
    protected function getWorkerClass(string $key):?string
    {
        return $this->workers[$key] ?? null;
    }

    /**
     * @param \ReflectionClass $worker
     * @param string           $category
     * @param string           $taskName
     *
     * @return string
     */
    protected function getTaskClass(\ReflectionClass $worker, string $category, string $taskName): string
    {
        return $worker->getNamespaceName().'\\'.ucfirst($category) .'\\' . $taskName.'Dto';
    }

    /**
     * @param string $taskName
     *
     * @return string
     */
    protected function getTaskAction(string $taskName): string
    {
        return $taskName.'Action';
    }

}
