<?php

namespace Dusterio\LinkPreview\Parsers;

use Dusterio\LinkPreview\Contracts\LinkInterface;
use Dusterio\LinkPreview\Contracts\ReaderInterface;
use Dusterio\LinkPreview\Contracts\ParserInterface;
use Dusterio\LinkPreview\Contracts\PreviewInterface;
use Dusterio\LinkPreview\Models\VideoPreview;
use Dusterio\LinkPreview\Readers\HttpReader;

/**
 * Class YouTubeParser
 */
class SoundcloudParser extends BaseParser implements ParserInterface
{
    /**
     * Url validation pattern taken from http://stackoverflow.com/questions/2964678/jquery-youtube-url-validation-with-regex
     */
    const PATTERN = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|soundcloud\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/';

    /**
     * @param ReaderInterface $reader
     * @param PreviewInterface $preview
     */
    public function __construct(ReaderInterface $reader = null, PreviewInterface $preview = null)
    {
        $this->setReader($reader ?: new HttpReader());
        $this->setPreview($preview ?: new VideoPreview());
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return 'soundcloud';
    }

    /**
     * @inheritdoc
     */
    public function canParseLink(LinkInterface $link)
    {
        return (preg_match(static::PATTERN, $link->getUrl()));
    }

    /**
     * @inheritdoc
     */
    public function parseLink(LinkInterface $link)
    {
        preg_match(static::PATTERN, $link->getUrl(), $matches);

        $this->getPreview()
            ->setId($matches[1])
            ->setEmbed(
              '<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay
                  src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/293&amp;'.$this->getPreview()->getId().'">
                </iframe>'
            );

        return $this;
    }
}
