<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();

	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false, $seller = NULL) {
		if($this->ssl && $secure && $seller){
			$url = str_replace("admin","seller",$this->ssl). 'index.php?route='.$route;
		}elseif ($this->ssl && $secure) {
			$url = $this->ssl . 'index.php?route=' . $route;
		} else {
			$url = $this->url . 'index.php?route=' . $route;
		}

		//This is the original text without the seller stuff as above
		// if ($this->ssl && $secure) {
		// 	$url = $this->ssl . 'index.php?route=' . $route;
		// } else {
		// 	$url = $this->url . 'index.php?route=' . $route;
		// }
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}
