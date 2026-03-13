<?php

declare(strict_types=1);

namespace RoMo\Translator;

trait TranslatorHolderTrait{

    private static Translator $translator;

    public static function getTranslator() : Translator{
        return self::$translator;
    }

    public static function setTranslator(Translator $translator) : void{
        self::$translator = $translator;
    }
}
