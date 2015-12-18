<?php namespace Tajrish\Http\Controllers\V1;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tajrish\Helpers\ResponseFactory;

abstract class ApiController extends Controller {

    use Helpers;

    /**
     * Override parent response to return our
     * needed factory
     *
     * @override
     *
     * @return ResponseFactory
     */
    public function response()
    {
        return app(ResponseFactory::class);
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
