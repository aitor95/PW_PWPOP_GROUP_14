<?php

namespace PwPop\View;

class Renderer{
    /**
     * @param string $file
     * @param array $data
     * @return string
     * @throws RenderException
     */
    public function render(string $file, array $data = []): string
    {
        if (is_array($data) && !empty($data)) {
            extract($data);
        }

        $fileToRender = __DIR__ . "/templates/" . $file . ".twig";

        ob_start();

        include_once $fileToRender;

        return ob_get_clean();


    }
}
