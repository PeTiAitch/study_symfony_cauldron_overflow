<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    /**
     * @var MarkdownParserInterface
     */
    private $markdownParser;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MarkdownParserInterface $markdownParser, 
                                CacheInterface $cache, 
                                bool $isDebug, 
                                LoggerInterface $markdownLogger)
    {
        $this->markdownParser = $markdownParser;

        $this->cache = $cache;

        $this->isDebug = $isDebug;

        $this->logger = $markdownLogger;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'cat') !== false) {
            $this->logger->info('Meow!');
        }

        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($source);
        }

        // MN: If cache key exists returns the cache, if not, saves new cache
        return $this->cache->get('markdown_' . md5($source), function () use ($source) {
            return $this->markdownParser->transformMarkdown($source);
        });

    }
}