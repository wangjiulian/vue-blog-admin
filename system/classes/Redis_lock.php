<?php
class Redis_lock {

	private $locks;
	private $lockTimeout = 15000000;
	private static $lock = null;

	public function __construct() {
		$this->locks = array();
	}

	public function __destruct() {
		//$this->clean();
	}

	public function clean() {
		if($this->locks) {
			foreach($this->locks as $lock => $tmp) {
				try {
                    Redis_db::delete(REDIS_LOCK_NAMESPACE, $lock);
				} catch (Exception $e) {
                    throw $e;
				}
			}
			$this->locks = array();
		}
	}

	public function tryLock($mutex, $timeout = 20) {
		if(!array_key_exists($mutex, $this->locks)) {
			if(!Redis_db::add(REDIS_LOCK_NAMESPACE, $mutex, 1, $timeout)) {
				return false;
			}
			$this->locks[$mutex] = 1;
			return true;
		} else {
			$this->locks[$mutex]++;
		}
		return false;
	}


	public function lock($mutex, $timeout = 20) {
		if(!array_key_exists($mutex, $this->locks)) {
			$i = 0;
			while(!Redis_db::add(REDIS_LOCK_NAMESPACE, $mutex, 1, $timeout)) {
				if($i > $this->lockTimeout) {
                    return false;
				}
				$sleep = mt_rand(10000, 50000);
				usleep($sleep);
				$i += $sleep;
			}
			$this->locks[$mutex] = 1;
			return true;
		} else {
			$this->locks[$mutex]++;
		}
		return false;
	}

	public function lockNoWait($mutex, $timeout = 20) {
		if(!array_key_exists($mutex, $this->locks)) {
			if(!Redis_db::add(REDIS_LOCK_NAMESPACE, $mutex, 1, $timeout)) {
				return false;
			}
			$this->locks[$mutex] = 1;
			return true;
		} else {
			$this->locks[$mutex]++;
		}
		return false;
	}

	public function release($mutex) {
		if(!array_key_exists($mutex, $this->locks)) {
			return;
		}
		if($this->locks[$mutex] <= 1) {
            Redis_db::delete(REDIS_LOCK_NAMESPACE, $mutex);
			unset($this->locks[$mutex]);
		} else {
			$this->locks[$mutex] -= 1;
		}
	}

    /**
     * @return Redis_lock
     */
	static function getInstance() {
		if(is_null(self::$lock)) {
			self::$lock = new Redis_lock();
		}
		return self::$lock;
	}	

	function dispose() {
		$this->clean();
	}

}