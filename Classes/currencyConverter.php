<?php

class CurrencyConverter
{
    public array $exchanges;

    public function __construct()
    {
        $this->exchanges = array();
    }

    public function getCurrency(): void
    {
        $url = 'https://www.cbr.ru/scripts/XML_daily.asp'; // Ссылка на XML-файл с курсами валют, будут самые актуальные значения курса

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);https://www.cbr.ru/scripts/XML_daily.asp
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content = curl_exec($ch);
        curl_close($ch);

        $xml = @simplexml_load_string($content);

        foreach ($xml->Valute as $item) {
            $this->exchanges[chr(39).$item->Name.chr(39)] = (string)$item->Value;
        }
    }
}

?>