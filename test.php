<div class="header__nav-popup" id="header__nav-popup">
    <div class="header__nav-popup-window">
        <div class="select header__nav-select">
            <label class="select__label header__nav-label" for=""><?= \Yii::t('trans', 'form-country'); ?></label>
            <div class="select__inner header__nav-select-inner">
                <p class="select__text header__nav-select-text countryMenuTitle"></p>
                <select class="select__input header__nav-select-input countryMenu" name="" >
                </select>
            </div>

        </div>
        <div class="select header__nav-select">
            <label class="select__label header__nav-label" for=""><?= \Yii::t('trans', 'form-language'); ?></label>
            <div class="select__inner header__nav-select-inner">
                <?= app\components\widgets\LanguageSelector\LanguageSelectorWidget::widget() ?>
            </div>

        </div>
        <div class="select header__nav-select">
            <label class="select__label header__nav-label" for=""><?= \Yii::t('trans', 'form-currency'); ?></label>
            <div class="select__inner header__nav-select-inner">
                <p class="select__text header__nav-select-text currencyMenuTitle"></p>
                <select class="select__input header__nav-select-input currencyMenu" name="" >
                </select>
            </div>

        </div>
        <input type="submit" value="Apply" class="btn btn_blue header__nav-popup-btn">
    </div>
</div>
