<?php

namespace Manadinho\RPGFastDebugger;

use WebSocket\Client;
use DOMDocument;

class RPGFast{
    protected $data = [];
    protected $flag = null;
    protected $meta = '';
    protected $filePath = '';
    protected $lineNumber = '';
    protected $host = 'host.docker.internal';

    public function __construct($arg)
    {
        ob_start();
        dump($arg);
        $this->data[] = $this->generateDebugData(ob_get_clean());

        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $caller = $trace[1];

        $filePath = $caller['file'];
        $lineNumber = $caller['line'];
        $filePath = (strpos($filePath, "/html/sites") !== false) ? explode('/sites', $filePath)[0]."/ezad-localdev/sites".explode('/sites', $filePath)[1] : $filePath;

        $this->filePath = $filePath;
        $this->lineNumber = $lineNumber;
    }

    public function __destruct()
    {
        $this->send();
    }

    public function flag($flag)
    {
        if(in_array(getType($flag), ['string', 'integer']))
        {
            $this->flag = $flag;
        }
        return $this;
    }

    public function host($host)
    {
        if(getType($host) == 'string') {
            $this->host = $host;
        }
        return $this;
    }

    private function generateDebugData(String $rawHtml)
    {
        preg_match_all('/<script[^>]*>(.*?)<\/script>/is', $rawHtml, $scripts);
        preg_match_all('/<pre[^>]*>(.*?)<\/pre>/is', $rawHtml, $pres);
        $html = $pres[0][0];
        $doc = new DOMDocument();
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $spans = $doc->getElementsByTagName('span');
        foreach ($spans as $span) {
            if (strpos($span->textContent, '// vendor/manadinho/rpg-fast-debugger/src/RPGFast.php') !== false) {
                $span->parentNode->removeChild($span);
            }
        } 
        $modifiedHtml = $doc->saveHTML();
        return $scripts[0][1].$modifiedHtml;
    }

    public function send()
    {
        try {
            $client = new Client("ws://".$this->host.":23518");
            $client->send(json_encode(['logType' => 'laravel', 'flag' => $this->flag, 'meta' => $this->meta, 'data' => $this->data, 'filePath' => $this->filePath, 'lineNumber' => $this->lineNumber]));
        } catch (\Throwable $th) {
            
        }
    }
}