<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Model;

/**
 * Class PersistedNameValueStore
 * @package AndriusJankevicius\Supermetrics\Model
 */
class PersistedNameValueStore
{
    /**
     * @var array
     */
    private $data;

    private $path;

    /**
     * PersistedNameValueStore constructor.
     * @param string $path
     */
    public function __construct(string $path = '/tmp/app_store.json')
    {
        $this->data = [];
        $this->path = $path;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function find(string $name)
    {
        if (!$this->data) {
            $this->data = $this->loadDataFile();
        }

        if (!isset($this->data[$name])) {

            throw new \InvalidArgumentException('The requested ' . $name .' was not found in the data file');
        }

        return unserialize($this->data[$name], [false]);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function persist(string $name, $value): void
    {
        $this->data[$name] = serialize($value);
    }

    /**
     * @return array
     */
    private function loadDataFile(): array
    {
        $filename = $this->getFilePath();
        $content = file_get_contents($filename) ?: '[]';
        $data = json_decode($content, true);

        return $data ?? [];
    }

    /**
     * Flush data to disk
     */
    public function flush(): void
    {
        $filename = $this->getFilePath();
        $content = json_encode($this->data);
        file_put_contents($filename, $content);
    }

    /**
     * @return string
     */
    private function getFilePath(): string
    {
        if (!file_exists($this->path)) {
            touch($this->path);
        }

        return $this->path;
    }
}
