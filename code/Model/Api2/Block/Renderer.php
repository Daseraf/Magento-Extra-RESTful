<?php

/**
 * Renders just the content of a block as HTML
 *
 * @author Daniel Deady <daniel@clockworkgeek.com>
 * @license MIT
 */
class Clockworkgeek_Extrarestful_Model_Api2_Block_Renderer implements Mage_Api2_Model_Renderer_Interface
{
    /**
     * Adapter mime type
     */
    const MIME_TYPE = 'text/html';

    /**
     * Extract HTML field
     *
     * @param array|object $data
     * @return string
     */
    public function render($data)
    {
        return (string) @$data['content'];
    }

    /**
     * Get MIME type generated by renderer
     *
     * @return string
     */
    public function getMimeType()
    {
        return self::MIME_TYPE;
    }
}
