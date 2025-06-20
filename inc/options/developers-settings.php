<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

// developers general options

if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
        'key' => 'group_5f563f5302920',
        'title' => 'Настройки разработчика',
        'fields' => array(
            array(
                'key' => 'field_welcome4d78f',
                'label' => '<span class="dashicons dashicons-admin-home"></span> Главная',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 1,
            ),
            array(
                'key' => 'field_welcometext',
                'label' => 'Как работать с темой',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '
                <p>В теме предусмотрено все для быстрого старта и удобной натяжки. Обязательно к прочтению.</p>
                <ol>
                    <li>Функционал темы не использует jQuery. При использовании jQuery-зависимых библиотек его можно активировать во вкладке <b>Срипты/стили</b></li>
                    <li>Стили в теме разделены на <b>critical.css</b> и <b>style.css</b>. В critical.css находится нормалайз и базовые стили темы, все остальное в style.css. Во вкладке SEO можно включить сжатую версию основного файла стилей style.min.css (предварительно закинуть туда сжатые стили из style.css).</li>
                    <li></li>
                    <li>Компоненты для быстрого старта находятся в папке <code>template-parts</code>. Доступны готовые контактные формы, FAQ, модальные окна, последние новости и многое другое.</li>
                </ol>
                <blockquote>Любые новые функции или  прочий новый ф-ционал вносим в <code>/inc/theme-custom.php</code>. <br/>По завершении работы с темой убираем константу LP_THEME с wp-config.php, чтобы скрыть настройки разработчика.</blockquote>
                ',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_cache5e38',
                'label' => 'Кеширование стилей и скриптов',
                'name' => 'enable_cache',
                'type' => 'true_false',
                'instructions' => 'Если включено, то клиенту не нужно будет чистить у себя кеш при любом обновлении основного файла стилей или скриптов.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Выключить при сдаче проекта',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'вкл',
                'ui_off_text' => 'выкл',
            ),

            array(
                'key' => 'field_bjkb26674d78f',
                'label' => '<span class="dashicons dashicons-admin-settings"></span> Поля настроек',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'field_seetti34634b3',
                'label' => 'Поля настроек',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Поля, которые видит клиент в своих настройках',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_phone1dsddjl34',
                'label' => 'Телефон 1',
                'name' => 'enable_phone_1',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>phone_1</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),

            array(
                'key' => 'field_phone2dsddjl34',
                'label' => 'Телефон 2',
                'name' => 'enable_phone_2',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>phone_2</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_phone3dsddjl34',
                'label' => 'Телефон 3',
                'name' => 'enable_phone_3',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>phone_3</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_phone4dsddjl34',
                'label' => 'Телефон 4',
                'name' => 'enable_phone_4',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>phone_4</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_emaildsddjl34',
                'label' => 'Email',
                'name' => 'enable_email',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>email</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_addressdsddjl34',
                'label' => 'Адрес',
                'name' => 'enable_address',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>address</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_workinghoursdsddjl34',
                'label' => 'График работ',
                'name' => 'enable_working_hours',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>working_hours</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_social3463353',
                'label' => 'Соц. сети',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_facebookdnvkj345',
                'label' => '<span class="dashicons dashicons-facebook-alt"></span> Facebook',
                'name' => 'enable_facebook',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>facebook_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_twitterdnvkj345',
                'label' => '<span class="dashicons dashicons-twitter"></span> Twitter',
                'name' => 'enable_twitter',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>twitter_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_telegramdnvkj345',
                'label' => 'Telegram',
                'name' => 'enable_telegram',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>telegram_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_viberdnvkj345',
                'label' => '<span class="dashicons dashicons-whatsapp"></span> Viber',
                'name' => 'enable_viber',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>viber_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_instagramdnvkj345',
                'label' => '<span class="dashicons dashicons-instagram"></span> Instagram',
                'name' => 'enable_instagram',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>instagram_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_vkdnvkj345',
                'label' => 'VK',
                'name' => 'enable_vk',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>vk_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_linkedindnvkj345',
                'label' => '<span class="dashicons dashicons-linkedin"></span> Linkedin',
                'name' => 'enable_linkedin',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>linkedin_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),
            array(
                'key' => 'field_skypednvkj345',
                'label' => 'Skype',
                'name' => 'enable_skype',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<b>skype_link</b>',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Показано',
                'ui_off_text' => 'Скрыто',
            ),

            array(
                'key' => 'field_5f51e83b4d78f',
                'label' => '<span class="dashicons dashicons-editor-code"></span> Cкрипты/Cтили',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'field_5f71c5e6334b3',
                'label' => 'Скрипты и прочие библиотеки',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_as66940b800e38',
                'label' => 'jQuery',
                'name' => 'enable_jquery',
                'type' => 'true_false',
                'instructions' => 'Включить, если используются зависимости',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_restdsd53e8',
                'label' => 'REST API',
                'name' => 'enable_rest_api',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_heartsd53e8',
                'label' => 'Heartbeat API',
                'name' => 'enable_heartbeat_api',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_9hkjkn78f',
                'label' => '<span class="dashicons dashicons-editor-spellcheck"></span> Шрифты',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'fieldnlknl_4b3',
                'label' => 'Шрифты',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_njkbkkfonts',
                'label' => 'Предзагрузка шрифта',
                'name' => 'fonts_preload',
                'type' => 'true_false',
                'instructions' => 'Включить предзагрузку основного шрифта сайта.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_63ac974400bbc',
                'label' => 'Список шрифтов',
                'name' => 'fonts_preload_list',
                'aria-label' => '',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_njkbkkfonts',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'table',
                'pagination' => 0,
                'min' => 0,
                'max' => 0,
                'collapsed' => '',
                'button_label' => 'Добавить шрифт',
                'rows_per_page' => 10,
                'sub_fields' => array(
                    array(
                        'key' => 'field_63ac975900bbd',
                        'label' => 'Название шрифта',
                        'name' => 'font_name',
                        'aria-label' => '',
                        'type' => 'text',
                        'instructions' => 'Пример: <b>NoahHead-Regular</b>',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'maxlength' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '.woff2',
                        'parent_repeater' => 'field_63ac974400bbc',
                    ),
                ),
            ),

            array(
                'key' => 'field_adh34d78f',
                'label' => '<span class="dashicons dashicons-admin-plugins"></span> Компоненты',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'field_sdg36e6334b3',
                'label' => 'Компоненты',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_sasdvdd800e8',
                'label' => 'Custom Post types',
                'name' => 'enable_post_types',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_smooodgb8e38',
                'label' => 'Smooth Scrollbar',
                'name' => 'smooth_scrollbar',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_sgsap38sdvnlk',
                'label' => 'GSAP',
                'name' => 'gsap_enable',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_lights3hj560',
                'label' => 'Lightbox js',
                'name' => 'lightbox_enable',
                'type' => 'true_false',
                'instructions' => 'Документация <a href="//fslightbox.com/javascript/documentation/how-to-use#through-javascript" target="_blank">здесь</a>',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Для галерей',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_sdvjbktestim38',
                'label' => 'Отзывы',
                'name' => 'enable_testimonials',
                'type' => 'true_false',
                'instructions' => 'Тип записи в админке, форма на фронте. Система модерации.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),
            array(
                'key' => 'field_subscriptonsaas',
                'label' => 'Новостная рассылка',
                'name' => 'enable_subscriptions',
                'type' => 'true_false',
                'instructions' => 'Тип записи в админке, форма на фронте. Уведомления на почту после выхода новости.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),
            array(
                'key' => 'field_ssdswe34445im38',
                'label' => 'Slider (splide.js)',
                'name' => 'enable_slider',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_5f5640b800e38',
                'label' => 'Прелоадер',
                'name' => 'preloader',
                'type' => 'true_false',
                'instructions' => 'Также может отображаться при ajax-событиях',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_5f6d9a9420e60',
                'label' => 'Хлебные крошки',
                'name' => 'breadcrumbs',
                'type' => 'true_false',
                'instructions' => 'Показать или скрыть Хлебные крошки',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_maps800e38',
                'label' => 'Google Maps',
                'name' => 'enable_maps',
                'type' => 'true_false',
                'instructions' => 'Также будет инициирован скрипт карт (map.js) в шаблоне контактов',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_5f567a1cdfb5d',
                'label' => 'Google map API key',
                'name' => 'google_map_api_key',
                'type' => 'text',
                'instructions' => 'Получить API key можно <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">здесь</a>',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_maps800e38',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'AIzaSyBBU_zpkBrGVM46XQG3dUTz87S2Ig8wNvk',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),

            array(
                'key' => 'field_5f51343b0d78f',
                'label' => '<span class="dashicons dashicons-admin-users"></span> Админка',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'field_5f71c5d6334b3',
                'label' => 'Dashboard панели',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_dev1283ad8dc316',
                'label' => 'Инфопанель о разработчике',
                'name' => 'developer_dashboard',
                'type' => 'true_false',
                'instructions' => 'информация о разработчике на стартовой старнице дашборда',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),
            array(
                'key' => 'field_sefd4283ad8dc316',
                'label' => 'Статус-панель',
                'name' => 'status_dashboard',
                'type' => 'true_false',
                'instructions' => 'статус-панель на стартовой странице консоли',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_wegf335d6334b3',
                'label' => 'Отображение пунктов меню админки',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_1f6d8a9420e60',
                'label' => 'Новости/Блог',
                'name' => 'news_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),
            array(
                'key' => 'field_1f6d9a9420e37',
                'label' => 'Комментарии',
                'name' => 'comments_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),
            array(
                'key' => 'field_1f6d9a9420e64',
                'label' => 'Пользователи',
                'name' => 'users_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),
            array(
                'key' => 'field_1f6d9a9420e89',
                'label' => 'Плагины',
                'name' => 'plugins_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),
            array(
                'key' => 'field_1f6d9a9465e40',
                'label' => 'Инструменты',
                'name' => 'tools_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),
            array(
                'key' => 'field_1f6d9a9520e40',
                'label' => 'Настройки',
                'name' => 'options_general_menu_item',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Скрыто',
                'ui_off_text' => 'Показано',
            ),

            array(
                'key' => 'field_forms5f57334564553f',
                'label' => '<span class="dashicons dashicons-email-alt"></span> Контактные формы',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),

            array(
                'key' => 'field_formsadfhnb',
                'label' => 'Доступные формы для вывода на сайте',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '
                  <p><code>[form-contact]</code></p>
                  <p><code>[form-callback]</code></p>
                ',
                'new_lines' => '',
                'esc_html' => 0,
            ),

            array(
                'key' => 'field_sdgdgdgsdg38',
                'label' => 'Заявки в Telegram',
                'name' => 'telegram_request',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_sgsfasf4b5d',
                'label' => 'Token',
                'name' => 'telegram_token',
                'type' => 'text',
                'instructions' => 'Как получить токен и чат id <a href="https://vc.ru/dev/158136-kak-otpravlyat-zayavki-s-lendinga-pryamo-v-telegram" target="_blank" rel="nofollow noopener">описано здесь</a>',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_sdgdgdgsdg38',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_sdgsdhsdb5d',
                'label' => 'Chat_id',
                'name' => 'telegram_chat_id',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_sdgdgdgsdg38',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),

            array(
                'key' => 'field_5f57334564553f',
                'label' => '<span class="dashicons dashicons-search"></span> SEO',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'left',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_1f71sdge6334b3',
                'label' => 'Оптимизация',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'Настройка параметров оптимизации',
                'new_lines' => '',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_5f6c83a8dc557',
                'label' => 'Сжатие HTML',
                'name' => 'minify_html',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),
            array(
                'key' => 'field_sitemap3a34fc557',
                'label' => 'HTML Карта сайта',
                'name' => 'html_sitemap',
                'type' => 'true_false',
                'instructions' => 'После включения страница будет добавлена в админке',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),
            array(
                'key' => 'field_dsgsd35557',
                'label' => 'style.min.css',
                'name' => 'enable_min_css',
                'type' => 'true_false',
                'instructions' => 'Подключить сжатую версию основного файла стилей',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

            array(
                'key' => 'field_6f6c83ad8dc346',
                'label' => 'W3C Validator - убрать ошибки',
                'name' => 'enable_w3c_validator',
                'type' => 'true_false',
                'instructions' => 'Включить если найдены ошибки',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Вкл',
                'ui_off_text' => 'Выкл',
            ),

        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'developers-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

endif;

/* Conditional Logic to display field if an options page field is set */
function lp_conditional_phone_1($field)
{
    if (get_field('enable_phone_1', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_phone_2($field)
{
    if (get_field('enable_phone_2', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_phone_3($field)
{
    if (get_field('enable_phone_3', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_phone_4($field)
{
    if (get_field('enable_phone_4', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_email($field)
{
    if (get_field('enable_email', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_address($field)
{
    if (get_field('enable_address', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_working_hours($field)
{
    if (get_field('enable_working_hours', 'option')) {
        return $field;
    } else {
        return;
    }
}

function lp_conditional_facebook($field)
{
    if (get_field('enable_facebook', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_twitter($field)
{
    if (get_field('enable_twitter', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_telegram($field)
{
    if (get_field('enable_telegram', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_instagram($field)
{
    if (get_field('enable_instagram', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_vk($field)
{
    if (get_field('enable_vk', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_linkedin($field)
{
    if (get_field('enable_linkedin', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_skype($field)
{
    if (get_field('enable_skype', 'option')) {
        return $field;
    } else {
        return;
    }
}
function lp_conditional_viber($field)
{
    if (get_field('enable_viber', 'option')) {
        return $field;
    } else {
        return;
    }
}

add_filter('acf/prepare_field/key=field_5f6dcb4612d7d', 'lp_conditional_phone_1', 20);
add_filter('acf/prepare_field/key=field_5f6dcb7978a2d', 'lp_conditional_phone_2', 20);
add_filter('acf/prepare_field/key=field_5f6dcb7e78a2e', 'lp_conditional_phone_3', 20);
add_filter('acf/prepare_field/key=field_4f6dcb7ssg32e', 'lp_conditional_phone_4', 20);
add_filter('acf/prepare_field/key=field_5f5f277ff38fe', 'lp_conditional_email', 20);
add_filter('acf/prepare_field/key=field_sdgdrcc2fd69235', 'lp_conditional_address', 20);
add_filter('acf/prepare_field/key=field_5f6dcc2fd6904', 'lp_conditional_working_hours', 20);

add_filter('acf/prepare_field/key=field_facebook8fe', 'lp_conditional_facebook', 20);
add_filter('acf/prepare_field/key=field_twitter8fe', 'lp_conditional_twitter', 20);
add_filter('acf/prepare_field/key=field_dasvd2435ff28fe', 'lp_conditional_telegram', 20);
add_filter('acf/prepare_field/key=field_instagram8fe', 'lp_conditional_instagram', 20);
add_filter('acf/prepare_field/key=field_sdgd277ff28fe', 'lp_conditional_vk', 20);
add_filter('acf/prepare_field/key=field_linkedin8fe', 'lp_conditional_linkedin', 20);
add_filter('acf/prepare_field/key=field_skype8fe', 'lp_conditional_skype', 20);
add_filter('acf/prepare_field/key=field_sdbddf5ff28fe', 'lp_conditional_viber', 20);
