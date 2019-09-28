<?php

namespace SB\Api\Middleware;

use Framework\Middleware\AbstractMiddleware;
use Framework\Util\Arr;

class ServerTokenMiddleware extends AbstractMiddleware
{
    protected function preHandle()
    {
        $headers = $this->request->getHeaders();
        $token = '';
        if (Arr::keyExists($headers, 'X-ServerToken'))
        {
            $token = $headers['X-ServerToken'];
        }

        $server = \Framework::container()->get('database')
            ->mapper('\SB\Entity\Server')
            ->first(['token'    => $token]);

        if (!$server)
        {
            throw $this->exception($this->response->withJson([
                'success'   =>  false,
                'error'     =>  [
                    'code'      => 0,
                    'message'   => 'Invalid token passed',
                ],
            ], 400));
        }

        $this->request->set('server', $server);
    }
}