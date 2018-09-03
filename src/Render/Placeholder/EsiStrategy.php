<?php
namespace Drupal\esi_placeholders\Render\Placeholder;

use Drupal\big_pipe\Render\Placeholder\BigPipeStrategy;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpCache\Esi;
class EsiStrategy extends BigPipeStrategy
{
    /**
     * @var RequestStack
     */
    protected $requestStack;
    /**
     * @var Esi
     */
    protected $esi;

    /**
     * EsiStrategy constructor.
     * @param RequestStack $request_stack
     * @param Esi $esi
     */
    public function __construct(RequestStack $request_stack, Esi $esi)
    {
        $this->requestStack = $request_stack;
        $this->esi = $esi;
    }

    /**
     * @param array $placeholders
     * @return array
     */
    public function processPlaceholders(array $placeholders)
    {
        $request = $this->requestStack->getCurrentRequest();
        $overridenPlaceHolder = [];
        foreach ($placeholders as $placeholder => $placeholder_elements) {
            if (isset($placeholder_elements['#lazy_builder']) && $this->esi->hasSurrogateCapability($request)) {
                $overridenPlaceHolder[$placeholder] = [
                    '#markup' =>
                        Markup::create(
                            $this->esi->renderIncludeTag(
                                '/esi/block/?'.
                                $this->generateBigPipePlaceholderId($placeholder,$placeholder_elements),
                                null,
                                false
                        )
                    )
                ];
            }
        }
        return $overridenPlaceHolder;
    }
}