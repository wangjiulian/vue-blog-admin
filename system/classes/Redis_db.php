<?php
class Redis_db {
	const DEFAULT_DB = 0;
	private static $instances = array();
    private static $redis_db = null;

	static function getRedis($db = self::DEFAULT_DB) {

		if(!array_key_exists($db, self::$instances)) {
			//$enablePConnect = $db = self::DEFAULT_DB;
            $redis = new Redis();
            if(defined('REDIS_PCONNECT') && REDIS_PCONNECT) {
                $redis->pconnect(REDIS_SERVER, REDIS_PORT);
                $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
                $redis->select($db);
            } else {
                $redis->connect(REDIS_SERVER, REDIS_PORT, 120);
                $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
                if($db != self::DEFAULT_DB) {
                    $redis->select($db);
                }
                self::$instances[$db] = $redis;
            }
		} else {
			$redis = self::$instances[$db];
		}
		return $redis;
	}

	static function & generateKey($ns, $key) {
		$realKey = "{$ns}_{$key}";
		return $realKey;
	}

    /**
     * @param $ns
     * @param $key
     * @param null|closure $callback
     * @param bool $refreshTimeout
     * @param int $timeout
     * @param int $db
     * @return bool|string
     * @throws Exception
     */
    static function get($ns, $key, $callback=null, $refreshTimeout = true, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			$result = $redis->get($redisKey);
			if(!$result) {
				if($callback) {
					$result = $callback();
					if(!is_null($result)) {
						if($timeout>0) {
							$redis->setex($redisKey, $timeout, $result);
						} else {
							$redis->set($redisKey, $result);
						}
					}
				}
			} elseif($refreshTimeout && $timeout>0)  {
				$redis->expire($redisKey, $timeout);
			}
			return $result;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function mGet($ns, $keys, $db=0) {
		$redisKeys = array();
		foreach($keys as $key) {
			$redisKeys[] = self::generateKey($ns, $key);
		}
		$redis = self::getRedis($db);
		try{
			$result = $redis->mGet($redisKeys);
			return $result;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function set($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			if($timeout>0) {
				return $redis->setex($redisKey, $timeout, $value);
			} else {
				return $redis->set($redisKey, $value);
			}
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function add($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
        $redisKey = self::generateKey($ns, $key);
        $redis = self::getRedis($db);
        try{
            $r = $redis->multi()->setnx($redisKey, $value);
            if($timeout>0) {
                $r->expire($redisKey, $timeout);
            }
            $result = $r->exec();
            foreach($result as $v) {
                if(!$v) {
                    return false;
                }
            }
            return true;
        } catch( Exception $e ) {
            throw $e;
        }
	}

	static function incr($ns, $key, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			if($timeout>0) {
				$r = $redis->multi()->incr($redisKey);
				$r->expire($redisKey, $timeout);
				$r->exec();
			} else {
				return $redis->incr($redisKey);
			}

			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}


	static function increase($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			$r = $redis->multi()->incrBy($redisKey, $value);
			if($timeout>0) {
				$r->expire($redisKey, $timeout);
			}
			$r->exec();
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function decrease($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			$r = $redis->multi()->decrBy($redisKey, $value);
			if($timeout>0) {
				$r->expire($redisKey, $timeout);
			}
			$r->exec();
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function delete($ns, $key, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->del($redisKey);
		} catch( Exception $e ) {
			throw $e;
		}
	}

    static function flushAll($db = 0) {

        $redis = self::getRedis($db);
        try{
            return $redis->flushAll();
        } catch( Exception $e ) {
            throw $e;
        }
    }

	static function loadHash($ns, $hName, $callback, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			if($redis->ttl($redisKey) > 30) {
				return true;
			}
			$data = $callback();
			if($data && is_array($data)) {
				$r = $redis->multi()->hMSet($redisKey, $data);
				if($timeout > 0) {
					$r->expire($redisKey, $timeout);
				}
				$r->exec();
			}
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function &loadHashAndReturn($ns, $hName, $callback, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			if($redis->ttl($redisKey) > 30) {
				$data = $redis->hGetAll($redisKey);
				return $data;
			}
			$data = $callback();
			if($data && is_array($data)) {
				$r = $redis->multi()->hMSet($redisKey, $data);
				if($timeout>0) {
					$r->expire($redisKey, $timeout);
				}
				$r->exec();
			}
			return $data;
		} catch( Exception $e ) {
			throw $e;
		}
	}


	static function setHashValue($ns, $hName, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			$r = $redis->multi()->hSet($redisKey, $key, $value);
			if($timeout>0) {
				$r->expire($redisKey, $timeout);
			}
			$r->exec();
			if($timeout <= 0) {
				if($redis->ttl($redisKey) < 0) {
					$redis->expire($redisKey, 3600);
				}
			}
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function delHashValue($ns, $hName, $key, $db = 0) {
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			$redis->hDel($redisKey, $key);
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function & getFromHash($ns, $hName, $key, Closure $loadCallback = null, $timeout = REDIS_TIME_OUT, $db = 0) {
		$data = null;
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			$data = $redis->hGet($redisKey, $key);
			if(!$data && !is_null($loadCallback)) {
				$arr = $loadCallback();
				if($arr && is_array($arr)) {
					$r = $redis->multi()->hMSet($redisKey, $arr);
					if($timeout>0) {
						$r->expire($redisKey, $timeout);
					}
					$r->exec();
					if(array_key_exists($key, $arr)) {
						$data = $arr[$key];
					}
				}
			}
		} catch( Exception $e ) {
			throw $e;
		}
		if($data) {
			return $data;
		} else {
			$data = null;
			return $data;
		}
	}

	static function & getValueFromHash($ns, $hName, $key, Closure $loadCallback = null, $timeout = REDIS_TIME_OUT, $db = 0) {
		$data = null;
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			self::loadHash($ns, $hName, $loadCallback, $timeout, $db);
			$data = $redis->hGet($redisKey, $key);
		} catch( Exception $e ) {
			throw $e;
		}
		if($data) {
			return $data;
		} else {
			$data = null;
			return $data;
		}
	}

	static function & getMultiFromHash($ns, $hName, $keys, $db = 0) {
		$data = null;
		$redisKey = self::generateKey($ns, $hName);
		$redis = self::getRedis($db);
		try{
			$data = $redis->hMGet($redisKey, $keys);
		} catch( Exception $e ) {
			throw $e;
		}
		if($data) {
			return $data;
		} else {
			$data = null;
			return $data;
		}
	}

	static function addToSet($ns, $sName, $val, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		try{
			$r = $redis->multi()->sAdd($redisKey, $val);
			if($timeout>0) {
				$r->expire($redisKey, $timeout);
			}
			$r->exec();
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function getRandomMemberFromSet($ns, $sName, $db = 0) {
		$data = null;
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		try{
			$data = $redis->sRandMember($redisKey);
		} catch( Exception $e ) {
			throw $e;
		}
		return $data;
	}

	static function zAdd($ns, $sName, $score, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		try{
			$r = $redis->multi()->zAdd($redisKey, $score, $value);
			if($timeout>0) {
				$r->expire($redisKey, $timeout);
			}
			$r->exec();
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function zGet($ns, $sName, $callback, $timeout = REDIS_TIME_OUT, $refreshTimeout = true, $db = 0) {
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		try{
			$result = $redis->get($redisKey);
			if(!$result) {
				$result = $callback();
				if(!is_null($result) && $refreshTimeout) {
					$r = $redis->multi();
					foreach($result as $row) {
						foreach($row as $key => $val) {
							$r->zAdd($redisKey, $val, $key);
						}
					}
					if($timeout > 0) {
						$r->expire($redisKey, $timeout);
					}
					$r->exec();
				}
			} elseif($timeout>0)  {
				$redis->expire($redisKey, $timeout);
			}
			return $result;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function zGetRank($ns, $sName, $val, $db = 0) {
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		return $redis->zRevRank($redisKey, $val);
	}

	static function zGetRange($ns, $sName, $iStart = 0, $isEnd = -1, $iType = 0, $db = 0) {
		$redisKey = self::generateKey($ns, $sName);
		$redis = self::getRedis($db);
		if($iType > 0) { //desc
			return $redis->zRevRange($redisKey, $iStart, $isEnd);
		}
		return $redis->zRange($redisKey, $iStart, $isEnd);
	}

	static function listGet($ns, $key, $callback, $index, $refresh = false, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			$result = $redis->lGet($redisKey, $index);
			if(is_null($result)) {
				$result = $callback();
				if($refresh) {
					$r = $redis->multi()->lSet($redisKey, $index, $result, $db);
					if($timeout>0) {
						$r->expire($redisKey, $timeout);
					}
					$r->exec();
				} else {
					$redis->lSet($redisKey, $index, $result);
				}
			} elseif($refresh) {
				$redis->expire($redisKey, $timeout);
			}
			return $result;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listGetRange($ns, $key, $callback, $start, $end, $refresh = false, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			$result = $redis->lGetRange($redisKey, $start, $end);
			if(is_null($result)) {
				$result = $callback();
				if($result) {
					$i = $start;
					$r = $redis->multi();
					foreach($result as $data) {
						$r->lSet($redisKey, $i++, $data);
					}
					if($refresh && $timeout>0) {
						$r->expire($redisKey, $timeout);
					}
					$r->exec();
				}
			} elseif($refresh && $timeout>0)  {
				$redis->expire($redisKey, $timeout);
			}
			return $result;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listLPush($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			if($timeout > 0) {
				$r = $redis->multi()->lPush($redisKey, $value);
				$r->expire($redisKey, $timeout);
				$r->exec();
			} else {
				$redis->lPush($redisKey, $value);
			}
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listRPush($ns, $key, $value, $timeout = REDIS_TIME_OUT, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			if($timeout > 0) {
				$r = $redis->multi()->rPush($redisKey, $value);
				$r->expire($redisKey, $timeout);
				$r->exec();
			} else {
				$redis->rPush($redisKey, $value);
			}
			return true;
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listLPop($ns, $key, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->lPop($redisKey);
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listRPop($ns, $key, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->rPop($redisKey);
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function listTrim($ns, $key, $start, $end, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->lTrim($redisKey, $start, $end);
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function zGetRangeByScore($ns, $key, $start, $end, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->zRangeByScore($redisKey, $start, $end);
		} catch( Exception $e ) {
			throw $e;
		}
	}

	static function zDeleteRangeByScore($ns, $key, $start, $end, $db = 0) {
		$redisKey = self::generateKey($ns, $key);
		$redis = self::getRedis($db);
		try{
			return $redis->zRemRangeByScore($redisKey, $start, $end);
		} catch( Exception $e ) {
			throw $e;
		}
	}

    /**
     * @return Redis_db
     */
    static function getInstance() {
        if(is_null(self::$redis_db)) {
            self::$redis_db = new Redis_db();
        }
        return self::$redis_db;
    }
}
