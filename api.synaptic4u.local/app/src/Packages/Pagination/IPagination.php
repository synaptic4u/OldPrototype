<?php

namespace Synaptic4U\Packages\Pagination;

interface IPagination{
    public function list($params);
    public function page($params, $page);
}
?>