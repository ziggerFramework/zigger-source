<div id="signin">
    <form <?php echo $this->form(); ?>>

        <h4>회원으로 가입하고 시작하세요.</h4>
        <span class="log-noti">
            이미 회원이신가요? <a href="<?php echo PH_DIR; ?>/sign/signin">지금 바로 회원 로그인</a>
        </span>

        <fieldset class="snsbox">
            <h5>SNS Sign up</h5>
            <ul>
                <li><a id="kakao-login" href="<?php echo PH_PLUGIN_DIR; ?>/snslogin/getlogin.php?get_sns=kakao"><img src="<?php echo PH_THEME_DIR; ?>/layout/images/login-sns-ico-k.jpg">Log in with Kakao</a></li>
                <li><a id="naver-login" href="<?php echo PH_PLUGIN_DIR; ?>/snslogin/getlogin.php?get_sns=naver"><img src="<?php echo PH_THEME_DIR; ?>/layout/images/login-sns-ico-n.jpg">Log in with Naver</a></li>
            </ul>
        </fieldset>

        <p class="or">OR</p>

        <fieldset class="inp-wrap">

            <label for="id"><em>*</em> User ID</label>
            <input type="text" name="id" id="id" title="User ID" class="inp"data-validt-action="/sign/Signup-check-id" data-validt-event="keyup" data-validt-group="id" />
            <span class="validt" data-validt-group="id"></span>
            <span class="tbltxt">
                · 영어, 숫자 조합으로 입력<br />
                · 최소 5자~최대 30자 까지 입력
            </span>

            <label for="email"><em>*</em> E-mail</label>
            <input type="text" name="email" id="email" title="E-mail" class="inp" data-validt-action="/sign/Signup-check-email" data-validt-event="keyup" data-validt-group="email" />
            <span class="validt" data-validt-group="email"></span>
            <span class="tbltxt">
                · 회원 로그인 정보 분실시 입력한 이메일로 조회 가능
            </span>

            <label for="pwd"><em>*</em> Password</label>
            <input type="password" name="pwd" id="pwd" title="Password" class="inp mb5" data-validt-action="/sign/Signup-check-password" data-validt-event="keyup" data-validt-group="pwd" />
            <span class="validt" data-validt-group="pwd"></span>
            <span class="tbltxt">
                · 최소 5자~최대 30자 까지 입력
            </span>

            <label for="pwd2"><em>*</em> Password 확인</label>
            <input type="password" name="pwd2" id="pwd2" title="Password 확인" class="inp" />

            <label for="name"><em>*</em> 사용자 이름</label>
            <input type="text" name="name" id="name" title="사용자 이름" class="inp" />

            <label for="gender"><em>*</em> 성별</label>
            <div class="labelWrap">
                <label><input type="radio" name="gender" id="gender" title="남자" value="M" checked />남자</label>
                <label><input type="radio" name="gender" id="gender" title="여자" value="F" />여자</label>
            </div>

            <label for="gender">휴대전화</label>
            <input type="text" name="phone" id="phone" title="휴대전화" class="inp w100" />
            <span class="tbltxt">
                · 하이픈(-) 없이 숫자만 입력
            </span>

            <label for="telephone">집전화</label>
            <input type="text" name="telephone" id="telephone" title="집전화" class="inp w100" />
            <span class="tbltxt">
                · 하이픈(-) 없이 숫자만 입력
            </span>

            <label class="tar mb15">
                <input type="checkbox" name="policy" value="checked" />
                회원가입을 위해 <a href="<?php echo PH_DIR; ?>/doc/terms-of-service" class="forgot" target="_blank">서비스이용약관</a> 및 <a href="<?php echo PH_DIR; ?>/doc/privacy-policy" class="forgot" target="_blank">개인정보처리방침</a> 에 동의합니다.
            </label>

            <button type="submit" class="btn1 w100p mt10">회원가입</button>
        </fieldset>

    </form>
</div>
