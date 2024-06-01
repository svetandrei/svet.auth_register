<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var StAuthRegisterClass $component
 * @var string $templateFolder
 */
$this->addExternalJs($templateFolder.'/script.js');
?>

<!-- modal login phone -->
<div class="popup " id="<?=$arParams['ID_MODAL_PHONE']?>">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Войти или зарегистрироваться</span>
            </div>
            <div class="popup__main popup__main-borRad">
                <div class="loginSwitcher">
                    <div class="loginSwitcher__item active" data-modal-open="<?=$arParams['ID_MODAL_PHONE']?>">
                        По телефону
                    </div>
                    <div class="loginSwitcher__item" data-modal-open="<?=$arParams['ID_MODAL_EMAIL']?>">
                        По e-mail
                    </div>
                </div>
                <form method="POST">
                    <div class="form__control">
                        <input class="input req input-phone" type="text" id="ST_REGISTER_AUTH_PHONE" name="ST_REGISTER_AUTH_PHONE" required>
                        <label for="ST_REGISTER_AUTH_PHONE" class="label">
                            Телефон
                        </label>
                    </div>
                    <div class="form__policy">
                        <label class="check">
                            <input class="check__input" type="checkbox" checked required style="display: block">
                            <span class="check__square"></span>
                        </label>
                        Соглашаюсь с <a href="<?=$arParams['LINK_PRIVACY']?>" target="_blank">Политикой конфиденциальности.</a>
                    </div>
                    <button class="btn btn-green btn-fullWidth" type="submit">
                        Получить код
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal login email -->
<div class="popup" id="<?=$arParams['ID_MODAL_EMAIL']?>">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Войти</span>
            </div>
            <div class="popup__main">
                <div class="loginSwitcher">
                    <div class="loginSwitcher__item" data-modal-open="<?=$arParams['ID_MODAL_PHONE']?>">
                        По телефону
                    </div>
                    <div class="loginSwitcher__item active" data-modal-open="<?=$arParams['ID_MODAL_EMAIL']?>">
                        По e-mail
                    </div>
                </div>
                <form method="POST">
                    <div class="form__control form__control-mb">
                        <input class="input req" type="text" id="ST_AUTH_EMAIL" name="ST_AUTH_EMAIL">
                        <label for="ST_AUTH_EMAIL" class="label">
                            E-mail
                        </label>
                    </div>
                    <div class="form__control form__control-pass">
                        <input class="input req" type="password" id="ST_AUTH_PASSWORD" name="ST_AUTH_PASSWORD">
                        <label for="ST_AUTH_PASSWORD" class="label">
                            Пароль
                        </label>
                        <button class="viewPass"></button>
                        <span class="error-email-info">Неверный логин или пароль</span>
                    </div>
                    <div class="form__policy">
                        <label class="check">
                            <input class="check__input" type="checkbox" checked required style="display: block">
                            <span class="check__square"></span>
                        </label>
                        Соглашаюсь с <a href="<?=$arParams['LINK_PRIVACY']?>" target="_blank">Политикой конфиденциальности.</a>
                    </div>
                    <button class="btn btn-green btn-fullWidth" type="submit">
                        Войти
                    </button>
                </form>
            </div>
            <div class="popup__bottom">
                <div class="popup__bottom__line popup__bottom__line-mb">
                    Нет аккаунта? <span data-modal-open="<?=$arParams['ID_MODAL_REGISTER']?>">Зарегистрироваться</span>
                </div>
                <div class="popup__bottom__line">
                    <span data-modal-open="modal-remindPass">Забыли пароль?</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal code -->
<div class="popup" id="modal-code">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Введите код</span>
            </div>
            <div class="popup__main popup__main-pt">
                <p class="popup__text">
                    Мы отправили код подтверждения на номер <b id="modal-code-phone"></b>
                </p>
                <span class="popup__action" data-modal-open="<?=$arParams['ID_MODAL_PHONE']?>">Изменить</span>
                <form>
                    <div class="form__control form__control-mt">
                        <input class="input req" type="text" id="ST_PHONE_CODE" name="ST_PHONE_CODE">
                        <label for="ST_PHONE_CODE" class="label">
                            Код
                        </label>
                        <span class="error-code-info">Неверный код</span>
                    </div>
                </form>
                <div class="code-block overtime">
                    <span class="popup__action code-repeat"></span>
                    <span class="code-timer">Отправить код повторно через</span>
                </div>
            </div>
            <div class="popup__bottom">
                <div class="popup__bottom__line popup__bottom__line-mb">
                    <span data-modal-open="<?=$arParams['ID_MODAL_EMAIL']?>">Войти по e-mail</span>
                </div>
                <div class="popup__bottom__line hint">
                    <span class="hint__open">Нужна помощь</span>
                    <div class="hint__hidden hint__hidden-help">
                        <button class="close hint__hidden-close"></button>
                        <p>
                            Если у вас возникли трудности при входе, пожалуйста, позвоните по телефону <a
                                href="tel:84957762188">8(495) 776 21 88</a> или
                            <a href="tel:89168702031">8 (916) 870 20 31</a>. Мы поможем!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal register -->
