<?php /* Smarty version 2.6.18, created on 2026-03-04 01:10:03
         compiled from bottom_menu.html */ ?>
<!-- 公共底部导航：游戏 / 开奖 / 未结，保持原 class/type 以兼容现有点击事件。可选变量 bottom_menu_active：game|result|report 控制当前高亮 -->
<style type="text/css">
.mxj-bottom-nav {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    min-height: 56px;
    background: #fff;
    border-top: 1px solid #e8e8e8;
    box-shadow: 0 -2px 12px rgba(0,0,0,0.06);
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-around;
    padding: 8px 6px 12px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    z-index: 100;
}
.mxj-bottom-nav a {
    -webkit-box-flex: 1;
    flex: 1;
    max-width: 120px;
    min-height: 44px;
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    color: #132e7b;
    text-decoration: none;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    margin: 0 4px;
    -webkit-tap-highlight-color: transparent;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.mxj-bottom-nav a .mxj-nav-cell {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    width: 100%;
    font-size: 13px;
    line-height: 1.3;
    color: inherit;
}
.mxj-bottom-nav a .mxj-nav-icon {
    width: 28px;
    height: 28px;
    margin-bottom: 4px;
    background: url(/css/mobi/img/icon_step.png) no-repeat;
    background-size: 520px 74px;
    -webkit-box-flex: 0;
    flex-shrink: 0;
}
.mxj-bottom-nav a.betting_shortcut .mxj-nav-icon { background-position: 39px -38px; }
.mxj-bottom-nav a.result_shortcut .mxj-nav-icon { background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA0kAAAJTBAMAAAAvbUA6AAAAFVBMVEVHcEwAl/EAl/EAl/EAl/EAl/EAl/FLFK/gAAAABnRSTlMAFm1L1Z1GdPbvAAAErElEQVR42u3cQUrzUBiG0VyNM0GKOhW7gyJ2KLgJV+DCugI3IK5BHUsRFyAx80rjUAWLfpDc3OJ5cCD8pPjnwPfixFR9tntd/dxqUWnMdrwCSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESQVUewXxji+CD6QbStmbHgUf6Fw8uyRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRK+qXi/z7eJPpA11LK3vw0+MBq4eKJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlDRe9f/5rx6eRJ94oJRfaR584L0YJRfPLhXVjNIW9EhJlCjZJUp2iZKLJ0p2iZJdEiXZJUp2iZKLJ0p2iZJdEiVRskt2iZIoUbJLlOySKImSXbJLlESJkl2iZJdcPEqiZJfsEiVRomSXKNklSi6eKNklu0RJlCjZJUp2iZKLJ0p2iZJdEiXZJUp2iZKLJ0p2iZJdEiVRskt2iZIoUbJLlOySKImSXbJLlESJkl2iZJdcPEqiZJfsEiVRomSXKNklSi6eKNklu0RJlCjZJUp2iZKLp/Gqh/nYs9QehL7Ww9+jGaXvpfPoE08ZdunSxRMlSn5fouT3JUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1y8SiJkl2yS5REiZJdomSXKLl4omSX7BIlUaJklyjZJUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1y8SiJkl2yS5REiZJdomSXKLl4omSX7BIlUaJklyjZJUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1yTiiJkl2yS5REiZJdomSXXDxKomSX7BIlUaJklyjZJUounijZJbtESZQo2SVKdomSiydKdomSXRIl2SVKdomSiydKdomSXRIlUbJLdomSKFGyS5TskiiJkl2yS5REiZJdomSXXDxKomSX7BIlUaJklyjZJUounijZJbtESZQo2SVKdomSiydKdomSXRIl2SVKdqnn6kE+tbuLPrHe9A/LZV+ftB/9obqNP9Nr8JNSkUrVc4GfdN/bJzWNiydKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlNRzf/v7eBMvKnNvcaW9K68tc7cvLp5dEiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKKk309fup91FKTesdbFsfvUx/mTkmoYYAAAAASUVORK5CYII=) no-repeat center; background-size: contain; }
.mxj-bottom-nav a.not_settlement_shortcut .mxj-nav-icon { background-position: 41px -7px; }

.mxj-bottom-nav a.bottom-nav-active {
    background: #132e7b;
    border-color: #132e7b;
    color: #fff;
}
.mxj-bottom-nav a.bottom-nav-active .mxj-nav-cell { color: #fff; }
.mxj-bottom-nav a.bottom-nav-active .mxj-nav-icon {
    filter: brightness(0) invert(1);
    -webkit-filter: brightness(0) invert(1);
}
</style>
<div class="sc-1ng8zp5-0 iBBuud menulist mxj-bottom-nav">
    <a class="betting_shortcut<?php if ($this->_tpl_vars['bottom_menu_active'] == 'game'): ?> bottom-nav-active<?php endif; ?>" type="/creditmobile/load" href="javascript:void(0)">
        <span class="mxj-nav-cell">
            <span class="mxj-nav-icon"></span>
            游戏
        </span>
    </a>
    <a class="result_shortcut<?php if ($this->_tpl_vars['bottom_menu_active'] == 'result'): ?> bottom-nav-active<?php endif; ?>" type="/creditmobile/dresult" href="javascript:void(0)">
        <span class="mxj-nav-cell">
            <span class="mxj-nav-icon"></span>
            开奖
        </span>
    </a>
    <a class="not_settlement_shortcut<?php if ($this->_tpl_vars['bottom_menu_active'] == 'report'): ?> bottom-nav-active<?php endif; ?>" type="/creditmobile/report" href="javascript:void(0)">
        <span class="mxj-nav-cell">
            <span class="mxj-nav-icon"></span>
            未结
        </span>
    </a>
</div>