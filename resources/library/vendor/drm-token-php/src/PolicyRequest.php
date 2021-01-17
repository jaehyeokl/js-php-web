<?php

namespace PallyCon;


class PolicyRequest
{
    public $_playbackPolicy;
    public $_securityPolicyArr;
    public $_externalKey;

    public function __construct(PlaybackPolicyRequest $playbackPolicyRequest=null
                                    , $securityPolicyArr=null
                                    , ExternalKeyRequest $externalKeyRequest=null)
    {
        if(!empty($playbackPolicyRequest)) {
            $this->_playbackPolicy =$playbackPolicyRequest ;
        }
        if(!empty($securityPolicyArr)) {
            $this->_securityPolicyArr = $securityPolicyArr;
        }
        if(!empty($externalKeyRequest)) {
            $this->_externalKey = $externalKeyRequest;
        }
    }

    public function toArray(){
        $arr= [];
        $securityPolicyArr = [];
        if(isset($this->_playbackPolicy)){
            $arr["playback_policy"] = $this->_playbackPolicy->toArray();
        }
        if(isset($this->_securityPolicyArr)){
            foreach ($this->_securityPolicyArr as $securityPolicy) {
                array_push($securityPolicyArr, $securityPolicy->toArray());
            }
            $arr["security_policy"] = $securityPolicyArr;
        }
        if(isset($this->_externalKey)){
            $arr["external_key"] = $this->_externalKey->toArray();
        }

        return $arr;
    }

    public function toJsonString(){
        return json_encode($this->toArray());
    }

    /**
     * @return PlaybackPolicyRequest
     */
    public function getPlaybackPolicy()
    {
        return $this->_playbackPolicy;
    }

    /**
     * @param $playbackPolicyRequest
     */
    public function setPlaybackPolicy(PlaybackPolicyRequest $playbackPolicyRequest)
    {
        $this->_playbackPolicy = get_object_vars($playbackPolicyRequest);
    }

    /**
     * @return SecurityPolicyRequest
     */
    public function getSecurityPolicy()
    {
        return $this->_securityPolicyArr;
    }

    /**
     * @param $securityPolicyRequestArr
     */
    public function setSecurityPolicy($securityPolicyRequestArr)
    {
        $this->_securityPolicyArr = $securityPolicyRequestArr;
    }

    public function pushSecurityPolicy(SecurityPolicyRequest $securityPolicyRequest)
    {
        array_push($this->_securityPolicyArr,  $securityPolicyRequest);
    }

    /**
     * @return ExternalKeyRequest
     */
    public function getExternalKey()
    {
        return $this->_externalKey;
    }

    /**
     * @param $externalKeyRequest
     */
    public function setExternalKey(ExternalKeyRequest $externalKeyRequest)
    {
        $this->_externalKey = get_object_vars($externalKeyRequest);
    }





}