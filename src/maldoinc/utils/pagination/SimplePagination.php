<?php

namespace maldoinc\utils\pagination;

/**
 * Pagination class that simplifies the generation of HTML page links
 * Defaults to using the twitter bootstrap friendly HTML.
 *
 * Class also implements __toString method, so you can just call it from your favourite templating engine as is
 *
 * Class SimplePagination
 * @package maldoinc\utils\pagination
 */
class SimplePagination extends Pagination
{
    protected static $defaultRowsPerPage = 10;
    public $href = null;

    /**
     * @param int $total total number of rows available
     * @param int $page current page
     * @param string $href string to be used as page formatting. %d denotes the page number.
     * Example: <code>path/to/resource/p/%d</code>
     */
    public function __construct($total, $page, $href)
    {
        parent::__construct($total, self::$defaultRowsPerPage, $page, 9);
        $this->href = $href;
    }

    /**
     * Sets the default number of rows per page to be used by all instances of this class
     *
     * @param int $val
     */
    public static function setDefaultRowsPerPage($val)
    {
        self::$defaultRowsPerPage = (int)$val;
    }

    public function __toString()
    {
        if (count($this->getPages()) <= 1) {
            return "";
        }

        $page = $this->getCurrentPage();
        $href = $this->href;

        return $this->getHtml(function ($page_nr, $label) use ($page, $href) {
            $class = $page_nr === $page ? ' class="active"' : '';
            $url = str_replace('%d', $page_nr, $href);

            return sprintf("<li%s><a href='%s'>%s</a></li>", $class, $url, $label);
        });
    }
}