<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 18.02.17
 * Time: 1:31 PM
 */

namespace rollun\callback\Middleware;


use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use rollun\callback\Callback\Interruptor\InterruptorInterface;
use rollun\utils\Json\Serializer;
use Zend\Diactoros\Response\EmptyResponse;

class InterruptorCallerAction extends InterruptorAbstract
{
    /** @var  InterruptorInterface */
    protected $interruptor;

    /**
     * InterruptorAbstract constructor.
     * @param InterruptorInterface $interruptor
     */
    public function __construct(InterruptorInterface $interruptor)
    {
        $this->interruptor = $interruptor;
    }

    /**
     * Call interrupt with value.
     * @param Request $request
     * @param DelegateInterface $delegate
     * @return Response
     */
    public function process(Request $request, DelegateInterface $delegate)
    {
        $value = $request->getAttribute(AbstractParamsResolver::ATTRIBUTE_WEBHOOK_VALUE);
        try {
            $result = call_user_func($this->interruptor, $value);
            $request = $request->withAttribute('responseData', $result);
            $response = new EmptyResponse(200);
        } catch (Exception $e) {
            $request = $request->withAttribute('responseData', ['responseData', ['error' => $e->getMessage()]]);
            $response = new EmptyResponse(500);
        }

        $request = $request->withAttribute(Response::class, $response);

        $response = $delegate->process($request);

        return $response;
    }
}