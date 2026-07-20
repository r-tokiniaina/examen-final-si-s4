<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (in_array('bypass', $arguments)) {
            if ($session->has('admin')) {
                return redirect()->to('/operateur/dashboard');
            } else if ($session->has('client')) {
                return redirect()->to('/client/operations');
            }
        } else {
            if (in_array('admin', $arguments)) {
                if (!$session->has('admin')) {
                    return redirect()->to('/operateur/login')->with('message_erreur', 'Vous n’êtes pas autorisé à accéder à cette page');
                }
            } else if (in_array('client', $arguments)) {
                if (!$session->has('client')) {
                    return redirect()->to('/login')->with('message_erreur', 'Vous n’êtes pas autorisé à accéder à cette page');
                }
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
