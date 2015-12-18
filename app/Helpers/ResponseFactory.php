<?php namespace Tajrish\Helpers;

use Dingo\Api\Http\Response;
use Dingo\Api\Http\Response\Factory;

class ResponseFactory extends Factory {

    /**
     * Override parent method
     *
     * @override
     *
     * @param null     $location
     * @param \Closure $data
     * @return Response
     *
     */
    public function created($location = null, \Closure $data = null)
    {
        list($item, $transformer, $headers) = $data();

        $class = get_class($item);

        $binding = $this->transformer->register($class, $transformer);

        $response =  new Response($item, 201, $headers, $binding);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }


}