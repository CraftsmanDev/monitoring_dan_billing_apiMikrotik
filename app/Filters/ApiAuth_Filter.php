<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAuth_Filter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        throw new \Exception('Not implemented');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //tidak digunakan
    }
}
