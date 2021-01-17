<?php

namespace PallyCon;


use PallyCon\Exception\PallyConTokenException;

class PlaybackPolicyRequest {
    public $_limit;
    public $_persistent;
    public $_licenseDuration;
    public $_expireDate;
    public $_allowedTrackTypes;

    public function __construct($persistent=false, $licenseDuration=0, $expireDate= "")
    {
        if(!is_null($persistent)) {
            if(is_bool($persistent)){
                $this->_persistent = $persistent;
            }else{
                throw new PallyConTokenException(1009);
            }
        }
        if(!empty($licenseDuration)) {
            if(is_numeric($licenseDuration)){
                $this->_licenseDuration = $licenseDuration;
            }else{
                throw new PallyConTokenException(1010);
            }
        }
        if(!empty($expireDate)) {
            if(preg_match('/[0-9]{4}-[0,1][0-9]-[0-5][0-9]T[0-2][0-3]:[0-5][0-9]:[0-5][0-9]Z/', $expireDate)){
                $this->_expireDate = $expireDate;
            }else{
                throw new PallyConTokenException(1011);
            }
        }
    }

    public function toArray(){
        $arr= [];
        if(isset($this->_persistent)){
            $arr["persistent"] = $this->_persistent;
        }
        if(isset($this->_licenseDuration)){
            $arr["license_duration"] = $this->_licenseDuration;
        }
        if(isset($this->_expireDate)){
            $arr["expire_date"] = $this->_expireDate;
        }
        if(isset($this->_allowedTrackTypes)){
            $arr["allowed_track_types"] = $this->_allowedTrackTypes;
        }
        return $arr;
    }

    /**
     * @return bool
     */
    public function isPersistent()
    {
        return $this->_persistent;
    }

    /**
     * @param bool $persistent
     */
    public function setPersistent($persistent)
    {
        $this->_persistent = $persistent;
    }

    /**
     * @return int|string
     */
    public function getLicenseDuration()
    {
        return $this->_licenseDuration;
    }

    /**
     * @param int|string $licenseDuration
     */
    public function setLicenseDuration($licenseDuration)
    {
        $this->_licenseDuration = $licenseDuration;
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return $this->_expireDate;
    }

    /**
     * @param string $expireDate
     */
    public function setExpireDate($expireDate)
    {
        $this->_expireDate = $expireDate;
    }

    /**
     * @return mixed
     */
    public function getAllowedTrackTypes()
    {
        return $this->_allowedTrackTypes;
    }

    /**
     * @param mixed $allowedTrackTypes
     */
    public function setAllowedTrackTypes($allowedTrackTypes)
    {
        $this->_allowedTrackTypes = $allowedTrackTypes;
    }



}
?>