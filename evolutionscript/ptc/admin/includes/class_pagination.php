<?php
/**
 * @ EvolutionScript
 * Created by NextPay.ir
 * author: Nextpay Company
 * ID: @nextpay
 * Date: 04/01/2017
 * Time: 5:45 PM
 * Website: NextPay.ir
 * Email: info@nextpay.ir
 * @copyright 2017
 * @package NextPay_Gateway
 * @version 1.0
 */
class Pagination {
	function Pagination($table_name, $conditional = null) {
		$this->tbl = $table_name;
		$this->cond = ($conditional != "" ? "WHERE " . $conditional : "");
		$this->page = 1;
		$this->max_results = 25;
		$this->total_pages = 1;
		$this->results = $this->getResults();
		$this->allowed = array();
	}

	function setOrders($orderby, $sortby) {
		$this->orderby = $orderby;
		$this->sortby = $sortby;

		if ($this->sortby == "ASC") {
			$this->nextsortby = "DESC";
			return null;
		}

		$this->nextsortby = "ASC";
	}

	function getResults() {
		global $db;

		$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM " . $this->tbl . " " . $this->cond);
		return $count;
	}

	function setMaxResult($result) {
		$this->max_results = $result;
	}

	function setPage($page) {
		$this->total_pages = ceil($this->results / $this->max_results);
		$page = (!is_numeric($page) || $this->total_pages < $page) ? 1 : $page;
		$this->page = $page;
	}

	function getFrom() {
		$from = $this->max_results * $this->page - $this->max_results;
		return $from;
	}

	function allowedfield($vars = null) {
		if ($vars !== null) {
			if (is_array($vars)) {
				foreach ($vars as $v) {
					$this->allowed[] = $v;
				}
			}
		}

	}

	function setNewOrders($orderby = null, $sortby = null) {
		if ($orderby !== null) {
			if (count($this->allowed) == 0) {
				$this->orderby = $orderby;
			}
			else {
				if (in_array($orderby, $this->allowed)) {
					$this->orderby = $orderby;
				}
			}
		}


		if ($sortby !== null) {
			if ($sortby != $this->sortby) {
				$this->oldsortby = $this->sortby;
				$this->sortby = $this->nextsortby;
				$this->nextsortby = $this->oldsortby;
			}
		}

	}

	function getQuery() {
		global $db;

		$q = $db->query("SELECT * FROM " . $this->tbl . " " . $this->cond . " ORDER BY " . $this->orderby . " " . $this->sortby . " LIMIT " . $this->getFrom() . ", " . $this->max_results);
		return $q;
	}

	function setLink($link) {
		$this->url = $link;
	}

	function linkorder($orderby, $name) {
		$url = "orderby=" . $orderby . "&sortby=" . $this->nextsortby . "&page=" . $this->page;

		if ($this->orderby == $orderby) {
			if ($this->sortby == "ASC") {
				$img = " <img src=\"./css/images/asc.png\" border=0 />";
			}
			else {
				$img = " <img src=\"./css/images/desc.png\" border=0 />";
			}
		}

		$path = "<a href=\"" . $this->url . $url . "\">" . $name . $img . "</a>";
		return $path;
	}

	function totalResults() {
		return $this->results;
	}

	function totalPages() {
		return $this->total_pages;
	}

	function getPage() {
		return $this->page;
	}

	function prevpage() {
		$url = $this->url . "orderby=" . $this->orderby . "&sortby=" . $this->sortby . "&page=" . ($this->page - 1);
		return $url;
	}

	function nextpage() {
		$url = $this->url . "orderby=" . $this->orderby . "&sortby=" . $this->sortby . "&page=" . ($this->page + 1);
		return $url;
	}

	function gotopage($page = null) {
		if ($page === null) {
			$page = $this->page;
		}

		$url = $this->url . "orderby=" . $this->orderby . "&sortby=" . $this->sortby . "&page=" . $page;
		return $url;
	}
}

?>