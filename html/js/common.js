// 웹페이지 모든 페이지에 걸쳐 공통된 javascript 함수

window.onload = function() {
    
    /** 관리자 버튼 **/
    let managerButton = document.querySelector('#header .nav-manager');
    let managerDropdownMenu = document.querySelector('#header .manager-menu');

    if (managerButton != null) {

        // 상단메뉴바에서 관리자 메뉴를 관리자 계정 로그인시에만 보이도록 설정
        // 관리 버튼 태그의 id 값이 (1일때 : 관리자, 0일때 : 일반회원, 없을때 : 비회원) 으로 설정됨
        if (managerButton.getAttribute('id') == 1) {
            // 관리자일 경우
            managerButton.style.visibility = 'visible';
        } else {
            // 관리자가 아니거나, 로그인상태가 아닐 경우
            managerButton.style.visibility = 'hidden';
        }
    

        // 관리자 로그인 시 보이는 '관리' 드롭다운 메뉴 펼치고 닫기
        managerButton.addEventListener('mouseover', function(){
            managerDropdownMenu.style.display = 'block';
        })

        managerButton.addEventListener('mouseout', function(){
            managerDropdownMenu.style.display = 'none';
        })
    }
    /** 관리자 버튼 **/


    /** 블로그 글쓰기 버튼 **/
    let writePostButton = document.querySelector('.blog.header a');

    if (writePostButton != null) {
        // 블로그 목록에서 글쓰기버튼을 관리자 로그인일 경우에만 보이도록 한다
        if (managerButton.getAttribute('id') == 1) {
            // 관리자일 경우
            writePostButton.style.visibility = 'visible';
        } else {
            // 관리자가 아니거나, 로그인상태가 아닐 경우
            writePostButton.style.visibility = 'hidden';
        }
    }
    /** 블로그 글쓰기 버튼 **/


    /** 블로그 게시글 수정/삭제 **/
    let editPostButton = document.querySelector('.post_button');

    if (editPostButton != null) {
        if (managerButton.getAttribute('id') == 1) {
            // 관리자일 경우
            editPostButton.style.visibility = 'visible';
        } else {
            // 관리자가 아니거나, 로그인상태가 아닐 경우
            editPostButton.style.visibility = 'hidden';
        }
    }
    /** 블로그 게시글 수정/삭제 **/
}