<?php

namespace PHPNomad\Twig\Integration\Strategies;

use PHPNomad\Template\Exceptions\TemplateException;
use PHPNomad\Template\Exceptions\TemplateNotFound;
use PHPNomad\Template\Interfaces\CanRender;
use PHPNomad\Twig\Integration\Interfaces\TwigConfigProvider;
use PHPNomad\Utils\Helpers\Str;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigEngine implements CanRender
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * TwigEngine constructor.
     *
     * @param TwigConfigProvider $configProvider
     */
    public function __construct(protected TwigConfigProvider $configProvider)
    {

    }

    protected function twig()
    {
        if (!isset($this->twig)) {
            $templateDirectory = $this->configProvider->getTemplateDirectory();
            $loader = new FilesystemLoader($templateDirectory);
            $this->twig = new Environment($loader);
        }

        return $this->twig;
    }

    /**
     * @inheritDoc
     */
    public function render(string $templatePath, array $data = []): string
    {
        $templateFile = Str::append($templatePath, '.twig');
        if (!$this->twig()->getLoader()->exists($templateFile)) {
            throw new TemplateNotFound("The provided template $templatePath could not be found");
        }

        try {
            return $this->twig()->render($templateFile, $data);
        } catch (\Exception $e) {
            throw new TemplateException('Something went wrong when rendering template', 0, $e);
        }
    }
}
