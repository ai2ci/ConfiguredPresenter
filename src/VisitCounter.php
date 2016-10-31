<?php

namespace Counter;

/**
 * Base class VisitCounter is aimed to counting of visits in calling cookies 
 *
 * @author chlad
 */
class VisitCounter
{
    const BEEN_HERE = 'beenHere';
    /** @var string */
    protected $cookieName;

    /** @var array */
    protected $requestCounter = [];

    function __construct($name = null, $action = null)
    {
        $pieces = ['configured'];
        if ($name !== null) {
            $pieces[] = $name;
            if ($action !== null) {
                $pieces[] = $action;
            }
        }
        $pieces[] = "been-here";
        $this->cookieName = implode("-", $pieces);
    }

    /**
     * default cookie name
     * @return string
     */
    protected function getCookieName($cookieName = null)
    {
        # automatic cookie name
        if ($cookieName === null) {
            $cookieName = $this->cookieName;
        }
        return $cookieName;
    }

    /**
     * return count of been here
     * @param string $cookieName
     * @return int
     */
    public function countOfBeenHere($cookieName = null)
    {
        $count = 0;
        $beenHere = filter_input(INPUT_GET, self::BEEN_HERE);
        # forced from URL
        if (isset($beenHere)) {
            $count = intval($beenHere);
        } else { # normally from cookie
            $cookieName = $this->getCookieName($cookieName);

            # if has been set then directly get value
            if (isset($this->requestCounter[$cookieName])) {
                $count = $this->requestCounter[$cookieName];
            }
            # inrease value
            else {
                $count = $this->getHttpRequest()->getCookie($cookieName) ?: 0;
                $count++;
                $this->getHttpResponse()->setCookie($cookieName, $count, time() + 3600 * 24 * 7);
                $this->requestCounter[$cookieName] = $count;
                $count--;
            }
        }
        return $count;
    }
}