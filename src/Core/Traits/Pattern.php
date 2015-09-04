<?php

namespace DSH\Bencode\Core\Traits;

/**
 * Consider that every Element has a REGEX pattern that can be matched
 * and that each Element object implements a PATTERN constant. This
 * method is used only in the Collection Elements (List, Dictionary)
 * due to their strict rules of being becoding.
 */
trait Pattern {

    /**
     * Matches the stream to the Element's regex pattern, removed
     * it from the stream.
     *
     * @param string $stream    The raw encoded stream
     * @param string $pattern   The REGEX pattern of the element.
     *
     * @return string           The stream without the prefix and suffix.
     */
    public function dropEncoding($stream, $pattern)
    {
        if (preg_match($pattern, $stream)) {
            $stream = substr($stream, 1);
            $stream = substr($stream, 0, -1);
        }

        return $stream;
    }
}
