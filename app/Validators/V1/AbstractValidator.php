<?php
namespace Tajrish\Validators\V1;

use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;

/**
 * Class AbstractValidator
 * @package Tajrish\Services\Validators
 */
abstract class AbstractValidator
{

    /**
     * Http status code
     *
     * @var int
     */
    protected static $statusCode = 422;

    /**
     * Rules to check for
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Update rules to check for
     *
     * @var array
     */
    protected $updateRules = [];

    /**
     * Custom Attributes
     *
     * @var array
     */
    protected $customAttributes = [];

    /**
     * Messages to append
     *
     * @var array
     */
    protected $messages = [];

    /**
     * @var Factory
     */
    protected $validatorFactory;

    /**
     * @var Validator
     */
    protected $validated;

    /**
     * Rules Scenario
     *
     * @var
     */
    protected $scenario;

    /**
     * @param Factory $validatorFactory
     */
    public function __construct(Factory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return Validator|\Illuminate\Validation\Validator
     * @throw StoreResourceFailedException
     */
    public function validate(array $data, array $rules = [], array $messages = [], array $customAttributes = [])
    {

        if ($this->getScenario() !== null) {
            $scenarioRules = camel_case($this->getScenario() . 'Rules');

            if ($this->{$scenarioRules} !== null) {
                $rules = empty($rules) ? $this->{$scenarioRules}: array_merge($rules, $this->{$scenarioRules});
            }
        }
        else {
            $rules = empty($rules) ? $this->rules: array_merge($rules, $this->rules);
        }

        $messages = empty($messages) ? $this->messages : array_merge($messages, $this->messages);

        $customAttributes = empty($customAttributes) ? $this->customAttributes : array_merge($customAttributes, $this->customAttributes);

        $this->validated = $this->validatorFactory->make($data, $rules, $messages, $customAttributes);

        if ($this->validated->fails()) {
            throw new StoreResourceFailedException(trans('messages.validation_failed'), $this->validated->messages());
        }

        return $this->validated;
    }

    /**
     * Does validation fails with given data
     *
     * @return bool
     * @throws \Exception
     */
    public function fails()
    {
        if (is_null($this->validated)) {
            throw new \Exception("No data has been validated yet");
        }

        return $this->validated->fails();
    }

    /**
     * Does validation passes by gieven data
     *
     * @return bool
     * @throws \Exception
     */
    public function passes()
    {
        return !$this->fails();
    }

    /**
     * @param $scenario
     *
     * @return $this
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getScenario()
    {
        return $this->scenario;
    }

}