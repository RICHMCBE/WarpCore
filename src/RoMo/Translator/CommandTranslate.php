<?php

declare(strict_types=1);

namespace RoMo\Translator;

class CommandTranslate{

    private string $name;
    private string $description;
    private string $usage;

    /** @var string[] */
    private array $aliases;

    /**
     * @param string[] $aliases
     */
    public function __construct(string $name, string $description, string $usage, array $aliases){
        $this->name = $name;
        $this->description = $description;
        $this->usage = $usage;
        $this->aliases = $aliases;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getDescription() : string{
        return $this->description;
    }

    public function getUsage() : string{
        return $this->usage;
    }

    /**
     * @return string[]
     */
    public function getAliases() : array{
        return $this->aliases;
    }
}
