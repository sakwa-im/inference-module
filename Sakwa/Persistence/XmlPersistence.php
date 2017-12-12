<?php

namespace Sakwa\Persistence;

/**
 * Class XmlPersistence
 *
 */
class XmlPersistence implements IPersistence
{
    /**
     * @var string
     */
    protected $fullFilePath;

    /**
     * @var string
     */
    protected $fileVersion;

    /**
     * @var \SimpleXMLElement
     */
    protected $record;

    /**
     * @var \SimpleXMLIterator
     */
    protected $recordIterator;

    /**
     * XmlPersistence constructor.
     *
     * @param string $fullFilePath
     * @param bool   $keepClosed
     *
     * @throws \Sakwa\Exception
     */
    public function __construct($fullFilePath, $keepClosed = false)
    {
        $this->fullFilePath = $fullFilePath;

        if (!$keepClosed) {
            $this->open();
        }
    }

    /**
     * @{inheritdoc}
     */
    public function getFileVersion()
    {
        return $this->fileVersion;
    }

    /**
     * @throws \Sakwa\Exception
     */
    public function open()
    {
        if (!file_exists($this->fullFilePath)) {
            $pwd = getcwd();
            throw new \Sakwa\Exception('File "'.$this->fullFilePath.'" does not exist."');
        }

        try {
            $xmlDocument = simplexml_load_file($this->fullFilePath);
        }
        catch(\Exception $e) {
            throw new \Sakwa\Exception('File "'.$this->fullFilePath.'" contains an invalid format."');
        }

        $this->fileVersion = $this->getAttributeValue($xmlDocument, 'version');
        $this->recordIterator = new \SimpleXMLIterator($xmlDocument->asXML());
    }

    /**
     * Select the next record.
     *
     * @return bool
     */
    public function nextRecord()
    {
        if ($this->record == null) {
            $this->recordIterator->rewind();
        } else {
            $this->recordIterator->next();
        }

        $this->record = $this->recordIterator->current();

        return $this->recordIterator->valid();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasField($name)
    {
        return $this->record != null && $this->record->{$name} != null;
    }

    /**
     * @param string $name
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getFieldValue($name, $defaultValue = null)
    {
        $result = $defaultValue;

        if ($this->hasField($name)) {
            if (isset($this->record->{$name}->element)) {
                // @codeCoverageIgnoreStart
                $result = (array)$this->record->{$name}->element;
                // @codeCoverageIgnoreEnd
            } else {
                $result = (string)$this->record->{$name};
            }
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $element
     * @param string            $name
     * @param string            $defaultValue
     *
     * @return string
     */
    protected function getAttributeValue(\SimpleXMLElement $element, $name, $defaultValue = '')
    {
        return $element->attributes()->{$name} != null ? (string)$element->attributes()->{$name} : $defaultValue;
    }
}