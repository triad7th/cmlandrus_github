//////////////////////////////////////////////////////
//
// THEME_MODULE : CM_ALPHA/CM_MENU
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/modules/cm_menu/js/cm-menu.js
//
//////////////////////////////////////////////////////

//
// cm-menu selector
//
// show a block when a mouse point hovers a menu item

const cm_menu_ent_dur = 100;
const cm_menu_leave_dur = 100;

const cm_img_menu_ent_dur = 100;
const cm_img_menu_leave_dur = 100;
const cm_menu_opacity = 0.3;

const cm_img_opacity_max = 0.9;
const cm_img_opacity_min = 0.9;


$(document).ready( function() {
    $(".cm-menu-item").mouseenter( function() {
        if ($(this).hasClass("cm-img-menu-item")) {
            $(this).children(".cm-menu-selector").fadeTo(cm_img_menu_ent_dur,cm_menu_opacity);
            $(this).find("img").fadeTo(cm_img_menu_ent_dur,cm_img_opacity_max);
        } else
        $(this).children(".cm-menu-selector").fadeTo(cm_menu_ent_dur,cm_menu_opacity);
    });

    $(".cm-menu-item").mouseleave( function() {
        if ($(this).hasClass("cm-img-menu-item")) {
            $(this).children(".cm-menu-selector").fadeTo(cm_img_menu_leave_dur,0);
            $(this).find("img").fadeTo(cm_img_menu_ent_dur,cm_img_opacity_min);
        } else
        $(this).children(".cm-menu-selector").fadeTo(cm_menu_leave_dur,0);
    });
});