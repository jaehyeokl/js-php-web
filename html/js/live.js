// 웹페이지 모든 페이지에 걸쳐 공통된 javascript 함수

window.onload = function() {

    // 라이브 시청을 위해 이메일 인증하기
    // 나타나는 팝업창에 이메일을 입력하면, 이메일로 인증번호가 전달된다
    // 수신한 메일의 인증번호를 다시 팝업창에 등록하여 인증 절차를 거치도록 한다
    
    // 팝업창 버튼 및 팝업창 내 input, button 초기화
    const authButton = document.querySelector(".auth-button")
    const emailSubmitButton = document.querySelector(".popup__button.submit_email")
    const certifyNumberSubmitButton = document.querySelector(".popup__button.submit_certify_number")    
    const popupCancelButton = document.querySelector(".popup__button.cancel")
    
    authButton.addEventListener("click", viewInputPopup)
    emailSubmitButton.addEventListener("click", submitEmail)
    certifyNumberSubmitButton.addEventListener("click", submitCertifyNumber)
    popupCancelButton.addEventListener("click", inputCancel)
        

    // 이메일 입력 후 전달받을 DB liveAuth 테이블의 id 번호
    // 인증번호를 확인할때 DB 테이블 데이터를 찾는 id 로 사용된다
    let authId

    // 이메일 인증을 위해 입력할때 입력값을 초기화한다
    let userEmail


    // 유저 이메일 입력 팝업창 나타나게하기
    function viewInputPopup() {
        document.querySelector(".popup").style.display = "flex"
    }

    // 입력한 유저 이메일로 인증번호를 보내기위해 서버로 이메일을 전달한다
    // 인증정보가 저장된 DB 테이블의 id 번호를 return 받는다
    function submitEmail() {
        // 입력값이 있을때 데이터가 전달(submit)되도록 한다
        let emailInput = document.querySelector(".input_email").value

        // 추후 사용을 위한 전역변수 초기화
        userEmail = emailInput

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
                .then(data => {
                    // console.log(data)

                    // rerutn 문자열에서 authId 변수 반환하기
                    let array = data.split("\n")
                    let json = JSON.parse(array[1])
                    authId = json.authId

                     // 팝업창 내 인증번호 입력받기위한 input 태그 및 버튼으로 전환
                    toggleInput()
                })
                .catch(error => console.log(error))
                // send_live_certify_email.php 의 이메일 발송 메소드에서 echo 로 리턴되는 메세지로 인해
                // 응답메세지를 json 으로 인식하지 못하는 문제가 있음
                // 이에 응답 response.json 이 아닌 response.text 로 값을 리턴받는다
        } else {
            alert("이메일을 입력해주세요")
        }
    }     
    
    // 입력창 취소
    function inputCancel() {
        document.querySelector(".popup").style.display = "none"
    }

    // 입력창 내에서 이메일 입력했을때, 인증번호를 입력하기위한 창으로 전환
    function toggleInput() {
        document.querySelector(".email").classList.toggle("current")
        document.querySelector(".certify-number").classList.toggle("current")
        
    }

    // 인증번호 입력
    // 유저가 입력한 인증번호가 서버에 저장된 인증번호와 일치한지 확인한다
    // submitEmail 을 통해 return 받은 authId 를 이용해 데이터에 접근한다
    // DB liveAuth 테이블에서 id 값으로 authId 를 가지는 데이터 조회하여 인증번호 일치 여부 확인한다
    function submitCertifyNumber() {
        let certifyNumberInput = document.querySelector(".input_certify_number").value;
        if (certifyNumberInput.length > 0) {

            // 유저가 입력한 인증번호와 authId 를 전달한다
            fetch("check_live_certify_number.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    "certifyNumber" : certifyNumberInput,
                    "authId" : authId
                }),
            })
                .then(response => response.json())
                .then(data => {
                    let result = data.result

                    if (result) {
                        // 토큰발급 받기
                        getDrmLicenseToken()

                        alert('인증완료\n 스트리밍을 시청할 수 있습니다')
                        
                        inputCancel() // 팝업창 종료
                        getAuthState() // 인증 안내메세지 대신, 인증 정보를 보여준다
                    } else {
                        alert('인증번호가 일치하지 않습니다')
                    }
                })
                .catch(error => console.log(error))

        } else {
            alert("인증번호를 입력해주세요")
        }
    }

    // 인증 안내 메세지와 버튼을 제거하고
    // 인증 정보를 보여준다
    function getAuthState() {
        // TODO: 인증 정보로 대체하기
        document.querySelector(".auth-user").classList.toggle("certified")
        document.querySelector(".auth-guide").classList.toggle("certified")

        document.querySelector(".certified-email").innerHTML = userEmail
        document.querySelector(".certified-drmtype").innerHTML = getDrmType()
    }

    // 이메일 인증 완료 후 DRM 라이센스 토큰을 전달받는다
    // 라이센스를 발급하기 위해 필요한 데이터를를 전달해 주어야한다
    function getDrmLicenseToken() {
        // 라이센스토큰
        let licenseToken;

        // 전달할 데이터
        let sendData = { "drmType": getDrmType(), 
            userId: userEmail,
            contentId: "test4",
        }

        // 데이터 전달 후 토큰 받기
        fetch("generate_drm_token.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(sendData),
        })
            .then(response => response.json())
            .then(data => {
                // 토큰 반환받기
                licenseToken = data.licenseToken
                // console.log(licenseToken)

                // DRM 스트리밍 실행
                drmStreaming(licenseToken)
            })
            .catch((error) => console.log(error))
    }

    // 유저의 브라우저를 확인하여, 알맞은 DrmType 반환한다
    function getDrmType() {
        let drmType;

        const agent = navigator.userAgent.toLowerCase()

        if (agent.indexOf("chrome") != -1) {
            // Edge 는 chrome 의 값도 가지고 있기때문에 
            // 해당 위치에서 한번 더 분기해주어야한다
            if (agent.indexOf("edg") != -1) { // egd 키워드로 찾아야한다
                drmType = "PlayReady"
            } else {
                drmType = "Widevine"
            }
        } 
        if (agent.indexOf("firefox") != -1) drmType = "Widevine"
        if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {
            drmType = "PlayReady"
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

        return drmType
    }

    // 라이센스 토큰을 이용해 drm 스트리밍 재생
    function drmStreaming(licenseToken) {

        // 플레이에어서 라이센스 DRM 연동하기!
        let player = videojs("video");

        player.ready(function(){
                player.src({
                    'src': '/video/project/output/dash/stream.mpd',
                    'type': 'application/dash+xml',
                    'keySystemOptions': [
                        {
                            'name': 'com.widevine.alpha',
                            'options':{
                                'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                                'httpRequestHeaders' : {
                                    'pallycon-customdata-v2' : licenseToken,
                                }
                            }
                        },
                        {
                            'name': 'com.microsoft.playready',
                            'options':{
                                'serverURL' : 'https://license.pallycon.com/ri/licenseManager.do',
                                'httpRequestHeaders' : {
                                    'pallycon-customdata-v2' : licenseToken,
                                }
                            }
                        }
                    ]
                });
        })
        player.play();
    }
}