<div class="popup" id="<?=$arParams['ID_MODAL_REGISTER']?>">
    <div class="popup__overlay">
        <div class="popup__body popup__body-lg popup__body-pb">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Регистрация</span>
            </div>
            <div class="popup__main popup__main-borRad">
                <form>
                    <div class="form__row form__row-mb">
                        <div class="form__control">
                            <input class="input req" type="text" id="ST_REGISTER_NAME" name="ST_REGISTER_NAME">
                            <label for="ST_REGISTER_NAME" class="label">
                                Имя <span>*</span>
                            </label>
                        </div>
                        <div class="form__control">
                            <input class="input req" type="text" id="ST_REGISTER_LASTNAME" name="ST_REGISTER_LASTNAME">
                            <label for="ST_REGISTER_LASTNAME" class="label">
                                Фамилия <span>*</span>
                            </label>
                        </div>
                    </div>
                    <div class="form__row form__row-mb">
                        <div class="form__control">
                            <input class="input req input-phone" type="text" id="ST_REGISTER_PHONE" name="ST_REGISTER_PHONE">
                            <label for="ST_REGISTER_PHONE" class="label">
                                Телефон <span>*</span>
                            </label>
                        </div>
                        <div class="form__control">
                            <input class="input req" type="text" id="ST_REGISTER_EMAIL" name="ST_REGISTER_EMAIL">
                            <label for="ST_REGISTER_EMAIL" class="label">
                                E-mail <span>*</span>
                            </label>
                        </div>
                    </div>
                    <div class="form__row">
                        <div class="form__control form__control-pass">
                            <input class="input req" type="password" id="ST_REGISTER_PASSWORD" name="ST_REGISTER_PASSWORD">
                            <label for="ST_REGISTER_PASSWORD" class="label">
                                Пароль <span>*</span>
                            </label>
                            <button class="viewPass"></button>
                        </div>
                        <div class="form__control form__control-pass">
                            <input class="input req" type="password" id="ST_REGISTER_PASSWORD_CONFIRM" name="ST_REGISTER_PASSWORD_CONFIRM">
                            <label for="ST_REGISTER_PASSWORD_CONFIRM" class="label">
                                Повторите пароль <span>*</span>
                            </label>
                            <button class="viewPass"></button>
                        </div>
                    </div>
                    <div class="form__policy">
                        <label class="check">
                            <input class="check__input" type="checkbox" checked required style="display: block">
                            <span class="check__square"></span>
                        </label>
                        Соглашаюсь с <a href="<?=$arParams['LINK_PRIVACY']?>" target="_blank">Политикой конфиденциальности.</a>
                    </div>
                    <div class="error-register-form-info"></div>
                    <div class="popup__bottom-mobile">
                        <button class="btn btn-green popup__btn-register" type="submit">
                            Зарегистрироваться
                        </button>
                    </div>
                </form>
                <div class="popup__bottom">
                    <div class="popup__bottom__line">
                        <span data-modal-open="<?=$arParams['ID_MODAL_EMAIL']?>">У меня уже есть аккаунт</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal confirm -->
<div class="popup" id="modal-confirm">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Подтверждение регистрации</span>
            </div>
            <div class="popup__main popup__main-pt popup__main-borRad">
                <p class="popup__text">
                    Для подтверждения регистрации на указанный вами адрес электронной почты было выслано письмо с подробной
                    инструкцией
                </p>
                <button class="btn btn-green btn-fullWidth popup__btn-ok popup__btn-close">
                    ОК
                </button>
            </div>
        </div>
    </div>
</div>

<!-- modal forgot password confirm -->
<div class="popup" id="modal-forgot-confirm">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Восстановление пароля</span>
            </div>
            <div class="popup__main popup__main-pt popup__main-borRad">
                <p class="popup__text">
                    Контрольная строка, а также ваши регистрационные данные были высланы на email. Пожалуйста, дождитесь письма, так как контрольная строка изменяется при каждом запросе.
                </p>
                <button class="btn btn-green btn-fullWidth popup__btn-ok popup__btn-close">
                    ОК
                </button>
            </div>
        </div>
    </div>
</div>

<!-- modal remind pass -->
<div class="popup" id="<?=$arParams['ID_MODAL_REMIND']?>">
    <div class="popup__overlay">
        <div class="popup__body popup__body-sm">
            <button class="close popup__close"></button>
            <div class="popup__head">
                <span>Напоминание пароля</span>
            </div>
            <div class="popup__main popup__main-pt popup__main-borRad">
                <p class="popup__text">
                    На указанный адрес будет отправлено сообщение с восстановленным паролем
                </p>
                <form method="POST" action="<?=POST_FORM_ACTION_URI?>?forgot_password=yes">
                    <input type="hidden" name="TYPE" value="SEND_PWD">
                    <input type="hidden" name="AUTH_FORM" value="Y">
                    <div class="form__control form__control-mt">
                        <input class="input req" type="text" id="USER_EMAIL" name="USER_EMAIL">
                        <label for="USER_EMAIL" class="label">
                            E-mail
                        </label>
                    </div>
                    <button class="btn btn-green btn-fullWidthMt">
                        Получить пароль
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    StAuthRegister.init(<?=json_encode($arParams)?>);
</script>