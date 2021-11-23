
<div id="gui-content">

    <h2 class="sub-tit">
        Shell에서 설치하기
        <em>Shell 접속을 통해 간편하게 원격으로 부터 다운로드 및 설치할 수 있습니다.</em>
    </h2>

    <div class="inner">


        <div class="gui-warbox">
            <strong><i class="fas fa-exclamation-circle"></i> 확인해 주세요</strong>
            <p>이 방법을 사용하기 위해선 소유하고 있는 웹계정(웹호스팅)에서 SSH를 지원해야 합니다.</p>
        </div>

        <h4 class="ctit1">Zigger 를 다운로드합니다.</h4>

        <span class="ctxt1">
            Shell로 Zigger를 설치하고자 하는 계정에 접속한 뒤 <strong>최상위 Root</strong> 경로로 이동하여 다운로드를 준비합니다. <br />
            다운로드할 준비가 되었다면, Zigger 최신버전을 아래 명령어를 통해 원격지로부터 자신의 계정으로 다운로드합니다.
        </span>

<div class="nostyle gui-tagwrap">
<pre data-enlighter-language="shell" data-enlighter-highlight="" data-enlighter-linenumbers="" >
#wget https://cdn.zigger.net/download/zigger-source.tar.gz
</pre>
</div>

        <span class="ctxt1">
            그런 다음, 다운로드 받은 파일은 아래 명령어로 압축을 해제 합니다.
        </span>

<div class="nostyle gui-tagwrap">
<pre data-enlighter-language="shell" data-enlighter-highlight="" data-enlighter-linenumbers="" >
#tar xvf Zigger-source.tar.gz
</pre>
</div>

        <div class="gui-warbox bg">
            <strong><i class="fas fa-exclamation-circle"></i> 확인해 주세요</strong>
            <p>
                원격지로부터 다운로드 받은 압축 파일은 최상위 Root에 압축을 해제하여 설치할 것을 권장합니다. <br />
                하위 디렉토리 내부에 압축을 해제하여 설치하는 경우 웹사이트 URI가 불필요하게 길어지게 됩니다.
            </p>
        </div>

        <h4 class="ctit1">설치를 시작합니다.</h4>

        <span class="ctxt1">
            압축 해제가 정상적으로 완료 되었다면, <br />
            웹브라우저에서 자신의 웹사이트로 접속합니다. <br /><br />

            <strong>Zigger Installer</strong>의 안내에 따라 설치를 진행하면 Zigger를 손쉽게 설치할 수 있습니다.
        </span>




<!-- <div class="nostyle gui-tagwrap">
<span class="filename">
    <strong>Controller</strong>
    /app/test.php
</span>
<pre data-enlighter-language="php" data-enlighter-highlight="11-18">
&lt;?php
class Testpage extends \Controller\Make_Controller{

    public function _init(){
        $this->layout()->head();
        $this->_make();
        $this->load_tpl(PH_THEME_PATH.'/html/testpage.php');
        $this->layout()->foot();
    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('type','static');
        $form->set('action',PH_DIR.'/member/signup2');
        $form->set('method','POST');
        $form->set('target','view');
        $form->run();
    }

    public function _make(){

    }

}
</pre>
</div> -->

        <!-- <div class="gui-linkbox">
            <strong>연관 가이드 바로가기</strong>
            <a href="#"></a>
        </div> -->

    </div>

</div>
