<?php

namespace Tajrish\Helpers;

use Carbon\Carbon;
use Tajrish\Models\Token;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class TokenHelper
 * @package Tajrish\Helpers
 */
class TokenHelper
{
    /**
     * @var bool
     */
    protected $unique = false;

    /**
     * @var null
     */
    protected $model = null;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {

        $this->hasher = $hasher;
    }

    /**
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @param boolean $unique
     */
    public function unique()
    {
        $this->unique = true;

        return $this;
    }

    /**
     * Generate Token
     *
     * @return string
     */
    public function generate($salt = 'Im salt man, Not Sugar!')
    {
        $generatedToken = $this->generateToken($salt);

        /**
         * check for uniqueness in database
         */
        $generatedToken = $this->checkForUniqueness($salt, $generatedToken);

        return $generatedToken;
    }

    /**
     * Get token expiration date
     *
     * @param null $ttl
     *
     * @return Carbon
     */
    public function getExpirationDate($ttl = null)
    {
        if($ttl === null) {
            $ttl = config('tezol.token_ttl', 3600);
        }

        return Carbon::now()->addSeconds($ttl);
    }

    /**
     * Generate new token with salt
     *
     * @param string $salt
     *
     * @return string
     */
    private function generateToken($salt = 'Im salt man, Not Sugar!')
    {
        $uniqueId = uniqid('token_' . Carbon::now()->timestamp . '_' . $salt , true);

        return md5($this->hasher->make($uniqueId));
    }

    /**
     * Check For Uniqueness
     *
     * check for database and if it exist in there regenerate other token and search for this new token too.
     *
     * @param $salt
     * @param $generatedToken
     *
     * @return string
     */
    private function checkForUniqueness($salt, $generatedToken)
    {
        if ($this->isUnique() === false) {
            return $generatedToken;
        }

        $isUnique = false;
        while ($isUnique === false) {
            if (Token::where('token', $generatedToken)->count() > 0) {
                $generatedToken = $this->generateToken($salt);
            }
            else {
                $isUnique = true;
            }
        }

        return $generatedToken;
    }

    /**
     * @return null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param null $model
     */
    public function setModel($model)
    {
        $this->model = $model;
        
        return $this;
    }

    /**
     * @param string $salt
     *
     * @return mixed | Token
     * @throws \Exception
     */
    public function create($salt = 'Im salt man, Not Sugar!')
    {
        if($this->model === null)
            throw new \Exception('You must set model first');

        $createdToken = $this->model->tokens()->create([
            'token' => $this->unique()->generate($salt),
            'expires_at'=> $this->getExpirationDate(),
        ]);

        return $createdToken;
    }

    /**
     * @param $token
     *
     * @return bool | Token
     */
    public function validate($token)
    {
        $token = Token::where('token', $token)
            ->where('expires_at', '>=', Carbon::now()->toDateTimeString())
            ->first();

        if($token === NULL)
            return false;

        return $token;
    }
}