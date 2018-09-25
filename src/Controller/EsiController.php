<?php
namespace Drupal\esi_placeholders\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EsiController extends ControllerBase
{
    public function returnEsiBlockContent(Request $request)
    {
        // @todo validate $request->get('callback') and $request->get('args'), using private key + salt.
        $build = [
            'esiBlockContent' => [
                '#lazy_builder' => [
                    $request->get('callback'),
                    $request->get('args'),
                ]
            ]
        ];
        $output = \Drupal::service('renderer')->renderRoot($build);
        $response = new Response($output);
        return $response;
    }
}
