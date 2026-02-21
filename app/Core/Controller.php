<?php

namespace Tarn\Core;

abstract class Controller {
    
    /**
     * Return a rendered view as a Response.
     *
     * @param string $view
     * @param array $data
     * @param int $status
     * @return Response
     */
    protected function view(string $view, array $data = [], int $status = 200): Response {
        return (new Response())->view($view, $data, $status);
    }

    /**
     * Return a JSON encoded Response.
     *
     * @param mixed $data
     * @param int $status
     * @return Response
     */
    protected function json(mixed $data, int $status = 200): Response {
        return (new Response())->json($data, $status);
    }

    /**
     * Return a Redirect Response.
     *
     * @param string $url
     * @param int $status
     * @return Response
     */
    protected function redirect(string $url, int $status = 302): Response {
        return (new Response())->redirect($url, $status);
    }

    /**
     * Redirect back with an error or success flash message and old input.
     *
     * @param Request $request
     * @param string $redirectUrl
     * @param string $type e.g 'error' or 'success'
     * @param string $message
     * @return Response
     */
    protected function backWithFlash(Request $request, string $redirectUrl, string $type, string $message): Response {
        Session::flash($type, $message);
        Session::flashInput($request); // Save input so the user doesn't lose form data
        return $this->redirect($redirectUrl);
    }
}
