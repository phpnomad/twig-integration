<?php

namespace PHPNomad\Twig\Integration\Interfaces;

interface TwigConfigProvider
{
    public function getTemplateDirectory(): string;
}