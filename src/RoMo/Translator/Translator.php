<?php

declare(strict_types=1);

namespace RoMo\Translator;

use pocketmine\plugin\Plugin;

class Translator{

    /** @var array<string, string> */
    protected array $data;

    public function __construct(Plugin $plugin, string $resourceDirectory, string $dataDirectory, string $language, bool $isDev = false){
        $resourcePath = $resourceDirectory . "resources/messages";
        $dataPath = $dataDirectory . "messages/";

        if(!is_dir($dataPath)){
            mkdir($dataPath);
        }

        $dir = opendir($resourcePath);
        if($dir === false){
            throw new \RuntimeException("Unable to open translator resource directory: " . $resourcePath);
        }

        while(($read = readdir($dir)) !== false){
            if($read !== "." && $read !== ".."){
                if(!file_exists($dataPath . "/" . $read) || $isDev){
                    $messageFile = $resourcePath . "/" . $read;
                    copy($messageFile, $dataPath . "/" . $read);
                }
            }
        }
        closedir($dir);

        $data = parse_ini_file($dataPath . $language . ".ini", false);
        if($data === false){
            throw new \RuntimeException("Unable to load translator language file: " . $dataPath . $language . ".ini");
        }

        $this->data = $data;
        foreach($this->data as $key => $value){
            $this->data[$key] = str_replace("(enter)", "\n", $value);
        }
    }

    public function getPrefix() : string{
        if(!isset($this->data["prefix"])){
            return "prefix ";
        }
        return $this->data["prefix"];
    }

    /**
     * @param list<string|int|float|bool> $parameters
     */
    public function getTranslate(string $id, array $parameters = []) : string{
        if(!isset($this->data[$id])){
            return $id;
        }
        $str = $this->data[$id];

        $count = 1;
        foreach($parameters as $parameter){
            $str = str_replace("&{$count}", (string) $parameter, $str);
            $count++;
        }

        return $str;
    }

    /**
     * @param list<string|int|float|bool> $parameters
     */
    public function getMessage(string $id, array $parameters = []) : string{
        return $this->getPrefix() . $this->getTranslate($id, $parameters);
    }

    public function getCmd(string $id) : CommandTranslate{
        $commandId = "command.$id";

        $commandName = $this->data[$commandId . ".name"] ?? $id;
        $commandDescription = $this->data[$commandId . ".description"] ?? $id;
        $commandUsageMessage = $this->data[$commandId . ".usageMessage"] ?? $id;

        $aliases = [];
        $count = 1;

        while(isset($this->data[$commandId . ".aliases.$count"])){
            $aliases[] = $this->data[$commandId . ".aliases.$count"];
            $count++;
        }

        return new CommandTranslate($commandName, $commandDescription, $commandUsageMessage, $aliases);
    }
}
