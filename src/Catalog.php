<?php

namespace Sepia\PoParser;

use Sepia\PoParser\Catalog\Entry;
use Sepia\PoParser\Catalog\Header;

class Catalog
{
    /** @var Header */
    protected $headers;

    /** @var array */
    protected $entries;

    /**
     * @param Entry[] $entries
     */
    public function __construct(array $entries = array())
    {
        $this->entries = array();
        $this->headers = new Header();
        foreach ($entries as $entry) {
            $this->addEntry($entry);
        }
    }

    public function addEntry(Entry $entry)
    {
        $key = $this->getEntryHash(
            $entry->getMsgId(),
            $entry->getMsgCtxt()
        );
        $this->entries[$key] = $entry;
    }

    public function addHeaders(Header $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string      $msgid
     * @param string|null $msgctxt
     */
    public function removeEntry($msgid, $msgctxt = null)
    {
        $key = $this->getEntryHash($msgid, $msgctxt);
        $this->removeEntryByKey($key);
    }

    public function removeEntryByKey($key)
    {
        if (isset($this->entries[$key])) {
            unset($this->entries[$key]);
        }
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers->asArray();
    }

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->headers;
    }

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param string      $msgId
     * @param string|null $context
     *
     * @return Entry|null
     */
    public function getEntry($msgId, $context = null)
    {
        $key = $this->getEntryHash($msgId, $context);
        return $this->getEntryByKey($key);
    }

    public function getEntryByKey($key)
    {
        if (!isset($this->entries[$key])) {
            return null;
        }
        
        return $this->entries[$key];
    }

    /**
     * @param string      $msgId
     * @param string|null $context
     *
     * @return string
     */
    private function getEntryHash($msgId, $context = null)
    {
        return md5($msgId.$context);
    }
}
