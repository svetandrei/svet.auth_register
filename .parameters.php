<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    'PARAMETERS' => array(
        'ID_MODAL_PHONE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID модального окна регистрации/входа по телефону',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'modal-login-phone'
        ),
        'ID_MODAL_EMAIL' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID модального окна входа по e-mail',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'modal-login-email'
        ),
        'ID_MODAL_REGISTER' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID модального окна обычной регистрации',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'modal-login-register'
        ),
        'ID_MODAL_REMIND' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID модального окна восстановления пароля',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'modal-remindPass'
        ),
        'LINK_PRIVACY' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Ссылка на политику конфиденциальности',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '/privacy/'
        ),
    )
);
?>
