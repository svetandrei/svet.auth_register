<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Sms\Event;

class StAuthRegisterAjax extends Controller {
    public function configureActions() {
        $prefilters = array(
            new Csrf(),
        );
        return array(
            'getCode' => array(
                'prefilters' => $prefilters,
            ),
            'checkCode' => array(
                'prefilters' => $prefilters,
            ),
            'authEmail' => array(
                'prefilters' => $prefilters,
            ),
            'register' => array(
                'prefilters' => $prefilters,
            )
        );
    }

    public function getCodeAction($data) {
        parse_str($data, $result);
        // Создаем 4-х значный код
        // Нормализуем номер телефона для отправки по SMS
        $phone = Main\UserPhoneAuthTable::normalizePhoneNumber($result['ST_REGISTER_AUTH_PHONE']);

        // Проверяем есть ли пользователь с таким номером телефона
        $user = Main\UserPhoneAuthTable::getList(array(
            'filter'=>array('PHONE_NUMBER' =>$phone)
        ))->fetch();

        if($user){
            // Если есть то генерируем код подтверждения
            list($code, $phoneNumber) = \CUser::GeneratePhoneCode($user['USER_ID']);

        } else {
            // Если такого пользователя нет, то создаем и генерируем код подтверждения
            $newUser = new CUser;

            $arFields = Array(
                "LOGIN" => $phone,
                "LID" => SITE_ID,
                "ACTIVE" => "Y",
                "GROUP_ID" => array(),
                "PHONE_NUMBER" => $phone,
                "PERSONAL_PHONE" => $phone
            );

            // Берем группы пользователя из настроек главного модуля
            $def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
            if($def_group != "")
                $arFields["GROUP_ID"] = explode(",", $def_group);

            // Генерируем пароль пользователю
            $password = randString(8);

            $arFields["PASSWORD"] = $password;
            $arFields["CONFIRM_PASSWORD"] = $password;
            $bOk = true;
            $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
            foreach($events as $arEvent)
            {
                if(ExecuteModuleEventEx($arEvent, array(&$arFields)) === false)
                {
                    $bOk = false;
                    break;
                }
            }
            if($bOk) {
                $ID = $newUser->Add($arFields);
            }
            if (intval($ID) > 0)
            {
                list($code, $phoneNumber) = \CUser::GeneratePhoneCode($ID);
                $events = GetModuleEvents("main", "OnAfterUserRegister", true);
                foreach ($events as $arEvent)
                    ExecuteModuleEventEx($arEvent, array(&$arFields));

                $arFields["USER_ID"] = $ID;

                $arEventFields = $arFields;
                unset($arEventFields["PASSWORD"]);
                unset($arEventFields["CONFIRM_PASSWORD"]);

                $event = new CEvent;
                $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
            } else {
                return array(
                    'error' => $newUser->LAST_ERROR
                );
            }

        }
        if($code && $phoneNumber) {
            // Отправляем СМС по событию
            $sms = new Event(
                "SMS_USER_CONFIRM_NUMBER",
                array(
                    "USER_PHONE" => $phoneNumber,
                    "CODE" => $code,
                )
            );

            $smsResult = $sms->send(true);

            if($smsResult->isSuccess()) {
                // Если смс успешно отправлено, то передаем данные о номере телефоне (для отображения на фронте) и статус успешности
                return array(
                    'phone' => $result['ST_REGISTER_AUTH_PHONE'],
                    'success' => true,
                );
            } else {
                // Иначе возвращаем ошибку
                return array(
                    'error' => $smsResult->getErrorMessages()
                );
            }
        }
        return array(
            'error' => 'Ошибка авторизации/регистрации'
        );
    }
    public function checkCodeAction($data) {
        $phone = Main\UserPhoneAuthTable::normalizePhoneNumber($data['ST_REGISTER_AUTH_PHONE']);
        if($userId = \CUser::VerifyPhoneCode($phone, $data['ST_PHONE_CODE'])) {
            /*$user = Main\UserPhoneAuthTable::getList(array(
                'filter'=>array('PHONE_NUMBER' =>$phone)
            ))->fetch();
            global $USER;
            $USER->Authorize($user['USER_ID']);*/
            global $USER;
            $USER->Authorize($userId);
            return array(
                'success' => true,
            );
        } else {
            return array(
                'error' => 'Неверно введен код',
            );
        }
    }
    public function authEmailAction($data) {
        parse_str($data, $result);
        $filter = Array("EMAIL" => $result['ST_AUTH_EMAIL']);
        $rsUsers = CUser::GetList(false, false, $filter)->fetch();
        if($rsUsers) {
            // Пользователь существует, пробуем авторизовать
            global $USER;
            $auth = $USER->Login($rsUsers['LOGIN'], $result['ST_AUTH_PASSWORD'], 'Y');
            return $auth;
        } else {
            // Пользователь не существует, выводим такое же сообщение об ошибке, как при неправильном пароле
            // Здесь делаем фиктивную авторизацию на несуществующий аккаунт для получения такого же сообщения (чтобы нельзя было отследить правильность логина)
            global $USER;
            $auth = $USER->Login('FakeAuthForTheSameMessage', 'FakeAuthForTheSameMessage'.mt_rand());
            return $auth;
        }
    }
    public function registerAction($data) {
        parse_str($data, $result);
        if($result['ST_REGISTER_PASSWORD'] !== $result['ST_REGISTER_PASSWORD_CONFIRM']) {
            return array(
                'error' => 'Пароли не совпадают',
            );
        }

        $phone = Main\UserPhoneAuthTable::normalizePhoneNumber($result['ST_REGISTER_PHONE']);
        $login = explode('@', $result['ST_REGISTER_EMAIL']);
        $login = $login[0].mt_rand(9999, 99999);
        $arFields = Array(
            "LOGIN" => $login,
            "LID" => SITE_ID,
            "ACTIVE" => "N",
            "GROUP_ID" => array(),
            "PHONE_NUMBER" => $phone,
            "PERSONAL_PHONE" => $phone,
            "NAME" => $result['ST_REGISTER_NAME'],
            "LAST_NAME" => $result['ST_REGISTER_LASTNAME'],
            "EMAIL" => $result['ST_REGISTER_EMAIL'],
            "PASSWORD" => $result['ST_REGISTER_PASSWORD'],
            "CONFIRM_PASSWORD" => $result['ST_REGISTER_PASSWORD_CONFIRM'],
            "CONFIRM_CODE" => randString(8),
            "LANGUAGE_ID" => LANGUAGE_ID,
            "USER_IP" => $_SERVER["REMOTE_ADDR"],
            "USER_HOST" => @gethostbyaddr($_SERVER["REMOTE_ADDR"])
        );
        $def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
        if($def_group != "")
            $arFields["GROUP_ID"] = explode(",", $def_group);

        $bOk = true;

        $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
        foreach($events as $arEvent)
        {
            if(ExecuteModuleEventEx($arEvent, array(&$arFields)) === false)
            {
                $bOk = false;
                break;
            }
        }
        $newUser = new CUser;
        if ($bOk)
        {
            $ID = $newUser->Add($arFields);
        } else {
            return array(
                'error' => 'Ошибка регистрации',
            );
        }
        if(intval($ID) > 0) {
            $events = GetModuleEvents("main", "OnAfterUserRegister", true);
            foreach ($events as $arEvent)
                ExecuteModuleEventEx($arEvent, array(&$arFields));

            $arFields["USER_ID"] = $ID;

            $arEventFields = $arFields;
            unset($arEventFields["PASSWORD"]);
            unset($arEventFields["CONFIRM_PASSWORD"]);

            $event = new CEvent;
            $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
            $event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
            return array(
                'success' => true,
            );
        } else {
            return array(
                'error' => str_replace('<br>', ' ', $newUser->LAST_ERROR),
            );
        }
        return false;
    }

}