StAuthRegister = {
    init: function (params) {
        this.params = params;
        this.formPhone = $('#' + this.params.ID_MODAL_PHONE).find('form');
        this.formRegister = $('#' + this.params.ID_MODAL_REGISTER).find('form');
        this.formEmailAuth = $('#' + this.params.ID_MODAL_EMAIL).find('form');
        this.formForgotPwd = $('#' + this.params.ID_MODAL_REMIND).find('form');
        this.event();
    },
    event: function () {
        this.formForgotPwd.off('submit');
        this.formForgotPwd.submit((e) => {
            e.preventDefault();
            BX.ajax({
                method: this.formForgotPwd.attr('method'),
                url: this.formForgotPwd.attr('action'),
                data: this.formForgotPwd.serialize(),
                onsuccess: function () {
                    $('.popup').removeClass('active');
                    $('#modal-forgot-confirm').addClass('active');
                }
            })
        })
        this.formRegister.off('submit');
        this.formRegister.submit((e) => {
            e.preventDefault();
            let request = BX.ajax.runComponentAction('st:auth-registration-form', 'register', {
                mode: 'ajax',
                data: {
                    data: this.formRegister.serialize()
                },
            });
            request.then((response) => {
                if(response.data.success) {
                    $('.popup').removeClass('active');
                    $('#modal-confirm').addClass('active');
                } else {
                    this.formRegister.addClass('error-register-form');
                    this.formRegister.find('.error-register-form-info').html(response.data.error)
                }
            });
        });

        // Обработка формы ввода номера телефона
        this.formPhone.off('submit');
        this.formPhone.submit((e) => {
            e.preventDefault();
            this.getCode();
        });

        $('#modal-code').find('.code-repeat').off('click');
        $('#modal-code').find('.code-repeat').click((e) => {
            e.preventDefault();
            this.getCode();
        });

        // Обработка формы введения кода подтверждения
        $('#ST_PHONE_CODE').off('keyup');
        $('#ST_PHONE_CODE').keyup(() => {
            if ($('#ST_PHONE_CODE').val().length == 6) {
                $('#ST_PHONE_CODE').parents('.form__control').addClass('loading-code');
                $('#ST_PHONE_CODE').attr('readonly', true);
                let request = BX.ajax.runComponentAction('st:auth-registration-form', 'checkCode', {
                    mode: 'ajax',
                    data: {
                        data: {
                            'ST_PHONE_CODE': $('#ST_PHONE_CODE').val(),
                            'ST_REGISTER_AUTH_PHONE': $('#ST_REGISTER_AUTH_PHONE').val()
                        }
                    },
                });
                request.then((response) => {
                    if(response.data.success) {
                        location.reload();
                    } else {
                        $('#ST_PHONE_CODE').parents('.form__control').removeClass('loading-code');
                        $('#ST_PHONE_CODE').attr('readonly', false);
                        $('#ST_PHONE_CODE').parents('.form__control').addClass('error-code');
                    }
                });
            } else if ($('#ST_PHONE_CODE').val().length > 0) {
                $('#ST_PHONE_CODE').parents('.form__control').removeClass('error-code');
            }
        });

        // Обработка формы входа по e-mail
        this.formEmailAuth.off('submit');
        this.formEmailAuth.submit((e) => {
            e.preventDefault();
            let request = BX.ajax.runComponentAction('st:auth-registration-form', 'authEmail', {
                mode: 'ajax',
                data: {
                    data: this.formEmailAuth.serialize()
                },
            });
            request.then((response) => {
                if(response.data === true) {
                    location.reload();
                } else {
                    this.formEmailAuth.find('.form__control').addClass('error-email-pass');
                }
            });
        });
    },

    timerCode: function (second) {
        let timerSecond = second;
        let timerCodeInterval = setInterval(() => {
            $('#modal-code').find('.code-timer').html('Отправить код повторно через 0:' + (timerSecond < 10 ? '0' + timerSecond : timerSecond));
            if(timerSecond < 1) {
                clearInterval(timerCodeInterval);
                $('#modal-code').find('.code-repeat').show();
                $('#modal-code').find('.code-timer').hide();
            }
            timerSecond -= 1;
        }, 1000)
    },
    getCode: function () {

        let formPhone = this.formPhone;
        console.log(formPhone.serialize())
        let request = BX.ajax.runComponentAction('st:auth-registration-form', 'getCode', {
            mode: 'ajax',
            data: {
                data: formPhone.serialize()
            },
        });
        request.then((response) => {
            console.log(response)
            if(response.data.success) {
                $('#modal-code-phone').text(response.data.phone);
                $('.popup').removeClass('active')
                $('#modal-code').addClass('active');
                $('#modal-code').find('.code-repeat').hide();
                $('#modal-code').find('.code-timer').show();
                this.timerCode(30);
            } else {
                console.log(response)
            }
        }).catch((err) => {
            console.log(err)
        });
    },
}