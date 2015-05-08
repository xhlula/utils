<?php

namespace maldoinc\utils\pagination;

class Pagination
{
    /**
     * @var int number of records per page
     */
    public $recordsPerPage;

    /**
     * @var int current page number
     */
    protected $currentPage;

    /**
     * @var int number of total records
     */
    public $totalRecords;

    /**
     * @var int number of visible pages
     */
    public $nrPagesVisible;

    /**
     * @var bool show/hide first/last page links
     */
    public $showFirstLast = true;

    /**
     * @var string label for first page link
     */
    public $firstPageStr = 'First';

    /**
     * @var string label for last page link
     */
    public $lastPageStr = 'Last';

    /**
     * @param int $total the total number of records
     * @param int $per_page number of records visible per page
     * @param int $page current page
     * @param int $nr_pages_visible number of visible pages
     */
    public function __construct($total, $per_page, $page, $nr_pages_visible)
    {
        $this->recordsPerPage = $per_page;
        $this->totalRecords = $total;
        $this->nrPagesVisible = $nr_pages_visible;
        $this->setCurrentPage($page);
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Sets the current page. Value is casted to int before being assigned.
     * If the value is out of bounds it is defaulted to 1
     *
     * @param int $value
     */
    public function setCurrentPage($value)
    {
        $value = (int)$value;

        if (($value >= 1) && ($value <= $this->getTotalPages())) {
            $this->currentPage = $value;
        } else {
            // silently default to 1 if current page value is not valid
            $this->currentPage = 1;
        }
    }

    /**
     * Returns the number of records to be skipped
     *
     * @return int
     */
    public function getLimitOffset()
    {
        return ($this->currentPage - 1) * $this->recordsPerPage;
    }

    /**
     * Returns the number of total pages available
     *
     * @return float
     */
    public function getTotalPages()
    {
        return floor($this->totalRecords / $this->recordsPerPage) + (int)($this->totalRecords % $this->recordsPerPage > 0);
    }

    /**
     * Returns the pages to be rendered specified by the current settings
     *
     * @return array
     */
    public function getPages()
    {
        // in case of no data return an empty pagination array
        if ($this->totalRecords == 0) {
            return array();
        }

        // determine at which page will the pagination start and end
        $f = floor($this->nrPagesVisible / 2);
        $start = $this->currentPage - $f;
        $end = $this->currentPage + $f;

        // Let's set the upper bound

        // it should not be less than the requested amount of visible pages
        $min_upper_boundary = max($end, $this->nrPagesVisible);

        // but also should not exceed the total pages number
        $end = min($min_upper_boundary, $this->getTotalPages());

        // adjust the starting page accordingly
        // which should be end - visiblepages (inclusive)
        if ($end - $start < $this->nrPagesVisible) {
            $start = 1 + $end - $this->nrPagesVisible;
        }

        return range(max(1, $start), $end);
    }

    /**
     * Generates HTML based on the pages to be rendered and the callback function
     *
     * @param callable $reducefunc callback to the function which will return the markup
     * @return string
     */
    public function getHTML($reducefunc)
    {
        $html = "";
        $pages = $this->getPages();
        $cnt = count($pages);
        $total = $this->getTotalPages();

        if ($this->showFirstLast && (($cnt > 0) && ($pages[0] != 1))) {
            $html = $reducefunc(1, $this->firstPageStr);
        }

        $html .= array_reduce($pages, function ($result, $item) use ($reducefunc) {
            return $result . $reducefunc($item, $item);
        });

        if ($this->showFirstLast && (($cnt > 0) && ($pages[$cnt - 1] != $total))) {
            $html .= $reducefunc($total, $this->lastPageStr);
        }

        return $html;
    }
}