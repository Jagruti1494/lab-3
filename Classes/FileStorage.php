<?php
class FileStorage
{
    private string $directory;

    public function __construct(string $folder = '/data/')
    {
        $this->directory = dirname(__DIR__) . $folder;
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
    }

    public function store(string $filename, array $content): bool
    {
        $file = $this->directory . $filename . '.json';
        return file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT)) !== false;
    }

    public function retrieve(string $filename): array
    {
        $file = $this->directory . $filename . '.json';

        if (is_file($file) && is_readable($file)) {
            $jsonContent = file_get_contents($file);
            $decodedContent = json_decode($jsonContent, true);

            return json_last_error() === JSON_ERROR_NONE ? $decodedContent : [];
        }

        return [];
    }
}
?>
