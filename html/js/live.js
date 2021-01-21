// 웹페이지 모든 페이지에 걸쳐 공통된 javascript 함수

window.onload = function() {

    // 이메일 인증
    // 팝업창을 띄우고, 이메일을 입력받는다
    const authButton = document.querySelector(".auth-button");
    const emailSubmitButton = document.querySelector(".popup__button.submit");
    const popupCancelButton = document.querySelector(".popup__button.cancel");
    authButton.addEventListener("click", viewInputPopup);
    emailSubmitButton.addEventListener("click", emailSubmit);
    popupCancelButton.addEventListener("click", inputCancel);

    function viewInputPopup() {
        document.querySelector(".popup").style.display = "flex";
    }

    function emailSubmit() {
        // 입력값이 있을때 데이터가 전달(submit)되도록 한다
        var emailInput = document.querySelector(".popup__input").value;
        if (emailInput.length > 0) {
            // TODO: 이메일 형식 확인 추가하기
            
            // 입력한 이메일로 인증번호를 발송한다
            fetch("send_live_certify_email.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    "email" : emailInput
                }),
            })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch((error) => console.log(error))
                // send_live_certify_email.php 의 이메일 발송 메소드에서 echo 로 리턴되는 메세지로 인해
                // 응답메세지를 json 으로 인식하지 못하는 문제가 있음
                // 이에 응답 response.json 이 아닌 response.text 로 값을 리턴받는다
        }
    }     
        
    function inputCancel() {
        document.querySelector(".popup").style.display = "none";
    }    


    // 이메일 인증 이후, 라이센스 얻기
    // 라이센스를 발급하기위해 필요한 정보를 제공해 주어야한다
    // drmType 은 브라우저 현재 접속한 브라우저를 통해 초기화

    let drmType;
    const agent = navigator.userAgent.toLowerCase(); 
    if (agent.indexOf("chrome") != -1) drmType = "Widevine";
    if (agent.indexOf("firefox") != -1) drmType = "Widevine";
    if (agent.indexOf("edge") != -1) drmType = "PlayReady";
    if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {
        drmType = "PlayReady";
    }
    // if (agt.indexOf("webtv") != -1) return 'WebTV'; 
    // if (agt.indexOf("beonex") != -1) return 'Beonex'; 
    // if (agt.indexOf("chimera") != -1) return 'Chimera'; 
    // if (agt.indexOf("netpositive") != -1) return 'NetPositive'; 
    // if (agt.indexOf("phoenix") != -1) return 'Phoenix'; 
    // if (agt.indexOf("safari") != -1) return 'Safari'; 
    // if (agt.indexOf("skipstone") != -1) return 'SkipStone'; 
    // if (agt.indexOf("netscape") != -1) return 'Netscape'; 
    // if (agt.indexOf("mozilla/5.0") != -1) return 'Mozilla'; 

    let licenseToken;
    let value = { "drmType": drmType, 
        userId: "hyukzza@naver.com",
        contentId: "test4",
    }

    fetch("generate_drm_token.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(value),
    })
        .then(response => response.json())
        .then(data => {
            licenseToken = data.licenseToken
            // console.log(licenseToken)
        })
        .catch((error) => console.log(error))



    
    // 플레이에어서 라이센스 DRM 연동하기!
    let player = videojs("video");

    player.ready(function(){
            player.src({
                'src': '/video/project/dash/stream.mpd',
                'type': 'application/dash+xml',
                'keySystemOptions': [
                    {
                        'name': 'com.widevine.alpha',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            'httpRequestHeaders' : {
                                'pallycon-customdata-v2' : licenseToken,
                                // 'pallycon-customdata-v2' : token,
                            }
                        }
                    },
                    {
                        'name': 'com.microsoft.playready',
                        'options':{
                            'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                            'httpRequestHeaders' : {
                                'pallycon-customdata-v2' : licenseToken,
                                // 'pallycon-customdata-v2' : token,
                            }
                        }
                    }
                ]
            });
        })
        player.play();
}